<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payout extends Model
{
    protected $fillable = [
        'user_id',
        'batch_id',

        'transaction_id',
        'sprintnxt_txn_id',
        'sprintnxt_logger_id',
        'utr',
        'txn_status',
        'initiated_at',
        'processed_at',

        'account_holder',
        'account_number',
        'ifsc_code',
        'branch_code',
        'bank_name',
        'branch_name',
        'mobile',
        'email',
        'city',
        'state',
        'pincode',

        'beneficiary_address',

        'amount',
        'fee',
        'total_amount',

        'mode',
        'status',
        'remarks',
        'purpose',
        'narration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(BatchPayout::class, 'batch_id');
    }

    public static function getTodayStats()
    {
        $today = Carbon::today();
        return self::whereDate('created_at', $today)
        ->where('status', 'success')
        ->sum('amount');
    }

    public static function getSuccessRate()
    {
        $total = self::count();
        if ($total === 0) return 0;
        
        $success = self::where('status', 'success')->count();
        return round(($success / $total) * 100, 1);
    }

    public function scopeFilter($query, $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) use ($filters) {
            $searchColumn = $filters['searchColumn'] ?? 'account_holder';
            return $query->where($searchColumn, 'like', '%' . $search . '%');
        })->when($filters['dateFrom'] ?? null, function ($query, $dateFrom) use ($filters) {
            $dateTo = $filters['dateTo'] ?? now()->toDateString();
            return $query->whereDate('initiated_at', '>=', $dateFrom)
            ->whereDate('initiated_at', '<=', $dateTo);
        })->when(!blank($filters['status'] ?? null), function ($query) use ($filters) {
            return $query->where('status', $filters['status']);
        });
    }
}
