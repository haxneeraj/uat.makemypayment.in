<?php

namespace App\Listeners;

use App\Events\KycRejected;
use App\Jobs\Mails\SendKycRejectedMailJob;

class SendKycRejectedMail
{
    public function handle(KycRejected $event): void
    {
        SendKycRejectedMailJob::dispatch($event->user, $event->remark);
    }
}
