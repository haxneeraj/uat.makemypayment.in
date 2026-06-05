<?php

namespace App\Services;

use App\Events\PayoutProcessed;
use App\Services\RequestService;

use App\Dto\SinglePayoutDTO;

use App\Models\Payout;
use App\Models\User;
use App\Models\MerchantVirtualAccount;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use App\Traits\ResponseTrait;
use App\Traits\LogTrait;
use App\Traits\HasGenerateTransferIdTrait;

use App\Jobs\Payouts\ProcessPayoutJob;
use App\Jobs\Payouts\ProcessBulkPayoutJob;

class PayoutService
{
    use  ResponseTrait, LogTrait, HasGenerateTransferIdTrait;

    protected RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * Fetch source account balance from SprintNXT.
     * API: payout/PAYOUT, apiId=30003
     *
     * @param string|null $accountNumber Optional override account number.
     * @return array{
     *   accountnumber:string,
     *   netBalance:float,
     *   availableBalance:float,
     *   message:string,
     *   raw:array
     * }
     */
    public function getAccountBalance(?string $accountNumber = null): array
    {
        try {
            $acctNumber = trim((string) ($accountNumber ?: config('sprintnxt-endpoints.source_account_number')));
            $bankId = config('sprintnxt-endpoints.bank_id');

            if ($acctNumber === '') {
                return $this->errorResponse('Source account number is missing for balance check.');
            }

            if (blank($bankId)) {
                return $this->errorResponse('Bank id is not configured.');
            }

            $payload = [
                'apiId'      => config('sprintnxt-endpoints.account_balance_api_id', '30003'),
                'bankId'     => (string) $bankId,
                'acctNumber' => $acctNumber,
            ];

            $response = $this->requestService->post('payout/PAYOUT', $payload);

            $isSuccess = (($response['status'] ?? false) === true)
                || ((int) ($response['responsecode'] ?? 0) === 1);

            if (!$isSuccess) {
                return $this->errorResponse($response['message'] ?? 'Unable to fetch account balance from gateway.');
            }

            $data = is_array($response['data'] ?? null) ? $response['data'] : [];
            return $this->successResponse([
                'availableBalance'    => $data['availableBalance'] ?? 0,
            ], 'Account balance fetched successfully.');

        } catch (\Exception $e) {
            $this->logError($e);
            return $this->errorResponse('An error occurred while fetching account balance. Please try again later.');
        }
    }

    /**
     * Map SprintNXT txn_status code to our DB status.
     * txn_status: 0=Initiated, 1=Confirm Success, 2=Pending, 3=Send to Bank, 4=Confirm Failure, 6=Processed
     * Note: txn_status 1 with blank UTR = pending (not yet settled)
     */
    private function mapTxnStatus(int $txnStatus, ?string $utr): string
    {
        return match ($txnStatus) {
            0       => 'initiated',
            1       => ($utr ? 'success' : 'pending'),
            2       => 'pending',
            3       => 'send_to_bank',
            4       => 'failed',
            6       => 'processed',
            default => 'pending',
        };
    }

    /**
     * Automatically resolve the transfer mode based on bank name and amount.
     *
     * Rules:
     *  - HDFC bank              → a2a  (internal account-to-account)
     *  - Amount ≤  2,00,000     → imps (instant, up to ₹2L)
     *  - Amount ≤  9,99,999     → neft (batch, same-day)
     *  - Amount ≥ 10,00,000     → rtgs (high-value, same-day)
     */
    private function resolveMode(string $ifsc_code, float $amount, string $mode = null): string
    {
        if (stripos($ifsc_code, 'HDFC') !== false) {
            return 'a2a';
        }

        if($mode) {
            return $mode;
        }

        return match (true) {
            $amount <= 200000  => 'imps',
            $amount <= 999999  => 'neft',
            default            => 'rtgs',
        };
    }

    /**
     * Calculate payout fee and total debit based on merchant pricing slab.
     * - amount <= 1000: fixed charge (below_thousand_charge)
     * - amount > 1000: percentage charge (above_thousand_charge)
     */
    private function calculateFeeAndTotal(User $user, float $amount): array
    {
        if ($amount <= 1000) {
            $fee = (float) $user->below_thousand_charge;
        } else {
            $fee = ($amount * (float) $user->above_thousand_charge) / 100;
        }

        $totalAmount = $amount + $fee;

        return [
            'fee' => $fee,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Validate daily transfer limit and wallet balance, then deduct wallet.
     */
    private function validateAndDeductWallet(User $user, float $totalDebit): array
    { 
        $wallet = MerchantVirtualAccount::where('user_id', $user->id)
        ->lockForUpdate()
        ->first();

        $openingBalance = (float) $wallet->balance;

        if (!$wallet) {
            return $this->errorResponse('Virtual account not found for wallet deduction.', 400);
        }

        if ((float) $wallet->balance < $totalDebit) {
            return $this->errorResponse('Insufficient wallet balance.', 400);
        }

        $todayConsumed = (float) Payout::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereIn('status', ['initiated', 'pending', 'processed', 'send_to_bank', 'success'])
            ->sum(DB::raw('CASE WHEN total_amount > 0 THEN total_amount ELSE amount END'));

        if (($todayConsumed + $totalDebit) > (float) $user->daily_transfer_limit) {
            return $this->errorResponse('Daily transfer limit exceeded. Please try again tomorrow.', 400);
        }

        $wallet->balance = round(((float) $wallet->balance - $totalDebit), 2);
        $wallet->save();

        $closingBalance = (float) $wallet->balance;

        return $this->successResponse([
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
        ]);
    }

    /**
     * Refund a previously deducted amount back to the merchant's wallet.
     * Used when a payout is rejected by the gateway after wallet deduction.
     */
    private function refundWallet(User $user, float $amount): void
    {
        MerchantVirtualAccount::where('user_id', $user->id)->increment('balance', $amount);
        $this->logInfo("Wallet refunded ₹{$amount} for user {$user->id} due to payout gateway failure.");
    }

    /**
     * Initiate a single payout via SprintNXT and persist the record.
     * Returns the local transaction_id on success.
     */
    public function createSinglePayout(SinglePayoutDTO $payout, User $user): array
    {
        $payoutRecord = null;

        // DB transaction to ensure atomicity of wallet deduction and payout record creation
        DB::beginTransaction();

        try {
            // Generate a unique transferId 30 digits shared with SprintNXT
            $transferId = $this->generateTransferId();

            // Calculate fee and total debit amount based on merchant's pricing slab
            $feeAndTotal = $this->calculateFeeAndTotal($user, (float) $payout->amount);

            // Validate wallet + limit and deduct at initiation time.
            $walletValidation = $this->validateAndDeductWallet($user, (float) $feeAndTotal['total_amount']);
            if ($walletValidation['status'] === 'error') {
                // Rollback DB transaction if wallet validation fails (e.g. insufficient balance, limit exceeded)
                DB::rollBack();
                return $this->errorResponse($walletValidation['message'] ?? 'Wallet validation failed.', 400);
            }

            $resolvedMode = $this->resolveMode($payout->ifscCode, (float) $payout->amount, $payout->mode);
            

            // Create payout record in the database
            $payoutRecord = Payout::create([
                'user_id'             => $user->id,
                'merchant_reference_id' => $payout->merchantReferenceId,
                'transaction_id'      => $transferId,
                'account_holder'      => $payout->accountHolder,
                'account_number'      => $payout->accountNumber,
                'ifsc_code'           => $payout->ifscCode,
                'branch_code'         => $payout->branchCode,
                'bank_name'           => $payout->bankName,
                'branch_name'         => $payout->branchName,
                'mobile'              => $payout->mobile,
                'email'               => $payout->email,
                'city'                => $payout->city,
                'state'               => $payout->state,
                'pincode'             => $payout->pincode,
                'beneficiary_address' => $payout->beneficiaryAddress,
                'amount'              => $payout->amount,
                'fee'                 => $feeAndTotal['fee'],
                'total_amount'        => $feeAndTotal['total_amount'],
                'opening_balance'     => $walletValidation['data']['opening_balance'],
                'closing_balance'     => $walletValidation['data']['closing_balance'],
                'mode'                => $resolvedMode,
                'status'              => 'initiated',
                'purpose'             => $payout->purpose,
                'remarks'             => $payout->remarks,
                'narration'           => $payout->narration,
                'initiated_from'      => $payout->initiatedFrom ?? 'api',
                'initiated_at'        => now(),
            ]);

            // Dispatch Payout Job to process the payout asynchronously, so that API response is not delayed by external call
            // ProcessPayoutJob::dispatch($payoutRecord->id)
            // ->onConnection('redis')
            // ->afterCommit();

            // Commit DB transaction before calling external API to ensure data consistency
            DB::commit();

            return $this->successResponse([
                'transaction_id' => $payoutRecord->transaction_id,
                'merchant_reference_id' => $payoutRecord->merchant_reference_id,
                'utr' => '', // UTR will be updated after processing the payout
                'status' => $payoutRecord->status,
                'amount' => $payoutRecord->amount,
                'beneficiary' => 
                [
                    'beneficiary_name' => $payoutRecord->accountHolder,
                    'account_number' => $payoutRecord->accountNumber,
                    'ifsc_code' => $payoutRecord->ifscCode,
                    'bank_name' => $payoutRecord->bankName,
                ],
            ], 'Payout initiated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return $this->errorResponse('Some internal error occurred. Please try again later.', 500);
        }
    }

    /**
     * Initiate multiple payouts via a single SprintNXT bulk payout API call.
     * All payout records are persisted first, then one API request is made.
     * Returns an array of accepted transaction IDs.
     */
    public function createBulkPayout(array $payouts, User $user): array
    {
        $batchId     = strtoupper(Str::random(16));
        $totalAmount = array_sum(array_map(fn($p) => $p->amount, $payouts));

        // ── 1. Create the batch header record ────────────────────────────────
        $batch = \App\Models\BatchPayout::create([
            'user_id'      => $user->id,
            'batch_id'     => $batchId,
            'batch_count'  => count($payouts),
            'batch_amount' => $totalAmount,
        ]);

        // ── 2. Persist all individual payout rows and build transactions array        
        $transactions = [];

        DB::beginTransaction();

        try{
            $totalDebit = 0;
            foreach ($payouts as $item) {
                $calc = $this->calculateFeeAndTotal($user, (float) $item->amount);
                $totalDebit += $calc['total_amount'];
            }
            $walletValidation = $this->validateAndDeductWallet($user, round($totalDebit, 2));
            if ($walletValidation['status'] === 'error') {
                // Rollback DB transaction if wallet validation fails (e.g. insufficient balance, limit exceeded)
                DB::rollBack();
                return $this->errorResponse($walletValidation['message'] ?? 'Wallet validation failed.', 400);
            }

            foreach ($payouts as $payout) {
                $transferId   = $this->generateTransferId();
                $resolvedMode = $this->resolveMode($payout->ifscCode, (float) $payout->amount, $payout->mode);
                $feeAndTotal  = $this->calculateFeeAndTotal($user, (float) $payout->amount);

                $payoutRecord = Payout::create([
                    'user_id'             => $user->id,
                    'batch_id'            => $batch->id,
                    'merchant_reference_id' => $payout->merchantReferenceId,
                    'transaction_id'      => $transferId,
                    'account_holder'      => $payout->accountHolder,
                    'account_number'      => $payout->accountNumber,
                    'ifsc_code'           => $payout->ifscCode,
                    'branch_code'         => $payout->branchCode,
                    'bank_name'           => $payout->bankName,
                    'branch_name'         => $payout->branchName,
                    'mobile'              => $payout->mobile,
                    'email'               => $payout->email,
                    'city'                => $payout->city,
                    'state'               => $payout->state,
                    'pincode'             => $payout->pincode,
                    'beneficiary_address' => $payout->beneficiaryAddress,
                    'amount'              => $payout->amount,
                    'fee'                 => $feeAndTotal['fee'],
                    'total_amount'        => $feeAndTotal['total_amount'],
                    'mode'                => $resolvedMode,
                    'status'              => 'initiated',
                    'purpose'             => $payout->purpose,
                    'remarks'             => $payout->remarks,
                    'narration'           => $payout->narration,
                    'initiated_from'      => $payout->initiatedFrom ?? 'api',
                    'initiated_at'        => now(),
                    'opening_balance'     => $walletValidation['data']['opening_balance'],
                    'closing_balance'     => $walletValidation['data']['closing_balance'],
                ]);

                $transactions[] = [
                    'transaction_id' => $payoutRecord->transaction_id,
                    'merchant_reference_id' => $payoutRecord->merchant_reference_id,
                    'utr' => '', // UTR will be updated after processing the payout
                    'status' => $payoutRecord->status,
                    'amount' => $payoutRecord->amount,
                    'beneficiary' => 
                    [
                        'beneficiary_name' => $payoutRecord->accountHolder,
                        'account_number' => $payoutRecord->accountNumber,
                        'ifsc_code' => $payoutRecord->ifscCode,
                        'bank_name' => $payoutRecord->bankName,
                    ],
                ];
            }        

            // Dispatch Bulk Payout Job to process the entire batch asynchronously, so that API response is not delayed by external call
            // ProcessBulkPayoutJob::dispatch($batch->id)
            // ->onConnection('redis')
            // ->afterCommit();

            // Commit DB transaction before calling external API to ensure data consistency
            DB::commit();

            // Note: The actual API call to SprintNXT for bulk payout will be made inside the ProcessBulkPayoutJob, which will iterate through the batch and update each payout's status accordingly.
            
            // Return the list of transactions that were accepted for processing (with initial status 'initiated')
            return $this->successResponse($transactions, 'Payout initiated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return $this->errorResponse('Some internal error occurred while creating payouts. Please try again later.', 500);
        }
    }

    public function getPayoutStatusByTransactionId(string $transactionId): ?string
    {
        try {
            $payout = Payout::where('transaction_id', $transactionId)->first();

            if (!$payout) {
                throw new \Exception("Payout with transaction ID {$transactionId} not found");
            }

            $payload = [
                'apiId'          => '30011',
                'bankId'         => config('sprintnxt-endpoints.bank_id'),
                'transferId'     => $transactionId,
                'sprintnxtTxnId' => null,
            ];

            \Log::info("Status Payload: " . json_encode($payload));

            $response = $this->requestService->post('payout/PAYOUT', $payload);

            $isSuccess = ($response['status'] === true || $response['responsecode'] === 1);
           

            if ($isSuccess && isset($response['data']['txn_status'])) {
                $utr    = $response['data']['utr'] ?? null;
                $status = $this->mapTxnStatus((int) $response['data']['txn_status'], $utr ?: null);

                $updates = ['status' => $status];
                if ($utr) {
                    $updates['utr'] = $utr;
                }
                if (!$payout->processed_at && in_array($status, ['success', 'failed', 'processed'])) {
                    $updates['processed_at'] = now();
                }
                $payout->update($updates);
            }

            return $payout->fresh()->status;

        } catch (\Exception $e) {
            \Log::error("Error fetching payout status for transaction ID {$transactionId}: " . $e->getMessage());
            return null;
        }
    }

    public function getPayoutStatusBySprintNxtTxnId(string $sprintnxtTxnId): ?string
    {
        try {
            $payout = Payout::where('sprintnxt_txn_id', $sprintnxtTxnId)->first();

            if (!$payout) {
                throw new \Exception("Payout with SprintNXT TxnID {$sprintnxtTxnId} not found");
            }

            $payload = [
                'apiId'          => '30011',
                'bankId'         => config('sprintnxt-endpoints.bank_id'),
                'transferId'     => null,
                'sprintnxtTxnId' => $sprintnxtTxnId,
            ];

            $response = $this->requestService->post('payout/PAYOUT', $payload);

            $isSuccess = ($response['status'] === true || $response['responsecode'] === 1);

            if ($isSuccess && isset($response['data']['txn_status'])) {
                $utr    = $response['data']['utr'] ?? null;
                $status = $this->mapTxnStatus((int) $response['data']['txn_status'], $utr ?: null);

                $updates = ['status' => $status];
                if ($utr) {
                    $updates['utr'] = $utr;
                }
                if (!$payout->processed_at && in_array($status, ['success', 'failed', 'processed'])) {
                    $updates['processed_at'] = now();
                }
                $payout->update($updates);
            }

            return $payout->fresh()->status;

        } catch (\Exception $e) {
            \Log::error("Error fetching payout status for SprintNXT TxnID {$sprintnxtTxnId}: " . $e->getMessage());
            return null;
        }
    }

    private function dispatchPayoutMailEvent(Payout $payout): void
    {
        try {
            event(new PayoutProcessed($payout->fresh(['user'])));
        } catch (\Throwable $e) {
            Log::warning('Payout mail event dispatch failed.', [
                'payout_id' => $payout->id,
                'transaction_id' => $payout->transaction_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
