<?php
namespace App\Traits;

trait ResponseTrait
{
    public function successResponse($data, $message = 'Success', $code = 200): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    public function errorResponse($message = 'Error', $code = 400, $data = null): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];
    }
}