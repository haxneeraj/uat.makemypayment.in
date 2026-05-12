<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Deposit;

class DepositDetailComponent extends Component
{
    public $alertSequenceNo;
    public $showDepositDetailModal = false;

    protected $listeners = [
        'openDepositDetailModal'  => 'open',
        'closeDepositDetailModal' => 'close',
    ];

    public function open(string $alertSequenceNo): void
    {
        $this->alertSequenceNo      = $alertSequenceNo;
        $this->showDepositDetailModal = true;
    }

    public function close(): void
    {
        $this->alertSequenceNo      = null;
        $this->showDepositDetailModal = false;
    }

    public function render()
    {
        $deposit = Deposit::where('alert_sequence_no', $this->alertSequenceNo)->first();

        return view('components.deposit-detail-component', [
            'deposit' => $deposit,
        ])->layout(null);
    }
}
