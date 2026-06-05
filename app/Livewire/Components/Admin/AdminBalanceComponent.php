<?php

namespace App\Livewire\Components\Admin;

use Livewire\Component;
use App\Services\PayoutService;

class AdminBalanceComponent extends Component
{       
    public $user;
    public $van;
    public $balance = 0;
    public $balanceVisible = false;
    
    public function mount()
    {
        # set authenticated user
        $this->user = auth()->user();

        # fetch van
        $this->van = $this->user->merchantVirtualAccount;
    }

    public function fetchBalance()
    {
        try {
            $payoutService = app(PayoutService::class);
            $response = $payoutService->getAccountBalance();
            if ($response['status'] !== 'success' && (!isset($response['data']) || !is_array($response['data']) || !isset($response['data']['availableBalance']))) {
                $this->balance = 0;
                $this->balanceVisible = false;
                $this->dispatch('toast', [
                    'message' => $response['message'] ?? 'Failed to fetch balance. Please try again later.',
                    'type' => 'error',
                ]);
                return;
            }

            $this->balance = $response['data']['availableBalance'] ?? 0;
            $this->balanceVisible = true;
        } catch (\Exception $e) {
            $this->balance = 0;
            $this->balanceVisible = false;
            \Log::error('AdminBalanceComponent fetchBalance error: ' . $e->getMessage());
        }
    }

    public function toggleBalance()
    {
        if ($this->balanceVisible) {
            $this->balanceVisible = false;
        } else {
            $this->fetchBalance();
        }
    }

    public function render()
    {
        return view('components.admin.admin-balance-component')
        ->layout(null);
    }
}
