<?php

namespace App\Helpers;

class AESHelper
{
    private string $key;
    private string $iv = "NC0V0$0T0L030RME"; // 16 chars fixed IV
    private string $cipher = "AES-256-CBC";   // AES + CBC mode

    public function __construct()
    {
        $this->key = config('castler.API_SECRET');
    }

    /**
     * Encrypt data using AES/CBC/PKCS5Padding
     */
    public function encrypt(string $plainText): string
    {
        $encrypted = openssl_encrypt(
            $plainText,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        return base64_encode($encrypted);
    }

    /**
     * Decrypt data using AES/CBC/PKCS5Padding
     */
    public function decrypt(string $cipherText): string
    {
        $decoded = base64_decode($cipherText);

        $decrypted = openssl_decrypt(
            $decoded,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        return $decrypted;
    }
}
