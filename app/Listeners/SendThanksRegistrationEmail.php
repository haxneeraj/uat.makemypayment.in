<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\Mails\SendThanksRegistrationMailJob;

class SendThanksRegistrationEmail
{
    public function handle(UserRegistered $event): void
    {
        SendThanksRegistrationMailJob::dispatch($event->user);
    }
}
