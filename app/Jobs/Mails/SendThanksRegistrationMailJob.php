<?php

namespace App\Jobs\Mails;

use App\Mail\ThanksRegistrationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendThanksRegistrationMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
        $this->onQueue('registration-mail');
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new ThanksRegistrationMail($this->user));
    }
}
