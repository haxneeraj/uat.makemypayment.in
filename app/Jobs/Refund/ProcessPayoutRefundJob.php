<?php

namespace App\Jobs\Refund;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Payout;
use App\Models\PayoutRefund;
use App\Models\Deposit;
use App\Models\MerchantVirtualAccount;

class ProcessPayoutRefundJob implements ShouldQueue
{
    use Queueable;

    protected $payoutId;

    /**
     * Create a new job instance.
     */
    public function __construct($payoutId)
    {
        $this->onQueue('process-payout-refund');
        $this->payoutId = $payoutId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {

            // 1. Lock payout
            $payout = Payout::where('id', $this->payoutId)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Idempotency check (extra safety)
            $existing = PayoutRefund::where('payout_id', $this->payoutId)->first();

            if ($existing) {
                Log::channel('refund')->warning('Refund already exists', [
                    'payout_id' => $this->payoutId,
                    'payout' => $payout,
                    'refund' => $existing
                ]);
                return;
            }
            \Log::channel('refund')->info('Processing refund', [
                'merchant virtual account' => MerchantVirtualAccount::where('user_id', $payout->user_id)->first(),
            ]);

            // 3. Lock VAN
            $van = MerchantVirtualAccount::where('user_id', $payout->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            // 4. Create refund (safe due to unique constraint) & Deposit association (BEST PRACTICE)
            // Deposit
            $deposit = Deposit::create([
                'user_id' => $payout->user_id,
                'alert_sequence_no' => $payout->transaction_id,
                'remitter_name' => 'System Refund',
                'remitter_account' => 'SYSTEM',
                'remitter_bank' => 'SYSTEM',
                'user_reference_number' => $payout->transaction_id,
                'virtual_account' => $van->van,
                'account_number' => $van->van,
                'amount' => $payout->total_amount,
                'mnemonic_code' => 'REFUND',
                'transaction_date' => now(),
                'value_date' => now()->toDateString(),
                'ifsc_code' => $van->ifsc,
                'cheque_no' => null,
                'transaction_description' => 'Deposit created for payout refund. Transaction ID: ' . $payout->transaction_id,
                'debit_credit' => 'credit',
                'raw_payload' => null,
                'processing_status' => 'success',
            ]);

            $refund = PayoutRefund::create([
                'user_id' => $payout->user_id,
                'deposit_id' => $deposit->id,
                'payout_id' => $payout->id,
                'amount' => $payout->total_amount,
                'process_date' => now(),
                'status' => 'processed',
                'remarks' => 'Refund processed for failed payout',
            ]);

            


            // 5. Update payout
            $payout->update([
                'remarks' => 'Refund completed'
            ]);

            // 6. Atomic balance update (BEST PRACTICE)
            $van->increment('balance', $refund->amount);

            Log::channel('refund')->info('Refund successful', [
                'payout' => $payout,
                'refund' => $refund,
                'deposit' => $deposit,
            ]);

        });
    }
}
