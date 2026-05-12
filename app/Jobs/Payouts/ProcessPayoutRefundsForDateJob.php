<?php

namespace App\Jobs\Payouts;

use App\Models\PayoutRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayoutRefundsForDateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public string $processDate)
    {
    }

    public function handle(): void
    {
        $totalPending = PayoutRefund::where('process_date', $this->processDate)
            ->where('status', 'pending')
            ->count();

        if ($totalPending === 0) {
            Log::info('No pending payout refunds found for date.', [
                'process_date' => $this->processDate,
            ]);
            return;
        }

        Log::info('Dispatching payout refund chunk jobs.', [
            'process_date' => $this->processDate,
            'pending_count' => $totalPending,
        ]);

        PayoutRefund::query()
            ->where('process_date', $this->processDate)
            ->where('status', 'pending')
            ->orderBy('id')
            ->chunkById(500, function ($refunds): void {
                $ids = $refunds->pluck('id')->all();

                ProcessPayoutRefundChunkJob::dispatch($ids)
                    ->onQueue('payout-refunds');
            });
    }
}
