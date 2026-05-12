<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id',
        'alert_sequence_no',
        'remitter_name',
        'remitter_account',
        'remitter_bank',
        'user_reference_number',
        'virtual_account',
        'amount',
        'mnemonic_code',
        'transaction_date',
        'value_date',
        'ifsc_code',
        'cheque_no',
        'transaction_description',
        'account_number',
        'debit_credit',
        'raw_payload',
        'processing_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'value_date' => 'date',
        'raw_payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}