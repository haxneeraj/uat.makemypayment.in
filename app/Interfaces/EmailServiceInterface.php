<?php

namespace App\Interfaces;

interface EmailServiceInterface
{
    public function sendMail($custom_mail, $data);
}