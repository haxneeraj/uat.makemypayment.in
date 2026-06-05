<?php

namespace App\Traits;

use App\Models\Payout;

trait HasInitiateRefundTrait
{
    protected function initiateRefund(Payout $payout): void
    {
        // Update payout record to as failed that automatically triggers the refund logic in the payout observer
        $payout->update([
            'status' => 'failed',
            'remarks' => 'Payout failed after processing. Initiating refund to your VAN (Virtual Account Number).',
        ]);
    }
}