<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Payout;
use App\Models\Deposit;
use Carbon\Carbon;

class ViewMerchantComponent extends Component
{
    public $merchant_id;
    public $merchant;

    public function mount($merchant_id)
    {
        $this->merchant_id = $merchant_id;
        $this->merchant = User::where('merchant_id', $merchant_id)
            ->with(['merchantKyc', 'merchantVirtualAccount', 'merchantSourceAccounts'])
            ->firstOrFail();
    }

    public function getMerchantStats()
    {
        $today = Carbon::today();
        
        return [
            'total_volume' => Payout::where('user_id', $this->merchant->id)
                ->where('status', 'success')
                ->sum('amount'),
            'today_volume' => Payout::where('user_id', $this->merchant->id)
                ->whereDate('created_at', $today)
                ->where('status', 'success')
                ->sum('amount'),
            'total_deposits' => Deposit::where('user_id', $this->merchant->id)
                ->sum('amount'),
            'success_rate' => $this->calculateSuccessRate(),
            'payout_count' => Payout::where('user_id', $this->merchant->id)->count(),
            'deposit_count' => Deposit::where('user_id', $this->merchant->id)->count(),
        ];
    }

    protected function calculateSuccessRate()
    {
        $total = Payout::where('user_id', $this->merchant->id)->count();
        if ($total === 0) return 0;
        
        $success = Payout::where('user_id', $this->merchant->id)
            ->where('status', 'success')
            ->count();
            
        return round(($success / $total) * 100, 2);
    }

    public function getRecentPayouts()
    {
        return Payout::where('user_id', $this->merchant->id)
            ->with('payee')
            ->latest()
            ->take(5)
            ->get();
    }

    public function getRecentDeposits()
    {
        return Deposit::where('user_id', $this->merchant->id)
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('admin.view-merchant-component', [
            'stats' => $this->getMerchantStats(),
            'recentPayouts' => $this->getRecentPayouts(),
            'recentDeposits' => $this->getRecentDeposits(),
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'merchants',
            'pageTitle' => 'View Merchant: ' . $this->merchant->full_name,
            'metaTitle' => 'View Merchant Details - MMP Fintech',
            'metaDescription' => 'Detailed view of merchant profile and transactions.',
        ]);
    }
}
