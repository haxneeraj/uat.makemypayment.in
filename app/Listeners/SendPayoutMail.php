<?php

namespace App\Listeners;

use App\Events\PayoutProcessed;
use App\Jobs\Mails\SendPayoutMailJob;

class SendPayoutMail
{
    public function handle(PayoutProcessed $event): void
    {
        SendPayoutMailJob::dispatch($event->payout);
    }
}
