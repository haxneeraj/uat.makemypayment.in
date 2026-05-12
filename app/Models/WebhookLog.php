<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $table = "webhook_logs";

    protected $fillable = [
        'user_id',
        'url',
        'payload',
        'status_code',
        'response_body',
        'status',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
