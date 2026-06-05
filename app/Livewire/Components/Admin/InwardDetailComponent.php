<?php

namespace App\Livewire\Components\Admin;

use Livewire\Component;
use App\Models\Deposit;

class InwardDetailComponent extends Component
{
    public $alert_sequence_no = null;
    public $deposit;
    public $merchant;
    public $showModal = false;

    protected $listeners = ['openInwardDetailModal' => 'openModal'];

    public function openModal($alert_sequence_no)
    {
        \Log::info($alert_sequence_no, ['Received alert_sequence_no in openModal']);
        $this->alert_sequence_no = $alert_sequence_no ?? null;
        $this->loadDetails();
        $this->showModal = true;
    }

    public function loadDetails()
    {
        $this->deposit = Deposit::with('user:id,full_name,phone,email,merchant_id')->where('alert_sequence_no', $this->alert_sequence_no)->first();
        $this->merchant = $this->deposit?->user;
        \Log::info('Loaded deposit details for alert_sequence_no: ' . $this->alert_sequence_no, [
            'deposit' => $this->deposit,
            'merchant' => $this->merchant,
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('components.admin.inward-detail-component');
    }
}
