<?php

namespace App\Events;

use App\Models\APIActivationRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IpWebhookApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public APIActivationRequest $request)
    {
    }
}
