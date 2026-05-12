<?php

namespace App\Livewire\Site;

use Livewire\Component;

class PrivacyPolicyComponent extends Component
{
    public function render()
    {
        return view('site.privacy-policy-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => "Privacy Policy | MakeMyPayment Fintech Platform",
            'metaDescription' => "Read the privacy policy for MakeMyPayment, India's secure payment gateway and fintech services.",
            'metaKeywords' => 'privacy policy, data protection, payment gateway, fintech, MakeMyPayment'
        ]);
    }
}
