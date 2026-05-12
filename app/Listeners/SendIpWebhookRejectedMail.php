<?php

namespace App\Listeners;

use App\Events\IpWebhookRejected;
use App\Jobs\Mails\SendIpWebhookRejectedMailJob;

class SendIpWebhookRejectedMail
{
    public function handle(IpWebhookRejected $event): void
    {
        SendIpWebhookRejectedMailJob::dispatch($event->request);
    }
}
