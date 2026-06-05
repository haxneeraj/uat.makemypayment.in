<?php

namespace App\Traits;

use Illuminate\Support\Str;

use App\Models\Payout;

trait HasGenerateTransferIdTrait
{
    protected function generateTransferId(): string
    {
        $txId = "TXN_" . strtoupper(Str::random(16));

        while(Payout::where('transaction_id', $txId)->exists()) {
            $txId = "TXN_" . strtoupper(Str::random(16));
        }   

        return $txId;
    }
}
