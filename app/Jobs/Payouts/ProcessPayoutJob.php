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

    protected RequestService $requestService;

    protected $payoutId;

    public function __construct($payoutId)
    {
        $this->onQueue('process-payout');
        $this->payoutId = $payoutId;
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
        $payout = Payout::find($this->payoutId);

        if (!$payout) {
            Log::channel('payout')->error("Payout with ID {$this->payoutId} not found.");
            return;
        }

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
            'transferId'     => $payout->transaction_id,
            'type'           => $payout->type ?? '1',
        ];

        // Log Payload with masked account numbers
        Log::channel('payout')->info('Initiating payout with payload: ' . json_encode($payload));

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

        $isAuthError = ($response['status'] === false && $response['responsecode'] === 4 && isset($response['message']) && str_contains(strtolower($response['message']), 'unauthorized request'));
        if($isAuthError) {
            Cache::forget('sprintnxt_auth_token');
            Log::channel('payout')->info("PayoutService createSinglePayout received auth error from gateway. Cleared cached token.");
            $response = $this->requestService->post('payout/PAYOUT', $payload);
        }

        // API returns: status (bool), responsecode (int), data.txn_status (int), data.utr (string)
        $utr            = $response['data']['utr'] ?? null;
        $txnStatus      = $response['data']['txn_status'] ?? null;
        $sprintnxtTxnId = $response['data']['sprintnxt_txn_id'] ?? null;
        $sprintnxtLoggerId = $response['data']['logger_id'] ?? null;
        $isSuccess      = ($response['status'] === true || $response['responsecode'] === 1);            
        

        if ($isSuccess && $txnStatus !== null) {
            $dbStatus = $this->mapTxnStatus((int) $txnStatus, $utr ?: null);
            $payout->update([
                'status'           => $dbStatus,
                'utr'              => $utr ?: null,
                'sprintnxt_txn_id' => $sprintnxtTxnId,
                'sprintnxt_logger_id' => $sprintnxtLoggerId,
                'txn_status'       => $txnStatus,
                'processed_at'     => now(),
                'raw_payload'      => $response,
            ]);
            
        } else {
            $payout->update([
                'status'              => 'failed',
                'sprintnxt_logger_id' => $sprintnxtLoggerId,
                'processed_at'        => now(),
                'raw_payload'         => $response,
            ]);
            Log::channel('payout')->error("Payout ID {$payout->id} failed. Response: " . json_encode($response));
        }
    }
}
