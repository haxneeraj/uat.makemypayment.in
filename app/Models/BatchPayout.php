<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchPayout extends Model
{
    protected $fillable = [
        'user_id',
        'batch_id',
        'system_batch_id',
        'batch_count',
        'batch_amount',
        'accepted_count',
        'rejected_count',
        'tracker_id',
    ];

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'batch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
