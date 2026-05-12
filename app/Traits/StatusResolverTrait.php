<?php

namespace App\Traits;

trait StatusResolverTrait
{
    public function resolveStatus(int|string $statusCode): string
    {
        $statusMap = [
            0 => 'Initiated',
            1 => 'Confirm Success',
            2 => 'Pending',
            3 => 'Processed',
            4 => 'Confirm Failure',
            6 => 'Send to Bank',
        ];

        return $statusMap[(int) $statusCode] ?? 'Unknown';
    }
}