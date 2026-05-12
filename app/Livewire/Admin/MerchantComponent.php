<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class MerchantComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $kyc_status = '';
    public $van_status = '';
    public $showConfirmation = false;
    public $modalType = 'active';
    public $selectedMerchant = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStatus()
    {
        $this->resetPage();
    }
    public function updatingKycStatus()
    {
        $this->resetPage();
    }
    public function updatingVanStatus()
    {
        $this->resetPage();
    }

    public function confirmSuspend($merchantId, $type)
    {
        $merchant = User::where('merchant_id', $merchantId)->first();        
        $this->modalType = $type;
        if(!$merchant){
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Something went wrong.',
                'icon' => 'error'
            ]);
            return;
        }
        $this->selectedMerchant = $merchant;
        $this->showConfirmation = true;
    }

    public function suspendMerchant()
    {
        if (!$this->selectedMerchant) return;

        try {
            $status = $this->modalType == 'active' ? 'active' : 'suspended';
            $this->selectedMerchant->update([
                'status' => $status
            ]);
            $msg = $status === 'active' ? 'activated' : 'suspended';

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Merchant has been ' . $msg . '.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to suspend merchant.',
                'icon' => 'error'
            ]);
        }

        $this->showConfirmation = false;
        $this->selectedMerchant = null;
    }

    public function cancelSuspend()
    {
        $this->showConfirmation = false;
        $this->selectedMerchant = null;
    }  

    public function render()
    {
        $query = User::query()
            ->where('role', 'merchant');

        if (!blank($this->search)) {
            $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('merchant_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }
        if (!blank($this->status)) {
            $query->where('status', $this->status);
        }
        if (!blank($this->kyc_status)) {
            $query->where('kyc_status', $this->kyc_status);
        }
        if (!blank($this->van_status)) {
            $query->where('van_status', $this->van_status);
        }

        $merchants = $query->orderByDesc('id')->paginate(10);

        $statuses = ['active', 'inactive', 'suspended'];
        $kyc_statuses = ['pending', 'verified', 'rejected'];
        $van_statuses = ['pending', 'verified', 'rejected'];

        return view('admin.merchant-component', [
            'merchants' => $merchants,
            'statuses' => $statuses,
            'kyc_statuses' => $kyc_statuses,
            'van_statuses' => $van_statuses,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'merchants',
            'pageTitle' => 'Merchants',
            'metaTitle' => 'Merchant List - MMP Fintech',
            'metaDescription' => 'View and manage all merchants in the MMP Fintech admin dashboard.',
        ]);
    }
}
