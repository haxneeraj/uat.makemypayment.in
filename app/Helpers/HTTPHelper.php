<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class HTTPHelper extends AESHelper
{
    protected $apiKey;
    protected $xApiKey;

    public function __construct()
    {
        $this->apiKey = config('castler.API_KEY');
        $this->xApiKey = config('castler.X_API_KEY');
        parent::__construct();
    }

    protected function header($header = []):array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'x-api-key' => $this->xApiKey,
            'x-client-key' => $this->apiKey,
            ...$header
        ];
    }

    public function request(string $type, string $endpoint, array $data)
    {
        # Normalize request type
        $method = strtolower($type);

        # Encrypted Request Data
        $encrypted_data = $this->encrypt(json_encode($data));

        # Request
        $request = HTTP::withHeaders($this->header())->withBody($encrypted_data);

        # Request Method and get response
        $response = match($method)
        {
            'get' => $request->get($endpoint),
            'post' => $request->post($endpoint),

            default => $request->get($endpoint),
        };

        \Log::info([
            'Response' => $response->body()
        ]);

        # Decrypt Response
        return $this->decrypt($response);

    }

    public function get()
    {
        
    }

    public function post(string $endpoint, array $data)
    {
        # Encrypted Request Data
        $encrypted_data = $this->encrypt(json_encode($data));

        # Request
        $response = HTTP::withHeaders($this->header())->withBody($encrypted_data)->post($endpoint);
        
    }
}