<?php
namespace App\Traits;

use App\Services\EncryptionService;

trait PayloadTrait
{
    public function preparePayload(array $data, string $clientAssertion): array
    {
        $encryptedRequest = EncryptionService::encData($data);

        return [
            'body' => [
                'payload'      => $encryptedRequest['payload'],
                'key'          => $encryptedRequest['key'],
                'partnerId'    => env('SPRINTNXT_PARTNER_ID'),
                'clientid'     => env('SPRINTNXT_CLIENT_ID'),
                'access_token' => $clientAssertion,
            ]
        ];
    }
}
