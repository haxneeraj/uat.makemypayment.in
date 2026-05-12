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
            'total_payouts' => Payout::where('status', 'success')->sum('amount'),
            'pending_payouts' => Payout::where('status', 'pending')->sum('amount'),
            'rejected_payouts' => Payout::where('status', 'failed')->sum('amount'),
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
        return Payout::with(['user'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get()
            ->map(function($payout) {
                return [
                    'date' => $payout->created_at->format('d M,y H:i'),
                    'amount' => '₹' . number_format($payout->amount, 2),
                    'status' => ucfirst($payout->status),
                    'utr' => $payout->utr,
                    'beneficiary' => $payout->user->full_name ?? 'N/A',
                    'merchant' => $payout->user->full_name ?? 'N/A',
                    'mode' => $payout->mode
                ];
            });
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
            'topPerformers' => $this->getTopPerformers(),
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'metaTitle' => 'Dashboard - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Admin Dashboard for M.M.P Fintech Payment Solution',
        ]);
    }
}
