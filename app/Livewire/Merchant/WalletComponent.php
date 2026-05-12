<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\SourceAccount;
use App\Models\Deposit;

class WalletComponent extends Component
{    
    use WithPagination;

    public $user;
    public $van;
    
    public $showAddAccountModal = false;
    public $holder_name;
    public $account_number;
    public $ifsc_code;
    public $bank;
    
    // For Deposits Table
    public $search = '';
    public $filterBy = 'alert_sequence_no';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    protected $queryString = [
        'search'   => ['except' => ''],
        'filterBy' => ['except' => 'alert_sequence_no'],  // alert_sequence_no | virtual_account
        'dateFrom' => ['except' => ''],
        'dateTo'   => ['except' => ''],
        'perPage'  => ['except' => 10],
        'currentPage' => ['except' => 1],
    ];

    protected $rules = [
        'holder_name'    => 'required|string|max:255',
        'account_number' => 'required|string|max:255|unique:source_accounts,account_number',
        'ifsc_code'      => 'required|string|max:11',
        'bank'           => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->user = auth()->user();
        $this->van  = $this->user->merchantVirtualAccount;
    }

    public function updatingSearch()   { $this->currentPage = 1; }
    public function updatingFilterBy() { $this->currentPage = 1; }
    public function updatingDateFrom() { $this->currentPage = 1; }
    public function updatingDateTo()   { $this->currentPage = 1; }
    public function updatingPerPage()  { $this->currentPage = 1; }

    public function clearFilters(): void
    {
        $this->search      = '';
        $this->filterBy    = 'alert_sequence_no';
        $this->dateFrom    = '';
        $this->dateTo      = '';
        $this->currentPage = 1;
        $this->dispatch('walletFiltersCleared');
    }

    public function render()
    {
        $depositsQuery = Deposit::where('user_id', $this->user->id)
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year);

        if ($this->search) {
            $column = match ($this->filterBy) {
                'virtual_account' => 'virtual_account',
                default           => 'alert_sequence_no',
            };
            $depositsQuery->where($column, 'like', '%' . $this->search . '%');
        }

        $deposits = $depositsQuery->latest('transaction_date')
            ->paginate($this->perPage);

        return view('merchant.wallet-component', [
            'deposits' => $deposits
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'wallet',
            'pageTitle' => 'Wallet',
            'metaTitle' => 'Wallet - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Wallet for M.M.P Fintech Payment Solution',
        ]);
    }
}
