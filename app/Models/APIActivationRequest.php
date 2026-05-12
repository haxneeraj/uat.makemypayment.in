<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APIActivationRequest extends Model
{
    protected $table = "a_p_i_activation_requests";

    protected $fillable = [
        'user_id',
        'ip',
        'webhook_url',
        'webhook_secret',
        'remark',
        'status',
    ];

    /**
     * Relation with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
