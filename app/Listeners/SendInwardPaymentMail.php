<?php

namespace App\Listeners;

use App\Events\InwardPaymentReceived;
use App\Jobs\Mails\SendInwardPaymentMailJob;

class SendInwardPaymentMail
{
    public function handle(InwardPaymentReceived $event): void
    {
        SendInwardPaymentMailJob::dispatch($event->deposit);
    }
}
