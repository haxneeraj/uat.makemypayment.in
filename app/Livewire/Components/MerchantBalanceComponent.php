<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Services\Van\VanService;

class MerchantBalanceComponent extends Component
{       
    public $user;
    public $van;
    public $balance = 0;

    public function mount()
    {
        # set authenticated user
        $this->user = auth()->user();

        # fetch van
        $this->van = $this->user->merchantVirtualAccount;
    }

    public function fetchBalance()
    {
        $this->balance = app(VanService::class)->getVanBalanceByUserId(auth()->user()->id);
    }

    public function render()
    {
        return view('components.merchant-balance-component')
        ->layout(null);
    }
}
