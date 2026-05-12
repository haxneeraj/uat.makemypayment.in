<?php

namespace App\Livewire\Merchant;

use Livewire\Component;

class OrganizationComponent extends Component
{
    public function mount()
    {
        # Check for KYC if not exists then redirect to complete kyc form
        if(!auth()->user()->merchantKyc()->exists())
        {
            return redirect()->route('merchant.kyc');
        }
    }

    public function render()
    {
        $user = auth()->user();

        return view('merchant.organization-component',[
            'kyc' => auth()->user()->merchantKyc,
            'ipandcallback' => $user->merchantCallbackAndIP,
            'sourceAccounts' => $user->merchantSourceAccounts
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'organization',
            'pageTitle' => 'Organization',
            'metaTitle' => 'Organization - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Organization for M.M.P Fintech Payment Solution',
        ]);
    }
}
