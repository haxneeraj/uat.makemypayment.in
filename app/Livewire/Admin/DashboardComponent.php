<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Payout;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardComponent extends Component
{    
    public function getBusinessAnalytics()
    {
        $today = Carbon::today();
        
        // Calculate total volumes
        $totalVolume = Payout::sum('amount');
        $totalTransactions = Payout::count();
        $successTransactions = Payout::where('status', 'success')->count();

        // Calculate success rate
        $successRate = $totalTransactions > 0 
            ? ($successTransactions / $totalTransactions) * 100 
            : 0;

        // Calculate average transaction
        $avgTransaction = $totalTransactions > 0 
            ? $totalVolume / $totalTransactions 
            : 0;

        return [
            'total_merchants' => User::where('role', 'merchant')->count(),
            'active_merchants' => User::where('role', 'merchant')
                ->where('status', 'active')
                ->where('kyc_status', 'verified')
                ->count(),
            'rejected_merchants' => User::where('role', 'merchant')
                ->where(function($query) {
                    $query->where('status', 'suspended')
                        ->orWhere('kyc_status', 'rejected');
                })
                ->count(),
            'waiting_merchants' => User::where('role', 'merchant')
                ->whereIn('kyc_status', ['pending', 'submitted'])
                ->where('status', 'active')
                ->count(),
            'today_registered' => User::where('role', 'merchant')
                ->whereDate('created_at', $today)
                ->count(),
            'total_transactions' => $totalTransactions,
            'total_volume' => $totalVolume,
            'success_rate' => round($successRate, 2),
            'avg_transaction' => $avgTransaction,
            'initiated_payouts' => Payout::where('status', 'initiated')->sum('amount'),
            'success_payouts' => Payout::where('status', 'success')->sum('amount'),
            'total_payouts' => Payout::where('status', 'success')->sum('amount'),
            'pending_payouts' => Payout::where('status', 'pending')->sum('amount'),
            'failed_payouts' => Payout::where('status', 'failed')->sum('amount'),
            'rejected_payouts' => Payout::where('status', 'failed')->sum('amount'),
            'processed_payouts' => Payout::where('status', 'processed')->sum('amount'),
            'send_to_bank_payouts' => Payout::where('status', 'send_to_bank')->sum('amount'),
            'transaction_status_total' => Payout::whereIn('status', ['initiated', 'success', 'pending', 'failed', 'processed', 'send_to_bank'])->sum('amount'),
            'today_volume' => Payout::whereDate('created_at', $today)->sum('amount'),
            'today_success' => Payout::whereDate('created_at', $today)
                ->where('status', 'success')
                ->count(),
        ];
    }

    public function getTodayMerchants()
    {
        return User::where('role', 'merchant')
            ->whereDate('created_at', Carbon::today())
            ->with(['merchantKyc', 'merchantVirtualAccount'])
            ->latest()
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'mobile' => $user->phone,
                    'status' => $this->getFormattedStatus($user),
                    'registered_at' => $user->created_at->format('Y-m-d H:i'),
                    'kyc_status' => ucfirst($user->kyc_status),
                    'business_name' => $user->merchantKyc->business_name ?? 'N/A',
                ];
            });
    }

    public function getTodayPayouts()
    {
        return Payout::query()
        ->select([
            'id',
            'user_id',
            'transaction_id',
            'utr',
            'account_holder',
            'account_number',
            'bank_name',
            'ifsc_code',
            'amount',
            'fee',
            'total_amount',
            'status',
            'mode',
            'initiated_at',
            'created_at',
        ])
        ->with([
            'user:id,full_name,phone',
            'refund:id,payout_id,amount,status',
        ])
        ->whereBetween('created_at', [
            now()->startOfDay(),
            now()->endOfDay(),
        ])
        ->latest('id')
        ->limit(10)
        ->get();
    }

    public function getRecentInwardFunds()
    {
        return Deposit::select([
            'id',
            'user_id',
            'alert_sequence_no',
            'virtual_account',
            'account_number',
            'amount',
            'transaction_description',
            'transaction_date',
            'mnemonic_code',
            'processing_status',
            'created_at',
        ])
        ->with(['user:id,full_name,phone'])
        ->latest()
        ->take(10)
        ->get();
    }

    protected function getFormattedStatus($user)
    {
        if ($user->status === 'active' && $user->kyc_status === 'verified') {
            return 'Active';
        } elseif ($user->status === 'suspended') {
            return 'Suspended';
        } elseif ($user->kyc_status === 'rejected') {
            return 'Rejected';
        } else {
            return 'Pending';
        }
    }

    public function getTopPerformers()
    {
        return User::where('role', 'merchant')
            ->where('status', 'active')
            ->where('kyc_status', 'verified')
            ->withCount(['payouts as success_count' => function($query) {
                $query->where('status', 'success');
            }])
            ->withSum(['payouts as total_volume' => function($query) {
                $query->where('status', 'success');
            }], 'amount')
            ->orderByDesc('total_volume')
            ->take(5)
            ->get()
            ->map(function($merchant) {
                return [
                    'name' => $merchant->full_name,
                    'volume' => $merchant->total_volume ?? 0,
                    'count' => $merchant->success_count,
                    'business' => $merchant->merchantKyc->business_name ?? 'N/A'
                ];
            });
    }

    public function render()
    {
        return view('admin.dashboard-component', [
            'businessAnalytics' => $this->getBusinessAnalytics(),
            'todayMerchants' => $this->getTodayMerchants(),
            'todayPayouts' => $this->getTodayPayouts(),
            'recentInwardFunds' => $this->getRecentInwardFunds(),
            'topPerformers' => $this->getTopPerformers(),
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'metaTitle' => 'Dashboard - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Admin Dashboard for M.M.P Fintech Payment Solution',
        ]);
    }
}
