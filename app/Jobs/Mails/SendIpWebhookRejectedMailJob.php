<?php

namespace App\Jobs\Mails;

use App\Mail\IpAndWebhookRejectedMail;
use App\Models\APIActivationRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendIpWebhookRejectedMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public APIActivationRequest $request)
    {
        $this->onQueue('ip-webhook-mail');
    }

    public function handle(): void
    {
        $request = $this->request->fresh(['user']);

        if (! $request || ! $request->user || ! $request->user->email) {
            return;
        }

        Mail::to($request->user->email)->send(new IpAndWebhookRejectedMail($request));
    }
}
