<?php

namespace App\Jobs\Payouts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

use App\Models\Payout;
use App\Services\RequestService;

class ProcessPayoutJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(protected int $payoutId) {}

    public function handle(RequestService $requestService): void
    {
        $payout = Payout::find($this->payoutId);

        if (!$payout || $payout->status !== 'initiated') {
            return;
        }

        try {
            $payload = [
                'apiId'          => config('sprintnxt-endpoints.api_id'),
                'bankId'         => config('sprintnxt-endpoints.bank_id'),
                'acctNumber'     => config('sprintnxt-endpoints.source_account_number'),
                'beneAcctNumber' => $payout->account_number,
                'amount'         => (int) $payout->amount,
                'purpose'        => $payout->purpose,
                'mode'           => $payout->mode,
                'name'           => $payout->account_holder,
                'mobile'         => $payout->mobile,
                'ifsc'           => $payout->ifsc_code,
                'city'           => $payout->city,
                'state'          => $payout->state,
                'pincode'        => $payout->pincode,
                'bankname'       => $payout->bank_name,
                'branchname'     => $payout->branch_name,
                'beneaddress'    => $payout->beneficiary_address,
                'branchCode'     => $payout->branch_code,
                'transferId'     => $payout->transaction_id,
            ];

            $response = $requestService->post('payout/PAYOUT', $payload);

            // API returns: status (bool), responsecode (int), data.txn_status, data.utr
            $utr            = $response['data']['utr'] ?? null;
            $txnStatus      = $response['data']['txn_status'] ?? null;
            $sprintnxtTxnId = $response['data']['sprintnxt_txn_id'] ?? null;
            $isSuccess      = ($response['status'] === true || $response['responsecode'] === 1);

            if ($isSuccess && $txnStatus !== null) {
                $dbStatus = match ((int) $txnStatus) {
                    0       => 'initiated',
                    1       => ($utr ? 'success' : 'pending'),
                    2       => 'pending',
                    3       => 'processed',
                    4       => 'failed',
                    6       => 'send_to_bank',
                    default => 'pending',
                };
                $payout->update([
                    'status'           => $dbStatus,
                    'utr'              => $utr ?: null,
                    'sprintnxt_txn_id' => $sprintnxtTxnId,
                    'txn_status'       => $txnStatus,
                    'processed_at'     => now(),
                ]);
            } else {
                $payout->update([
                    'status'       => 'failed',
                    'processed_at' => now(),
                    'remarks'      => $response['message'] ?? 'Payout failed',
                ]);
            }
        } catch (\Exception $e) {
            Log::error("ProcessPayoutJob [{$payout->transaction_id}]: " . $e->getMessage());
            $payout->update([
                'status'       => 'failed',
                'processed_at' => now(),
                'remarks'      => $e->getMessage(),
            ]);
            throw $e; // allow queue retry
        }
    }
}
