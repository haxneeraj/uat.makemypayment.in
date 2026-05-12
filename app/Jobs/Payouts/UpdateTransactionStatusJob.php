<?php

namespace App\Jobs\Payouts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\Payout;
use App\Services\BankServices\CastlerService;

class UpdateTransactionStatusJob implements ShouldQueue
{
    use Queueable;

    protected $transactionId;

    /**
     * Create a new job instance.
     */
    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # Get Payout Record
        $payout = Payout::where('transaction_id', $this->transactionId)->first();

        if(!$payout)
        {
            return;
        }

        # Get Transfer Detail
        $transferDetail = app(CastlerService::class)->getTransferDetail($payout->transaction_id);

        if(!$transferDetail)
        {
            return;
        }

        $transferDetail = json_decode($transferDetail, true);

        # Check if transfer is processed
        if($transferDetail['result'])
        {
            # Update Payout Record
            $payout->update([
                'status' => $transferDetail['result']['status'],
                'utr' => $transferDetail['result']['utr']??null,
                'remarks' => $transferDetail['result']['remarks'],
            ]);
        }
    }
}
