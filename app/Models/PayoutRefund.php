<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRefund extends Model
{
    protected $fillable = [
        'user_id',
        'payout_id',
        'deposit_id',
        'amount',
        'process_date',
        'status',
        'remarks',
        'processed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'process_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
