<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantDocument extends Model
{
    protected $table = "merchant_documents";

    protected $fillable = [
        'user_id',
        'merchant_virtual_account_id',
        'document_id',
        'name',
        'doc_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchantVirtualAccount()
    {
        return $this->belongsTo(MerchantVirtualAccount::class);
    }
}
