<?php

namespace App\Livewire\Site;

use Livewire\Component;

class TermsComponent extends Component
{
    public function render()
    {
        return view('site.terms-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => "Terms & Conditions | MakeMyPayment Fintech Platform",
            'metaDescription' => "Read the terms and conditions for using MakeMyPayment, India's secure payment gateway and fintech services.",
            'metaKeywords' => 'terms and conditions, payment gateway, fintech, user agreement, policies, MakeMyPayment'
        ]);
    }
}
