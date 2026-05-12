<?php

namespace App\Jobs\Mails;

use App\Mail\WelcomeOnboardMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendKycApprovalMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
        $this->onQueue('onboarding-mail');
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new WelcomeOnboardMail($this->user));
    }
}
