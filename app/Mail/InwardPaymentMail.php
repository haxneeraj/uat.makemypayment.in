<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InwardPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Deposit $deposit)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Inward Payment Received - Wallet Credited',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inward-mail',
            with: [
                'deposit' => $this->deposit,
                'user' => $this->deposit->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
