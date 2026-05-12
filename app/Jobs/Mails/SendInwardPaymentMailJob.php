<?php

namespace App\Jobs\Mails;

use App\Mail\InwardPaymentMail;
use App\Models\Deposit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInwardPaymentMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Deposit $deposit)
    {
        $this->onQueue('inward-mail');
    }

    public function handle(): void
    {
        $deposit = $this->deposit->fresh(['user']);

        if (! $deposit || ! $deposit->user || ! $deposit->user->email) {
            return;
        }

        Mail::to($deposit->user->email)->send(new InwardPaymentMail($deposit));
    }
}
