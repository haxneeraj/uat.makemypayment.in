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



class PayoutService
{
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
                return $this->errorResponse('Bank account id is not configured.');
            }

            $payload = [
                'apiId'      => config('sprintnxt-endpoints.account_balance_api_id', '30003'),
                'bankId'     => (string) $bankId,
                'acctNumber' => $acctNumber,
            ];

            Log::info('Fetching SprintNXT account balance.', [
                'apiId'      => $payload['apiId'],
                'bankId'     => $payload['bankId'],
                'acctNumber' => Str::mask($acctNumber, 'X', 3, max(strlen($acctNumber) - 6, 0)),
            ]);

            $response = $this->requestService->post('payout/PAYOUT', $payload);

            $isSuccess = (($response['status'] ?? false) === true)
                || ((int) ($response['responsecode'] ?? 0) === 1);

            if (!$isSuccess) {
                return $this->errorResponse($response['message'] ?? 'Unable to fetch account balance from gateway.');
            }

            $data = is_array($response['data'] ?? null) ? $response['data'] : [];

            $data = [
                'accountnumber'    => (string) ($data['accountnumber'] ?? $acctNumber),
                'netBalance'       => (float) ($data['netBalance'] ?? 0),
                'availableBalance' => (float) ($data['availableBalance'] ?? 0),
                'raw'              => $response,
            ];

            return $this->successResponse($data['message'] ?? 'Balance fetched successfully.', $data);
        } catch (\Exception $e) {
            Log::error('PayoutService getAccountBalance error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse('Something went wrong while fetching the account balance. Please try again.');
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
    private function resolveMode(string $bankName, float $amount): string
    {
        if (stripos($bankName, 'hdfc') !== false) {
            return 'a2a';
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

        $fee = round($fee, 2);
        $totalAmount = round($amount + $fee, 2);

        return [
            'fee' => $fee,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Validate daily transfer limit and wallet balance, then deduct wallet.
     */
    private function validateAndDeductWallet(User $user, float $totalDebit): void
    {
        $todayConsumed = (float) Payout::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereIn('status', ['initiated', 'pending', 'processed', 'send_to_bank', 'success'])
            ->sum(DB::raw('CASE WHEN total_amount > 0 THEN total_amount ELSE amount END'));

        if (($todayConsumed + $totalDebit) > (float) $user->daily_transfer_limit) {
            throw new \Exception('Daily transfer limit exceeded.');
        }

        $wallet = MerchantVirtualAccount::where('user_id', $user->id)
            ->lockForUpdate()
            ->first();

        if (!$wallet) {
            throw new \Exception('Virtual account not found for wallet deduction.');
        }

        if ((float) $wallet->balance < $totalDebit) {
            throw new \Exception('Insufficient wallet balance.');
        }

        $wallet->balance = round(((float) $wallet->balance - $totalDebit), 2);
        $wallet->save();
    }

    /**
     * Refund a previously deducted amount back to the merchant's wallet.
     * Used when a payout is rejected by the gateway after wallet deduction.
     */
    private function refundWallet(User $user, float $amount): void
    {
        MerchantVirtualAccount::where('user_id', $user->id)->increment('balance', $amount);
        Log::info("Wallet refunded ₹{$amount} for user {$user->id} due to payout gateway failure.");
    }

    /**
     * Initiate a single payout via SprintNXT and persist the record.
     * Returns the local transaction_id on success.
     */
    public function createSinglePayout(SinglePayoutDTO $payout, User $user): array
    {
        $payoutRecord = null;

        try {
            // Generate a unique transferId (8-20 chars) shared with SprintNXT
            $transferId = strtoupper(Str::random(16));

            $feeAndTotal = $this->calculateFeeAndTotal($user, (float) $payout->amount);

            $payoutRecord = DB::transaction(function () use ($user, $transferId, $payout, $feeAndTotal) {
                // Validate wallet + limit and deduct at initiation time.
                $this->validateAndDeductWallet($user, (float) $feeAndTotal['total_amount']);

                // Persist payout with status 'initiated'
                return Payout::create([
                    'user_id'             => $user->id,
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
                    'mode'                => $payout->mode,
                    'status'              => 'initiated',
                    'purpose'             => $payout->purpose,
                    'remarks'             => $payout->remarks,
                    'narration'           => $payout->narration,
                ]);
            });

            // Build SprintNXT payload
            // Required fields always sent
            $payload = [
                'apiId'          => config('sprintnxt-endpoints.api_id'),
                'bankId'         => config('sprintnxt-endpoints.bank_id'),
                'acctNumber'     => config('sprintnxt-endpoints.source_account_number'),
                'beneAcctNumber' => $payout->accountNumber,
                'amount'         => number_format($payout->amount, 2, '.', ''),
                'purpose'        => $payout->purpose,
                'mode'           => $payout->mode,
                'name'           => $payout->accountHolder,
                'mobile'         => $payout->mobile,
                'ifsc'           => $payout->ifscCode,
                'city'           => $payout->city,
                'bankname'       => $payout->bankName,
                'branchname'     => $payout->branchName,
                'beneaddress'    => $payout->beneficiaryAddress,
                'branchCode'     => $payout->branchCode,
                'transferId'     => $transferId,
                'type'           => $payout->type ?? '1',
            ];

            \Log::info('Initiating payout with payload: ' . json_encode($payload));

            // Optional fields — only added when present
            if ($payout->state !== null)         $payload['state']      = $payout->state;
            if ($payout->pincode !== null)        $payload['pincode']    = $payout->pincode;
            if ($payout->email !== null)          $payload['email']      = $payout->email;
            if ($payout->remarks !== null)        $payload['remarks']    = $payout->remarks;
            if ($payout->narration !== null)      $payload['narration']  = $payout->narration;

            // Required when type = 2
            if ($payout->beneMode !== null)       $payload['bene_mode']  = $payout->beneMode;
            if ($payout->beneType !== null)       $payload['bene_type']  = $payout->beneType;
            if ($payout->beneBankId !== null)     $payload['bene_bankid'] = $payout->beneBankId;

            // Call SprintNXT API
            $response = $this->requestService->post('payout/PAYOUT', $payload);

            // API returns: status (bool), responsecode (int), data.txn_status (int), data.utr (string)
            $utr            = $response['data']['utr'] ?? null;
            $txnStatus      = $response['data']['txn_status'] ?? null;
            $sprintnxtTxnId = $response['data']['sprintnxt_txn_id'] ?? null;
            $sprintnxtLoggerId = $response['data']['logger_id'] ?? null;
            $isSuccess      = ($response['status'] === true || $response['responsecode'] === 1);
            
            $isAuthError = ($response['status'] === false && $response['responsecode'] === 4 && isset($response['message']) && str_contains(strtolower($response['message']), 'unauthorized request'));
            if($isAuthError) {
                Cache::forget('sprintnxt_auth_token');
                Log::warning("PayoutService createSinglePayout received auth error from gateway. Cleared cached token.");

                return $this->errorResponse('Gateway authorization failed. Please retry payout.');
            }

            if ($isSuccess && $txnStatus !== null) {
                $dbStatus = $this->mapTxnStatus((int) $txnStatus, $utr ?: null);
                $payoutRecord->update([
                    'status'           => $dbStatus,
                    'utr'              => $utr ?: null,
                    'sprintnxt_txn_id' => $sprintnxtTxnId,
                    'sprintnxt_logger_id' => $sprintnxtLoggerId,
                    'txn_status'       => $txnStatus,
                    'processed_at'     => now(),
                ]);

                return $this->successResponse('Payout initiated successfully', ['transaction_id' => $payoutRecord->transaction_id]);
            } else {
                $payoutRecord->update([
                    'status'              => 'failed',
                    'sprintnxt_logger_id' => $sprintnxtLoggerId,
                    'processed_at'        => now(),
                ]);

                // Refund wallet since API rejected the payout
                $this->refundWallet($user, (float) $feeAndTotal['total_amount']);

                return $this->errorResponse($response['message'] ?? 'Payout failed');
            }

            return $this->successResponse('Payout initiated successfully', ['transaction_id' => $payoutRecord->transaction_id]);
        } catch (\Exception $e) {
            \Log::error([
                'message' => "Error in createSinglePayout: " . $e->getMessage(),
                'payload' => isset($payload) ? json_encode($payload) : 'N/A',
            ]);
            return $this->errorResponse('Something went wrong while processing the payout. Please try again.');
        } finally {
            if ($payoutRecord instanceof Payout) {
                //$this->dispatchPayoutMailEvent($payoutRecord);
            }
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
        $payoutMap    = [];  // transferId => Payout model
        $transactions = [];

        DB::transaction(function () use ($payouts, $user, $batch, &$payoutMap, &$transactions) {
            $totalDebit = 0;
            foreach ($payouts as $item) {
                $calc = $this->calculateFeeAndTotal($user, (float) $item->amount);
                $totalDebit += $calc['total_amount'];
            }
            $this->validateAndDeductWallet($user, round($totalDebit, 2));

            foreach ($payouts as $payout) {
                $transferId   = strtoupper(Str::random(16));
                $resolvedMode = $this->resolveMode($payout->bankName, (float) $payout->amount);
                $feeAndTotal  = $this->calculateFeeAndTotal($user, (float) $payout->amount);

                $payoutRecord = Payout::create([
                    'user_id'             => $user->id,
                    'batch_id'            => $batch->id,
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
                ]);

                $payoutMap[$transferId] = $payoutRecord;

                $transactions[] = [
                    'mode'            => $resolvedMode,
                    'beneAcctNumber'  => $payout->accountNumber,
                    'amount'          => (float) $payout->amount,
                    'purpose'         => $payout->purpose,
                    'addressLine'     => $payout->beneficiaryAddress ?? 'NA',
                    'benePartTrnRmks' => $payout->remarks ?? 'NA',
                    'type'            => $payout->type ?? '1',
                    'name'            => $payout->accountHolder,
                    'mobile'          => (int) $payout->mobile,
                    'ifsc'            => $payout->ifscCode,
                    'bankname'        => $payout->bankName,
                    'branchname'      => $payout->branchName,
                    'beneaddress'     => $payout->beneficiaryAddress ?? 'NA',
                    'transferId'      => $transferId,
                ];
            }
        });

        // ── 3. Single SprintNXT bulk payout API call ─────────────────────────
        $payload = [
            'apiId'          => '30028',
            'bankId'         => config('sprintnxt-endpoints.bank_id'),
            'batch_id'       => $batchId,
            'batch_count'    => (string) count($transactions),
            'is_sender_same' => 'Y',
            'sender_acct_no' => config('sprintnxt-endpoints.source_account_number'),
            'transactions'   => $transactions,
        ];

        // Log Payload with masked account numbers
        \Log::info("Bulk Payout Payload: " . json_encode($payload, JSON_UNESCAPED_UNICODE));

        \Log::info('Initiating bulk payout batch_id=' . $batchId . ' count=' . count($transactions));

        $response = $this->requestService->post('payout/PAYOUT', $payload);

        $isSuccess = ($response['status'] === true || ($response['responsecode'] ?? '') === 'success');

        // ── 4. Process accepted / rejected from response ─────────────────────
        $acceptedIds   = [];
        $acceptedTxIds = [];

        if ($isSuccess) {
            $accepted = $response['data']['transaction']['accept'] ?? [];
            $rejected = $response['data']['transaction']['reject'] ?? [];

            // Mark accepted payouts as 'initiated' (already set, but capture IDs)
            foreach ($accepted as $item) {
                $tid = (string) ($item['transferId'] ?? '');
                if (isset($payoutMap[$tid])) {
                    $acceptedIds[]   = $payoutMap[$tid]->id;
                    $acceptedTxIds[] = $tid;
                }
            }

            // Mark rejected payouts as 'failed' and refund their amounts
            foreach ($rejected as $item) {
                $tid = (string) ($item['transferId'] ?? '');
                if (isset($payoutMap[$tid])) {
                    $record = $payoutMap[$tid];
                    $record->update([
                        'status'       => 'failed',
                        'processed_at' => now(),
                    ]);
                    // Refund total_amount (amount + fee) back to wallet
                    $this->refundWallet($user, (float) $record->total_amount);
                }
            }

            // Any transferId not in accept or reject list — mark failed and refund
            $knownIds = array_merge(
                array_column($accepted, 'transferId'),
                array_column($rejected, 'transferId'),
            );
            foreach ($payoutMap as $tid => $record) {
                if (!in_array($tid, $knownIds, true)) {
                    $record->update(['status' => 'failed', 'processed_at' => now()]);
                    $this->refundWallet($user, (float) $record->total_amount);
                }
            }

            // foreach ($payoutMap as $record) {
            //     $this->dispatchPayoutMailEvent($record);
            // }
        } else {
            // Whole batch was rejected — mark all as failed and refund entire batch debit
            Payout::whereIn('id', array_map(fn($r) => $r->id, array_values($payoutMap)))
                ->update(['status' => 'failed', 'processed_at' => now()]);

            // Refund the total deducted amount for the entire batch
            $totalRefund = array_sum(array_map(fn($r) => (float) $r->total_amount, array_values($payoutMap)));
            $this->refundWallet($user, round($totalRefund, 2));

            // foreach ($payoutMap as $record) {
            //     $this->dispatchPayoutMailEvent($record);
            // }

            return $this->errorResponse($response['message'] ?? 'Bulk payout request failed');
        }

        // ── 5. Update batch record with gateway response ─────────────────────
        $batch->update([
            'system_batch_id' => $response['data']['bulk_system_batch_id'] ?? null,
            'accepted_count'  => $response['data']['accepted'] ?? count($acceptedIds),
            'rejected_count'  => $response['data']['rejected'] ?? (count($payoutMap) - count($acceptedIds)),
            'tracker_id'      => $response['tracker_id'] ?? null,
        ]);

        return $this->successResponse('Payouts processed successfully', ['accepted_tx_ids' => $acceptedTxIds]);
    }

    public function getPayoutStatusByTransactionId(string $transactionId): array
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
                if ($utr && !$payout->utr) {
                    $updates['utr'] = $utr;
                }
                if (!$payout->processed_at && in_array($status, ['success', 'failed', 'processed'])) {
                    $updates['processed_at'] = now();
                }
                $payout->update($updates);
            }

            return $this->successResponse('Payout status fetched successfully', ['status' => $payout->fresh()->status]);

        } catch (\Exception $e) {
            \Log::error("Error fetching payout status for transaction ID {$transactionId}: " . $e->getMessage());
            \Log::error([
                'message' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse('Something went wrong while fetching the payout status. Please try again.');
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
                if ($utr && !$payout->utr) {
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

    public function successResponse($message, $data = []): array
    {
        return [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function errorResponse($message, $data = []): array
    {
        return [
            'status' => false,
            'message' => $message,
            'data' => $data,
        ];
    }
}
