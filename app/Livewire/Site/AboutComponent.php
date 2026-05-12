<?php

namespace App\Livewire\Site;

use Livewire\Component;

class AboutComponent extends Component
{
    public function render()
    {
        return view('site.about-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => "About MakeMyPayment | India's Leading Payment Gateway & Fintech Platform",
            'metaDescription' => 'Learn more about MakeMyPayment, our mission, vision, and the team behind India\'s secure fintech payment solutions.',
            'metaKeywords' => 'about us, fintech, payment solutions, secure payments, company information'
        ]);
    }
}
