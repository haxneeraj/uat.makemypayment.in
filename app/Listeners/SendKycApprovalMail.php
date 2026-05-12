<?php

namespace App\Listeners;

use App\Events\KycApproved;
use App\Jobs\Mails\SendKycApprovalMailJob;

class SendKycApprovalMail
{
    public function handle(KycApproved $event): void
    {
        SendKycApprovalMailJob::dispatch($event->user);
    }
}
