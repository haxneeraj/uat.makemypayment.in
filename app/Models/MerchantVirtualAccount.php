<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantVirtualAccount extends Model
{
    protected $table = "merchant_virtual_accounts";

    protected $fillable = [
        'user_id',
        'van',
        'account_holder',
        'ifsc',
        'purpose',
        'start_date',
        'validity',
        'balance',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(MerchantDocument::class);
    }
}
