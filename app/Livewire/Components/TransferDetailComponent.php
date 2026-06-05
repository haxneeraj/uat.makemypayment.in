<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Payout;
use App\Services\PayoutService;

class TransferDetailComponent extends Component
{       
    public $transferId;
    public $showTransferDetailModal = false;
    public $refreshing = false;
    public $refreshMessage = null;

    protected $listeners = ['openTransferDetailModal' => 'open', 'closeTransferDetailModal' => 'close'];

    public function open($transferId)
    {
        $this->transferId = $transferId;
        $this->showTransferDetailModal = true;
        $this->refreshMessage = null;
    }
    
    public function close()
    {
        $this->transferId = null;
        $this->showTransferDetailModal = false;
        $this->refreshMessage = null;
    }

    public function refreshStatus()
    {
        if (!$this->transferId) return;

        $this->refreshing = true;
        $this->refreshMessage = null;

        try {
            $status = app(PayoutService::class)->getPayoutStatusByTransactionId($this->transferId);
            $this->refreshMessage = $status
                ? ['type' => 'success', 'text' => 'Status updated: ' . ucfirst(str_replace('_', ' ', $status))]
                : ['type' => 'error',   'text' => 'Could not fetch status from gateway.'];
        } catch (\Throwable $e) {
            $this->refreshMessage = ['type' => 'error', 'text' => 'Error: ' . $e->getMessage()];
        } finally {
            $this->refreshing = false;
        }
    }

    public function render()
    {
        $payout = Payout::with(['user'])->where('transaction_id', $this->transferId)->first();
        return view('components.transfer-detail-component', [
            'payout' => $payout,
        ])
        ->layout(null);
    }
}
