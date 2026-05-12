<?php

namespace App\Services;

use App\Services\RequestService;

use App\Dto\SinglePayoutDTO;

use App\Models\Payout;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class AccountService
{
    protected RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * Map SprintNXT txn_status code to our DB status.
     * txn_status: 0=Initiated, 1=Confirm Success, 2=Pending, 3=Processed, 4=Confirm Failure, 6=Send to Bank
     * Note: txn_status 1 with blank UTR = pending (not yet settled)
     */
    private function mapTxnStatus(int $txnStatus, ?string $utr): string
    {
        return match ($txnStatus) {
            0       => 'initiated',
            1       => ($utr ? 'success' : 'pending'),
            2       => 'pending',
            3       => 'processed',
            4       => 'failed',
            6       => 'send_to_bank',
            default => 'pending',
        };
    }

    /**
     * Initiate a single payout via SprintNXT and persist the record.
     * Returns the local transaction_id on success.
     */
    public function getAccountBalance()
    {
        $payload = [
                'apiId'          => '30011',
                'bankId'         => config('sprintnxt-endpoints.bank_id'),
                'transferId'     => $transactionId,
                'sprintnxtTxnId' => null,
            ];

            $response = $this->requestService->post('payout/PAYOUT', $payload);
    }
}
