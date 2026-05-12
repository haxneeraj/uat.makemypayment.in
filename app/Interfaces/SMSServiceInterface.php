<?php

namespace App\Interfaces;

interface SMSServiceInterface
{
    public function sendSMS($mobile, $message);
}