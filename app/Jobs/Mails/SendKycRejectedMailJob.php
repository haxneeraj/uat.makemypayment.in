<?php

namespace App\Jobs\Mails;

use App\Mail\KycRejectedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendKycRejectedMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user, public string $remark)
    {
        $this->onQueue('onboarding-mail');
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new KycRejectedMail($this->user, $this->remark));
    }
}
