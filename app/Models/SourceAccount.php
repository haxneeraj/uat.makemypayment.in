<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceAccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'bank_name',
        'is_primary',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
