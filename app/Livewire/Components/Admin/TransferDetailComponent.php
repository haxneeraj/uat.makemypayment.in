<?php

namespace App\Livewire\Components\Admin;

use Livewire\Component;
use App\Models\Payout;
use App\Models\User;

use App\Services\PayoutService;

use App\Jobs\Payouts\ProcessPayoutJob;
use App\Jobs\WebhookRunner\SendPayoutWebhookJob;

class TransferDetailComponent extends Component
{
    public $transaction_id = null;
    public $payout;
    public $merchant;
    public $showModal = false;
    public $refreshing = false;
    public $refreshMessage = null;

    protected $listeners = ['openTransferDetailModal' => 'openModal'];

    public function openModal($transaction_id)
    {
        \Log::info($transaction_id, ['Received transaction_id in openModal']);
        $this->transaction_id = $transaction_id ?? null;
        $this->loadDetails();
        $this->showModal = true;
    }

    public function loadDetails()
    {
        $this->payout = Payout::with('user:id,full_name,phone,email,merchant_id')->where('transaction_id', $this->transaction_id)->first();
        $this->merchant = $this->payout?->user;
        \Log::info('Loaded payout details for transaction_id: ' . $this->transaction_id, [
            'payout' => $this->payout,
            'merchant' => $this->merchant,
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->refreshing = false;
        $this->refreshMessage = null;
        $this->transaction_id = null;
    }

    public function refreshStatus()
    {
        if (!$this->transaction_id) return;

        $this->refreshing = true;
        $this->refreshMessage = null;

        try {
            $status = app(PayoutService::class)->getPayoutStatusByTransactionId($this->transaction_id);
            $this->refreshMessage = $status
                ? ['type' => 'success', 'text' => 'Status updated: ' . ucfirst(str_replace('_', ' ', $status))]
                : ['type' => 'error',   'text' => 'Could not fetch status from gateway.'];
        } catch (\Throwable $e) {
            $this->refreshMessage = ['type' => 'error', 'text' => 'Error: ' . $e->getMessage()];
        } finally {
            $this->refreshing = false;
        }
    }

    public function triggerWebhook()
    {
        if (!$this->transaction_id || !$this->payout) return;

        try {
            // Dispatch webhook to notify user about payout status change
            SendPayoutWebhookJob::dispatch(
                $this->payout->user_id,
                [
                    'transaction_id' => $this->payout->transaction_id,
                    'beneficiary_account_holder' => $this->payout->account_holder,
                    'beneficiary_account_number' => $this->payout->account_number,
                    'beneficiary_bank_name' => $this->payout->bank_name,
                    'beneficiary_ifsc_code' => $this->payout->ifsc_code,
                    'amount' => $this->payout->amount,
                    'status' => $this->payout->status,
                    'utr' => $this->payout->utr ?? null,
                    'remarks' => $this->payout->remarks,
                    'narration' => $this->payout->narration,
                ]
            )
            ->afterCommit()
            ->onQueue('webhooks-runner');

            // dispatch toast message
            $this->dispatch('toast', [
                'message' => 'Merchant has been notified about the payout status change.',
                'type' => 'success',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error refreshing payout status for transaction_id: ' . $this->transaction_id, [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function retryPayment()
    {
        if (!$this->transaction_id || !$this->payout) return;

        try {            
            // Dispatch Payout Job to process the payout asynchronously, so that API response is not delayed by external call
            ProcessPayoutJob::dispatch($this->payout->id)
            ->onConnection('redis')
            ->afterCommit();

            // dispatch toast message
            $this->dispatch('toast', [
                'message' => 'Payment retry has been initiated. Please refresh status after a few moments.',
                'type' => 'success',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error initiating payment retry for transaction_id: ' . $this->transaction_id, [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            $this->dispatch('toast', [
                'message' => 'Error initiating payment retry: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('components.admin.transfer-detail-component');
    }
}
