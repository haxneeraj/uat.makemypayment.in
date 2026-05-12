<?php

namespace App\Livewire\Site;

use Livewire\Component;

class ServiceComponent extends Component
{
    public function render()
    {
        return view('site.service-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => 'Our Services - Make My Payment',
            'metaDescription' => 'Explore the range of secure and reliable fintech payment solutions offered by Make My Payment, designed for businesses and individuals.',
            'metaKeywords' => 'payment services, fintech solutions, online payments, secure transactions, business services'
        ]);
    }
}
