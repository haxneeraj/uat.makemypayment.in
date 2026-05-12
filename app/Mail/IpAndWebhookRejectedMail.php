<?php

namespace App\Mail;

use App\Models\APIActivationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IpAndWebhookRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public APIActivationRequest $request)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IP & Webhook Request Rejected - Action Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ip-and-webhook-rejected',
            with: [
                'request' => $this->request,
                'user' => $this->request->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
