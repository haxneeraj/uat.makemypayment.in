<?php

namespace App\Jobs\Mails;

use App\Mail\PayoutMail;
use App\Models\Payout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPayoutMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payout $payout)
    {
        $this->onQueue('payout-mail');
    }

    public function handle(): void
    {
        $payout = $this->payout->fresh(['user']);

        if (! $payout || ! $payout->user || ! $payout->user->email) {
            return;
        }

        Mail::to($payout->user->email)->send(new PayoutMail($payout));
    }
}
