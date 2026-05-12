<?php

namespace App\Services;

use App\Interfaces\EmailServiceInterface;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{
    public function sendMail($mailer, $email)
    {
        Mail::to($email)->send($mailer);
    }
}