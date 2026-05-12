<?php

namespace App\Services;

use App\Interfaces\SMSServiceInterface;
use Illuminate\Support\Facades\Http;

class SMSService implements SMSServiceInterface
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('fast2sms.API_KEY');
        $this->baseUrl = config('fast2sms.BASEURL');
    }

    protected function header() : array
    {
        return [
            'authorization' => $this->apiKey,
            'accept' => '*/*',
            'cache-control' => 'no-cache',
            'content-type' => 'application/json'
        ];
    }

    public function sendSMS($mobile, $message)
    {
        if(blank($this->apiKey))
        {
            return true;
        }

        # Data
        $data = [
            'authorization' => $this->apiKey,
            "sender_id" => config('fast2sms.SENDER_ID'),
            'message' => 197906,
            "variables_values" => $message,
            "route" => "dlt",
            "numbers" => $mobile,
        ];

        # Endpoint
        $endpoint = $this->baseUrl . config('fast2sms.ENDPOINTS.SEND');

        # Response
        $response = HTTP::withHeaders($this->header())
        ->asJson()
        ->post($endpoint, $data);

        # Check for the success message and return the message
        if($response->successful() && isset($response->json()['return']) && $response->json()['return'] == true)
        {
            return $response->json()['message'];
        }

        # Return false
        return false;
    }
}