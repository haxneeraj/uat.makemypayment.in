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
        'document_type',
        'is_primary',
        'remarks',
        'document',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
