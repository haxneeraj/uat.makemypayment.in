<?php

namespace App\Livewire\Components\Admin;

use Livewire\Component;
use App\Models\MerchantVirtualAccount;

class VanBalanceComponent extends Component
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
            $this->balance = MerchantVirtualAccount::where('status', 'active')->sum('balance');
        }catch (\Exception $e) {
            $this->balance = 0;
            $this->balanceVisible = false;
            \Log::error('VanBalanceComponent fetchBalance error: ' . $e->getMessage());
        }
    }

    public function toggleBalance()
    {
        if ($this->balanceVisible) {
            $this->balanceVisible = false;
        } else {
            $this->fetchBalance();
            $this->balanceVisible = true;
        }
    }

    public function render()
    {
        return view('components.admin.van-balance-component')
        ->layout(null);
    }
}
