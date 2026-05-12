<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use App\Models\SourceAccount as SourceAccountModel;

class SourceAccount extends Component
{
    public $showAddModal      = false;
    public $showKycBlockModal = false;
    public $deleteConfirmId   = null;

    public $account_number;
    public $ifsc_code;
    public $account_holder_name;
    public $bank_name;

    protected function rules(): array
    {
        return [
            'account_number'      => 'required|string|max:20|unique:source_accounts,account_number',
            'ifsc_code'           => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i',
            'account_holder_name' => 'required|string|max:100',
            'bank_name'           => 'required|string|max:100',
        ];
    }

    protected $messages = [
        'account_number.unique'  => 'This account number is already registered.',
        'ifsc_code.regex'        => 'Please enter a valid IFSC code (e.g. HDFC0001234).',
    ];

    public function openAddModal(): void
    {
        $merchant = auth()->user();

        if ($merchant->kyc_status !== 'verified' || $merchant->van_status !== 'verified') {
            $this->showKycBlockModal = true;
            return;
        }

        if ($merchant->merchantSourceAccounts()->count() >= $merchant->max_source_accounts) {
            session()->flash('error', "You have reached the maximum limit of {$merchant->max_source_accounts} source accounts.");
            return;
        }

        $this->resetFields();
        $this->showAddModal = true;
    }

    public function closeKycBlockModal(): void
    {
        $this->showKycBlockModal = false;
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetFields();
        $this->resetValidation();
    }

    public function addAccount(): void
    {
        $merchant = auth()->user();

        if ($merchant->kyc_status !== 'verified' || $merchant->van_status !== 'verified') {
            $this->showKycBlockModal = true;
            $this->showAddModal = false;
            return;
        }

        if ($merchant->merchantSourceAccounts()->count() >= $merchant->max_source_accounts) {
            $this->addError('account_number', "You have reached the maximum limit of {$merchant->max_source_accounts} source accounts.");
            return;
        }

        $this->validate();

        $merchant->merchantSourceAccounts()->create([
            'account_number'      => $this->account_number,
            'ifsc_code'           => strtoupper($this->ifsc_code),
            'account_holder_name' => $this->account_holder_name,
            'bank_name'           => $this->bank_name,
        ]);

        $this->showAddModal = false;
        $this->resetFields();
        session()->flash('success', 'Source account added successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $account = SourceAccountModel::where('user_id', auth()->id())->findOrFail($id);

        if ($account->is_primary) {
            session()->flash('error', 'Primary account cannot be deleted.');
            return;
        }

        $this->deleteConfirmId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deleteConfirmId = null;
    }

    public function deleteAccount(int $id): void
    {
        $account = SourceAccountModel::where('user_id', auth()->id())->findOrFail($id);

        if ($account->is_primary) {
            session()->flash('error', 'Primary account cannot be deleted.');
            $this->deleteConfirmId = null;
            return;
        }

        $account->delete();
        $this->deleteConfirmId = null;
        session()->flash('success', 'Source account removed successfully.');
    }

    private function resetFields(): void
    {
        $this->account_number      = '';
        $this->ifsc_code           = '';
        $this->account_holder_name = '';
        $this->bank_name           = '';
    }

    public function render()
    {
        $merchant       = auth()->user();
        $sourceAccounts = $merchant->merchantSourceAccounts()->latest()->get();

        return view('merchant.source-account', [
            'sourceAccounts'     => $sourceAccounts,
            'maxSourceAccounts'  => $merchant->max_source_accounts,
            'usedSlots'          => $sourceAccounts->count(),
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active'          => 'source-accounts',
            'pageTitle'       => 'Source Accounts',
            'metaTitle'       => 'Source Accounts - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Manage your payout source bank accounts.',
        ]);
    }
}

