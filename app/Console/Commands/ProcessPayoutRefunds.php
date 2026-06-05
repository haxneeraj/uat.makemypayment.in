<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payout;
use Illuminate\Support\Facades\Log;
use App\Jobs\Refund\ProcessPayoutRefundJob;

class ProcessPayoutRefunds extends Command
{
    protected $signature   = 'payout:process-refunds';
    protected $description = 'Dispatch refund processing job for today\'s pending payout refunds.';

    public function handle(): int
    {
        $date = now()->subDay()->toDateString();

        $payouts = Payout::query()
        ->whereDate('created_at', $date)
        ->where('status', 'failed')
        ->where(function ($query) {
            $query->whereDoesntHave('refund')
            ->orWhereHas('refund', function ($refundQuery) {
                $refundQuery->where('status', 'failed');
            });
        })
        ->get();

        foreach ($payouts as $payout) {
            Log::channel('refund')->info('Dispatching refund job', [
                'payout' => $payout
            ]);
            ProcessPayoutRefundJob::dispatch($payout->id);
        }
        if($payouts->isEmpty()) {
            Log::channel('refund')->info('No payouts found for refund processing for date: ' . $date);
        }

        return self::SUCCESS;
    }
}
