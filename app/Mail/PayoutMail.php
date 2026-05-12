<?php

namespace App\Mail;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Payout $payout)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payout Update - Transaction Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-mail',
            with: [
                'payout' => $this->payout,
                'user' => $this->payout->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
