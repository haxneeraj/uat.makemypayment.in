<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Http;
use Exception;

use App\Traits\StatusResolverTrait;
use App\Traits\PayloadTrait;

use App\Services\EncryptionService;


class AuthTokenGenerator
{
    use PayloadTrait;
    public static function getToken(): string
    {
        // Step 1: check cache first
        $token = Cache::get('sprintnxt_auth_token');

        if ($token) {
            return $token;
        }

        // Step 2: acquire lock (wait max 10 sec)
        return Cache::lock('auth_token_lock', 10)->block(10, function () {

            // Step 3: double check (important!)
            $token = Cache::get('sprintnxt_auth_token');
            if ($token) {
                return $token;
            }

            // Step 4: generate new token
            $newToken = self::generateTokenFromBank();

            // Step 5: store with buffer (expire before actual expiry)
            Cache::put('sprintnxt_auth_token', $newToken, now()->addMinutes(29));
            \Log::info("AuthTokenGenerator generated new token and cached it.");

            return $newToken;
        });
    }

    private static function generateTokenFromBank(): string
    {
        $clientAssertion = bin2hex(random_bytes(16));

        $requestData = [
            'grant_type' => 'paymenttxn-fundTransfer',
            'client_assertion' => $clientAssertion,
        ];

        \Log::info([
            'requestData' => $requestData,
        ]);

        $encryptedRequest = (new self)->preparePayload($requestData, $clientAssertion);

        \Log::info([
            'encryptedRequest' => $encryptedRequest,
        ]);

        $response = Http::timeout(120)
        ->withHeaders([            
            'User-Agent' => 'NXT728453-Snxt-Payments',
            'client-id' => env('SPRINTNXT_CLIENT_ID'),
            'partnerId' => env('SPRINTNXT_PARTNER_ID'),
            'key'       => $encryptedRequest['body']['key'],
            'accept' => 'application/json',
        ])
        ->withBody(json_encode($encryptedRequest, JSON_UNESCAPED_SLASHES), 'application/json')
        ->post(config('sprintnxt-endpoints.base_url') . config('sprintnxt-endpoints.authorization'));

        if (!$response->successful()) {
            throw new Exception("Token API failed: " . $response->body());
        }

        \Log::info("AuthTokenGenerator returning client assertion as token.");
        return $clientAssertion;
    }
}