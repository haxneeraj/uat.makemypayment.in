<?php

namespace App\Jobs\Payouts;

use App\Models\MerchantVirtualAccount;
use App\Models\PayoutRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessPayoutRefundChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    /**
     * @param array<int> $refundIds
     */
    public function __construct(public array $refundIds)
    {
    }

    public function handle(): void
    {
        PayoutRefund::query()
            ->whereIn('id', $this->refundIds)
            ->orderBy('id')
            ->chunkById(100, function ($refunds): void {
                foreach ($refunds as $refund) {
                    try {
                        DB::transaction(function () use ($refund): void {
                            $lockedRefund = PayoutRefund::where('id', $refund->id)
                                ->lockForUpdate()
                                ->first();

                            if (!$lockedRefund || $lockedRefund->status !== 'pending') {
                                return;
                            }

                            $wallet = MerchantVirtualAccount::where('user_id', $lockedRefund->user_id)
                                ->lockForUpdate()
                                ->first();

                            if (!$wallet) {
                                throw new \RuntimeException('Virtual account not found for user_id ' . $lockedRefund->user_id);
                            }

                            $wallet->increment('balance', (float) $lockedRefund->amount);

                            $lockedRefund->update([
                                'status' => 'processed',
                                'processed_at' => now(),
                            ]);
                        });

                        Log::info('Payout refund processed.', [
                            'refund_id' => $refund->id,
                            'user_id' => $refund->user_id,
                            'amount' => $refund->amount,
                        ]);
                    } catch (Throwable $e) {
                        $errorRemarks = trim((string) ($refund->remarks ?: ''));
                        $errorRemarks = $errorRemarks !== ''
                            ? $errorRemarks . ' | Processing error: ' . $e->getMessage()
                            : 'Processing error: ' . $e->getMessage();

                        PayoutRefund::where('id', $refund->id)->update([
                            'status' => 'failed',
                            'remarks' => $errorRemarks,
                        ]);

                        Log::error('Payout refund processing failed.', [
                            'refund_id' => $refund->id,
                            'user_id' => $refund->user_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }, column: 'id');
    }
}
