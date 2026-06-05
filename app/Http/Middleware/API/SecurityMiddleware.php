<?php

namespace App\Http\Middleware\API;

use App\Helpers\API\SecurityHelper;
use App\Models\APIActivationRequest;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $apiSecret = $request->header('X-API-SECRET');

        if (!$apiKey || !$apiSecret) {
            return $this->jsonError('API key and secret are required', 400);
        }

        $securityHelper = new SecurityHelper($apiSecret);

        $user = User::where('api_key', $apiKey)->first();
        
        if (!$user || $user->api_secret !== $apiSecret) {
            return $this->encryptedError($securityHelper, 'Invalid API key or secret', 401);
        }

        if ($user->status !== 'active') {
            return $this->encryptedError($securityHelper, 'Merchant account is not active.', 403);
        }

        if ($user->kyc_status !== 'verified') {
            return $this->encryptedError($securityHelper, 'Merchant KYC is not verified.', 403);
        }

        if ($user->van_status !== 'verified') {
            return $this->encryptedError($securityHelper, 'Merchant virtual account is not verified.', 403);
        }

        $activation = APIActivationRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'verified')
            ->latest('id')
            ->first();

        if (!$activation) {
            return $this->encryptedError($securityHelper, 'Merchant API activation is not verified.', 403);
        }

        if (blank($activation->webhook_url) || blank($activation->webhook_secret)) {
            return $this->encryptedError($securityHelper, 'Merchant webhook configuration is incomplete.', 403);
        }

        $requestIp = (string) $request->ip();
        \Log::info("Request IP: {$requestIp}, Allowed IP: {$activation->ip}");
        if ((string) $activation->ip !== $requestIp) {
            return $this->encryptedError($securityHelper, 'Request IP is not whitelisted for this merchant.', 403);
        }

        $request->attributes->set('merchant_user', $user);
        $request->attributes->set('api_security_helper', $securityHelper);
        $request->attributes->set('merchant_api_activation', $activation);

        return $next($request);
    }

    private function jsonError(string $message, int $statusCode, array $errors = []): Response
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $statusCode);
    }

    private function encryptedError(SecurityHelper $securityHelper, string $message, int $statusCode, array $errors = []): Response
    {
        $json = [
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ];

        return response($securityHelper->encrypt(json_encode($json)), 200);
    }
}
