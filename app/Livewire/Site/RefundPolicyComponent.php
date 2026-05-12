<?php

namespace App\Livewire\Site;

use Livewire\Component;

class RefundPolicyComponent extends Component
{
    public function render()
    {
        return view('site.refund-policy-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => "Refund Policy | MakeMyPayment Fintech Platform",
            'metaDescription' => "Read the refund and cancellation policy for MakeMyPayment, India's secure payment gateway and fintech services.",
            'metaKeywords' => 'refund policy, cancellation, payment gateway, fintech, MakeMyPayment'
        ]);
    }
}
