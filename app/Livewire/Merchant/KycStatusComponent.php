<?php

namespace App\Livewire\Merchant;

use Livewire\Component;

class KycStatusComponent extends Component
{
    public function render()
    {
        return view('merchant.kyc-status-component')
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'dashboard',
            'pageTitle' => 'KYC Verification',
            'metaTitle' => 'KYC Verification - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Complete your KYC verification to unlock all features on M.M.P Fintech Payment Solution.',
        ]);
    }
}
