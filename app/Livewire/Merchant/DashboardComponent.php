<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use App\Models\Payout;
use App\Models\Deposit;
use Carbon\Carbon;

class DashboardComponent extends Component
{
    public function render()
    {
        $user = auth()->user();
        $userId = $user->id;

        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Single Optimized Stats Query
        |--------------------------------------------------------------------------
        */

        $stats = Payout::query()
            ->where('user_id', $userId)
            ->selectRaw("
                /*
                |--------------------------------------------------------------------------
                | Status Wise Amount
                |--------------------------------------------------------------------------
                */

                SUM(CASE WHEN status = 'initiated' THEN total_amount ELSE 0 END) as initiated_amount,

                SUM(CASE WHEN status = 'success' THEN total_amount ELSE 0 END) as success_amount,

                SUM(CASE WHEN status = 'pending' THEN total_amount ELSE 0 END) as pending_amount,

                SUM(CASE WHEN status = 'failed' THEN total_amount ELSE 0 END) as failed_amount,

                SUM(CASE WHEN status = 'processed' THEN total_amount ELSE 0 END) as processed_amount,

                SUM(CASE WHEN status = 'send_to_bank' THEN total_amount ELSE 0 END) as send_to_bank_amount,

                /*
                |--------------------------------------------------------------------------
                | Today Stats
                |--------------------------------------------------------------------------
                */

                SUM(
                    CASE 
                        WHEN created_at BETWEEN ? AND ? 
                        THEN 1 
                        ELSE 0 
                    END
                ) as today_count,

                SUM(
                    CASE 
                        WHEN created_at BETWEEN ? AND ? 
                        THEN total_amount 
                        ELSE 0 
                    END
                ) as today_amount,

                /*
                |--------------------------------------------------------------------------
                | Total Successful Stats
                |--------------------------------------------------------------------------
                */

                SUM(
                    CASE 
                        WHEN status != 'failed' 
                        THEN 1 
                        ELSE 0 
                    END
                ) as total_transaction_count,

                SUM(
                    CASE 
                        WHEN status != 'failed' 
                        THEN total_amount 
                        ELSE 0 
                    END
                ) as total_payout_amount
            ", [
                $todayStart,
                $todayEnd,
                $todayStart,
                $todayEnd,
            ])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent Payouts
        |--------------------------------------------------------------------------
        */

        $recentPayouts = Payout::query()
            ->where('user_id', $userId)
            ->latest('id')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Inward Funds
        |--------------------------------------------------------------------------
        */

        $inwardFunds = Deposit::query()
            ->where('user_id', $userId)
            ->latest('id')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Transaction Status
        |--------------------------------------------------------------------------
        */

        $transactionStatus = [
            'initiated'   => (float) $stats->initiated_amount,
            'success'     => (float) $stats->success_amount,
            'pending'     => (float) $stats->pending_amount,
            'failed'      => (float) $stats->failed_amount,
            'processed'   => (float) $stats->processed_amount,
            'send_to_bank'=> (float) $stats->send_to_bank_amount,
        ];

        $transactionStatus['total'] = array_sum($transactionStatus);

        return view('merchant.dashboard-component', [
            'user' => $user,

            'transactionStatus' => $transactionStatus,

            'todayStats' => [
                'count'  => (int) $stats->today_count,
                'amount' => (float) $stats->today_amount,
            ],

            'totalPayout' => (float) $stats->total_payout_amount,

            'totalTransactionCount' => (int) $stats->total_transaction_count,

            'recentPayouts' => $recentPayouts,

            'inwardFunds' => $inwardFunds,
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'dashboard',
            'pageTitle' => 'Dashboard',
            'metaTitle' => 'Dashboard - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Dashboard for M.M.P Fintech Payment Solution',
        ]);
    }
}
