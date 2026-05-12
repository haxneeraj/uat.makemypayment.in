<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $remark)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KYC Update - Action Required for Re-Submission',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reject-onboard',
            with: [
                'user' => $this->user,
                'remark' => $this->remark,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
