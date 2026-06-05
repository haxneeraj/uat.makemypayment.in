<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Traits\StatusResolverTrait;
use App\Traits\PayloadTrait;
use App\Services\EncryptionService;
use App\Services\AuthTokenGenerator;


class RequestService
{
    use StatusResolverTrait;
    use PayloadTrait;


    public function post(string $endpoint, array $data): array
    {
        try {
            // Step 1: get auth token (handles caching and refreshing)
            $token = AuthTokenGenerator::getToken();

            // Step 2: prepare encrypted payload
            $encryptedRequest = $this->preparePayload($data, $token);

            // Step 3: make request (key header carries the encrypted AES key)
            $response = Http::timeout(120)
            ->withHeaders([
                'User-Agent' => 'NXT728453-Snxt-Payments',
                'client-id' => env('SPRINTNXT_CLIENT_ID'),
                'partnerId' => env('SPRINTNXT_PARTNER_ID'),
                'key'       => $encryptedRequest['body']['key'],
            ])
            ->withBody(json_encode($encryptedRequest, JSON_UNESCAPED_SLASHES), 'application/json')
            ->post(config('sprintnxt-endpoints.base_url') . $endpoint);

            $responseData = $response->json();
            Log::channel('payout')->info("RequestService [{$endpoint}] raw response: " . json_encode($responseData));

            // SprintNXT wraps all responses (success & error) in {"body":"encrypted","code":200}
            // Process whenever the encrypted body field is present, regardless of HTTP status
            if (isset($responseData['body'])) {
                // key comes from response header
                $responseHeaders = $response->headers();
                $responseKey     = $responseHeaders['key'][0] ?? null;

                if (!$responseKey) {
                    Log::channel('payout')->error("RequestService [{$endpoint}] missing key header. Headers: " . json_encode($responseHeaders));
                    throw new Exception("Decryption key missing from response headers");
                }

                // decrypt the response body and return full array
                $decryptedData = EncryptionService::decData($responseData['body'], $responseKey);
                Log::channel('payout')->info("RequestService [{$endpoint}] decrypted: " . json_encode($decryptedData));                

                return $decryptedData;
            }

            Log::channel('payout')->error("RequestService post [{$endpoint}] error: HTTP {$response->status()} — " . $response->body());
            throw new Exception("Unexpected response (HTTP {$response->status()}): " . $response->body());

        } 
        catch (Exception $e) 
        {
            Log::channel('payout')->error("RequestService post [{$endpoint}] error: " . $e->getMessage());
            throw new Exception("Failed to post request: " . $e->getMessage());
        }
    }

    public static function buildRequest(array $data): array
    {
        return [
            'partnerId' => env('SPRINTNXT_PARTNER_ID'),
            'clientId'  => env('SPRINTNXT_CLIENT_ID'),
            'timestamp' => time(),
            'data'      => EncryptionService::encData($data),
        ];
    }
}
