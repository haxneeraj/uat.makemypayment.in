<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Payouts\ProcessPayoutRefundsForDateJob;

class ProcessPayoutRefunds extends Command
{
    protected $signature   = 'payout:process-refunds';
    protected $description = 'Dispatch refund processing job for today\'s pending payout refunds.';

    public function handle(): int
    {
        $date = now()->toDateString();

        ProcessPayoutRefundsForDateJob::dispatch($date)
            ->onQueue('payout-refunds');

        $this->info("Refund processing job dispatched for date: {$date}");

        return self::SUCCESS;
    }
}
