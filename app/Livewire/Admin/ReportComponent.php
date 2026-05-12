<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ReportComponent extends Component
{
    public function render()
    {
        return view('admin.report-component')
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'reports',
            'pageTitle' => 'Reports',
            'metaTitle' => 'Reports - MMP Fintech',
            'metaDescription' => 'Reports',
        ]);
    }
}
