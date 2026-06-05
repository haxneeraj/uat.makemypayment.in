<?php
namespace App\Traits;

trait LogTrait
{
    protected function logError(\Exception $e)
    {
        \Log::error([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    protected function logInfo($message, $context = [])
    {
        \Log::info($message, $context);
    }
}
