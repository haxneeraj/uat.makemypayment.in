<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PendingKycComponent extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Assuming 'merchant' role and 'submitted' kyc_status
        $merchants = User::where('role', 'merchant')
            ->where('kyc_status', 'submitted')
            ->where(function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('merchant_id', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('admin.pending-kyc-component', [
            'merchants' => $merchants,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'pending-kyc',
            'pageTitle' => 'Pending KYC',
            'metaTitle' => 'Pending KYC - MMP Fintech',
            'metaDescription' => 'Review and manage merchants with pending KYC applications.',
        ]);
    }
}
