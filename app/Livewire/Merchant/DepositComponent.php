<?php

namespace App\Livewire\Merchant;

use Livewire\Component;

class DepositComponent extends Component
{
    public function render()
    {
        return view('merchant.deposit-component')
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'deposits',
            'pageTitle' => 'Deposits',
            'metaTitle' => 'Deposits - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Deposits for M.M.P Fintech Payment Solution',
        ]);
    }
}
