<?php

namespace App\Helpers\API;

class SecurityHelper
{
    protected $secret_key;
    protected $cipher_method = 'AES-256-CBC';
    protected $iv = '0g7H#8X2mTqjvLwR';

    public function __construct(string $api_secret)
    {
        $this->secret_key = $api_secret;
    }

    public function encrypt(string $data): string
    {
        $encrypted = openssl_encrypt($data, $this->cipher_method, $this->secret_key, OPENSSL_RAW_DATA, $this->iv);
        return base64_encode($encrypted);
    }

    public function decrypt(string $data): string
    {
        $decoded = base64_decode($data);
        return openssl_decrypt($decoded, $this->cipher_method, $this->secret_key, OPENSSL_RAW_DATA, $this->iv);
    }
}