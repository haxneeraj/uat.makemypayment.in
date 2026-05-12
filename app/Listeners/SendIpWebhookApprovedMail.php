<?php

namespace App\Listeners;

use App\Events\IpWebhookApproved;
use App\Jobs\Mails\SendIpWebhookApprovedMailJob;

class SendIpWebhookApprovedMail
{
    public function handle(IpWebhookApproved $event): void
    {
        SendIpWebhookApprovedMailJob::dispatch($event->request);
    }
}
