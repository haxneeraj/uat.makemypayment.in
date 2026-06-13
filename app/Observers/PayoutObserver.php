<?php

namespace App\Observers;

use App\Models\Payout;

use App\Jobs\WebhookRunner\SendPayoutWebhookJob;

class PayoutObserver
{
    /**
     * Handle the Payout "created" event.
     */
    public function created(Payout $payout): void
    {
        //
    }

    /**
     * Handle the Payout "updated" event.
     */
    public function updated(Payout $payout): void
    {
        // Only if status change then dispatch webhook
        if ($payout->isDirty('status')) {
            // Dispatch webhook to notify user about payout status change
            SendPayoutWebhookJob::dispatch(
                $payout->user_id,
                [
                    'transaction_id' => $payout->transaction_id,
                    'merchant_reference_id' => $payout->merchant_reference_id,
                    'beneficiary_account_holder' => $payout->payee?->account_holder,
                    'beneficiary_account_number' => $payout->payee?->account_number,
                    'beneficiary_bank_name' => $payout->payee?->bank_name,
                    'beneficiary_ifsc_code' => $payout->payee?->ifsc_code,
                    'amount' => $payout->amount,
                    'status' => $payout->status,
                    'utr' => $payout->utr ?? null,
                    'remarks' => $payout->remarks,
                    'narration' => $payout->narration,
                ]
            )
            ->afterCommit()
            ->onQueue('webhooks-runner');
        }
    }

    /**
     * Handle the Payout "deleted" event.
     */
    public function deleted(Payout $payout): void
    {
        //
    }

    /**
     * Handle the Payout "restored" event.
     */
    public function restored(Payout $payout): void
    {
        //
    }

    /**
     * Handle the Payout "force deleted" event.
     */
    public function forceDeleted(Payout $payout): void
    {
        //
    }
}
