<?php

namespace App\Livewire\Admin;

use App\Models\SourceAccount;
use Livewire\Component;
use Livewire\WithPagination;

class SouceAccountVerification extends Component
{
    use WithPagination;

    public $search = '';
    public $showRejectModal = false;
    public $selectedAccountId = null;
    public $rejectRemark = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function approveAccount(int $id): void
    {
        $account = SourceAccount::where('status', 'inactive')
            ->where(function ($query) {
                $query->whereNull('remarks')->orWhere('remarks', '');
            })
            ->findOrFail($id);

        $account->update([
            'status' => 'active',
            'remarks' => null,
        ]);

        session()->flash('success', 'Source account has been approved successfully.');
    }

    public function openRejectModal(int $id): void
    {
        $account = SourceAccount::where('status', 'inactive')
            ->where(function ($query) {
                $query->whereNull('remarks')->orWhere('remarks', '');
            })
            ->findOrFail($id);

        $this->selectedAccountId = $account->id;
        $this->rejectRemark = '';
        $this->showRejectModal = true;
        $this->resetErrorBag('rejectRemark');
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->selectedAccountId = null;
        $this->rejectRemark = '';
        $this->resetErrorBag('rejectRemark');
    }

    public function rejectAccount(): void
    {
        $this->validate([
            'rejectRemark' => 'required|string|min:3|max:500',
        ], [
            'rejectRemark.required' => 'Rejection remark is required.',
        ]);

        if (!$this->selectedAccountId) {
            $this->closeRejectModal();
            return;
        }

        $account = SourceAccount::where('status', 'inactive')
            ->where(function ($query) {
                $query->whereNull('remarks')->orWhere('remarks', '');
            })
            ->findOrFail((int) $this->selectedAccountId);

        $account->update([
            'status' => 'inactive',
            'remarks' => $this->rejectRemark,
        ]);

        $this->closeRejectModal();
        session()->flash('success', 'Source account has been rejected successfully.');
    }

    public function render()
    {
        $accounts = SourceAccount::with('user')
            ->where('status', 'inactive')
            ->where(function ($query) {
                $query->whereNull('remarks')->orWhere('remarks', '');
            })
            ->when(!blank($this->search), function ($query) {
                $query->where(function ($q) {
                    $q->where('account_holder_name', 'like', '%' . $this->search . '%')
                        ->orWhere('account_number', 'like', '%' . $this->search . '%')
                        ->orWhere('ifsc_code', 'like', '%' . $this->search . '%')
                        ->orWhere('bank_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('full_name', 'like', '%' . $this->search . '%')
                                ->orWhere('merchant_id', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.souce-account-verification', [
            'accounts' => $accounts,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'pending-source-account-verifications',
            'pageTitle' => 'Source Account Verification',
            'metaTitle' => 'Source Account Verification - MMP Fintech',
            'metaDescription' => 'Review and approve or reject merchant source accounts.',
        ]);
    }
}
