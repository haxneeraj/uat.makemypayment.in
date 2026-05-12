<?php

namespace App\Mail;

use App\Models\MerchantVirtualAccount;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeOnboardMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?MerchantVirtualAccount $van;

    public function __construct(public User $user)
    {
        $this->van = MerchantVirtualAccount::where('user_id', $this->user->id)->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KYC Approved - Welcome Onboard to MakeMyPayment',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-onboard',
            with: [
                'user' => $this->user,
                'van' => $this->van,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
