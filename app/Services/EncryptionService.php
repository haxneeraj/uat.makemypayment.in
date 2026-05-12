<?php

namespace App\Services;

use RuntimeException;

class EncryptionService
{
    /**
     * Builds the full SprintNxt encrypted request packet under body node.
     */
    public static function encData(array $data): array
    {
        // Step 1: random AES key
        $key = openssl_random_pseudo_bytes(32);

        // Step 2: encrypt data
        $encryptedData = openssl_encrypt(
            json_encode($data),
            'AES-256-ECB',
            $key,
            OPENSSL_RAW_DATA
        );

        // Step 3: encrypt AES key with BANK public key
        $bankPublicKey = file_get_contents(storage_path('app/paysprint_UAT_public-key.pem'));

        openssl_public_encrypt($key, $encryptedKey, $bankPublicKey);

        return [
            'payload' => base64_encode($encryptedData),
            'key'     => base64_encode($encryptedKey)
        ];
    }

    /**
     * Decrypts an RSA+AES encrypted response from SprintNxt.
     */
    public static function decData(string $payload, string $encryptedKey): array
    {
        // Step 1: load your PRIVATE key
        $privateKey = file_get_contents(storage_path('app/partner_private.key'));

        if ($privateKey === false) {
            throw new RuntimeException('Failed to load live partner private key.');
        }

        // Step 2: decrypt AES key using RSA private key
        $result = openssl_private_decrypt(
            base64_decode($encryptedKey),
            $decryptedKey,
            $privateKey
        );

        if ($result === false) {
            throw new RuntimeException('RSA decryption of AES key failed: ' . openssl_error_string());
        }

        // Step 3: decrypt payload using AES-256-ECB
        $decryptedData = openssl_decrypt(
            base64_decode($payload),
            'AES-256-ECB',
            $decryptedKey,
            OPENSSL_RAW_DATA
        );

        if ($decryptedData === false) {
            throw new RuntimeException('AES decryption of payload failed: ' . openssl_error_string());
        }

        $decoded = json_decode($decryptedData, true);

        // Server may double-encode the payload (JSON string containing a JSON object)
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        if (!is_array($decoded)) {
            throw new RuntimeException('Decrypted payload is not a valid JSON object. Raw: ' . $decryptedData);
        }

        return $decoded;
    }
}