<?php

namespace App\Jobs\Payouts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

use App\Models\Payout;
use App\Models\BatchPayout;
use App\Services\RequestService;

class ProcessBulkPayoutJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30;

    protected RequestService $requestService;

    protected $batchId;

    public function __construct($batchId)
    {
        $this->onQueue('process-payout');
        $this->batchId = $batchId;
        $this->requestService = new RequestService();
    }

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

    public function handle(): void
    {
        $batchPayout = BatchPayout::with('payouts')->find($this->batchId);

        if (!$batchPayout) {
            Log::channel('payout')->error("BatchPayout with ID {$this->batchId} not found.");
            return;
        }

        $transactions = $batchPayout->payouts->map(function (Payout $payout) {
            return[
                'mode'            => $payout->mode,
                'beneAcctNumber'  => $payout->account_number,
                'amount'          => (float) $payout->amount,
                'purpose'         => $payout->purpose,
                'addressLine'     => $payout->beneficiary_address ?? 'NA',
                'benePartTrnRmks' => $payout->remarks ?? 'NA',
                'type'            => $payout->type ?? '1',
                'name'            => $payout->account_holder,
                'mobile'          => (int) $payout->mobile,
                'ifsc'            => $payout->ifsc_code,
                'bankname'        => $payout->bank_name,
                'branchname'      => $payout->branch_name,
                'beneaddress'     => $payout->beneficiary_address ?? 'NA',
                'transferId'      => $payout->transaction_id,
            ];
        })->toArray();

        // SprintNXT bulk payout API call
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
        Log::channel('payout')->info('Initiating payout with payload: ' . json_encode($payload));

        // Call SprintNXT API
        $response = $this->requestService->post('payout/PAYOUT', $payload);

        $isAuthError = ($response['status'] === false && $response['responsecode'] === 4 && isset($response['message']) && str_contains(strtolower($response['message']), 'unauthorized request'));
        if($isAuthError) {
            Cache::forget('sprintnxt_auth_token');
            Log::channel('payout')->info("PayoutService createSinglePayout received auth error from gateway. Cleared cached token.");
            $response = $this->requestService->post('payout/PAYOUT', $payload);
        }
        
        $isSuccess = ($response['status'] === true || $response['responsecode'] === 1);

        if ($isSuccess && $txnStatus !== null) {
            $accepted = $response['data']['transaction']['accept'] ?? [];
            $rejected = $response['data']['transaction']['reject'] ?? [];

            $batchPayout->update([
                'raw_payload'         => $response,
                'system_batch_id'     => $response['data']['bulk_system_batch_id'],
                'status'              => 'success',
                'tracker_id'          => $response['tracker_id'] ?? '',                
                'accepted_count'      => count($accepted),
                'rejected_count'      => count($rejected),
            ]);

            Log::channel('payout')->info("BatchPayout ID {$this->batchId} processed successfully. Response: " . json_encode($response));
            
        } else {      
            $batchPayout->update([
                'raw_payload'         => $response,
                'status'              => 'failed',
            ]);
            
            Log::channel('payout')->error("BatchPayout ID {$this->batchId} failed. Response: " . json_encode($response));
        }
    }
}
