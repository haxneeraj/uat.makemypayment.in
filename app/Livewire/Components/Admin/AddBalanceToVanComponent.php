<?php

namespace App\Livewire\Components\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Deposit;
use App\Models\MerchantVirtualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddBalanceToVanComponent extends Component
{
    public bool    $showModal      = false;
    public ?array  $merchant       = null;
    public ?array  $van            = null;
    public float   $currentBalance = 0.0;
    public string  $addAmount      = '';
    public string  $remarks        = '';

    #[Computed]
    public function newBalance(): float
    {
        $amount = is_numeric($this->addAmount) ? (float) $this->addAmount : 0.0;
        return $this->currentBalance + max(0.0, $amount);
    }

    #[On('openAddBalanceModal')]
    public function openModal(string $merchantId): void
    {
        $this->reset(['addAmount', 'remarks']);
        $this->resetErrorBag();

        $user = User::with('merchantVirtualAccount')
            ->where('merchant_id', $merchantId)
            ->first();

        if (! $user) {
            return;
        }

        $van = $user->merchantVirtualAccount;

        $this->merchant = [
            'id'          => $user->id,
            'full_name'   => $user->full_name,
            'merchant_id' => $user->merchant_id,
            'email'       => $user->email,
            'phone'       => $user->phone,
            'status'      => $user->status,
            'kyc_status'  => $user->kyc_status,
            'van_status'  => $user->van_status,
        ];

        $this->van = $van ? [
            'id'             => $van->id,
            'van'            => $van->van,
            'account_holder' => $van->account_holder,
            'ifsc'           => $van->ifsc,
            'balance'        => (float) $van->balance,
            'status'         => $van->status,
        ] : null;

        $this->currentBalance = $van ? (float) $van->balance : 0.0;
        $this->showModal      = true;
    }

    public function closeModal(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function addBalance(): void
    {
        $this->validate([
            'addAmount' => ['required', 'numeric', 'min:1', 'max:10000000'],
            'remarks'   => ['nullable', 'string', 'max:255'],
        ], [
            'addAmount.required' => 'Please enter an amount.',
            'addAmount.numeric'  => 'Amount must be a number.',
            'addAmount.min'      => 'Minimum amount is ₹1.',
            'addAmount.max'      => 'Maximum amount is ₹1,00,00,000.',
        ]);

        if (! $this->van) {
            $this->dispatch('toast', ['message' => 'No VAN found for this merchant.', 'type' => 'error']);
            return;
        }

        $amount = (float) $this->addAmount;
        $vanId = $this->van['id'];

        try {
            DB::transaction(function () use ($vanId, $amount) {
                $vanRecord = MerchantVirtualAccount::where('id', $vanId)->lockForUpdate()->firstOrFail();
                $old_balance = (float) $vanRecord->balance;
                
                $originalStatus = $vanRecord->status;
                $vanRecord->update(['status' => 'frozen']);
                $vanRecord->increment('balance', $amount);
                $vanRecord->update(['status' => $originalStatus]);

                $newBalance = (float) $vanRecord->refresh()->balance;
                $this->currentBalance = $newBalance;
                $this->van['balance'] = $newBalance;
                $this->van['status']  = $originalStatus;

                // Create Deposit record for manual admin addition
                $deposit =Deposit::create([
                    'user_id'                => $vanRecord->user_id,
                    'alert_sequence_no'      => 'ADMIN-' . now()->format('YmdHis') . '-' . uniqid(),
                    'remitter_name'          => 'Admin',
                    'remitter_account'       => null,
                    'remitter_bank'          => null,
                    'user_reference_number'  => null,
                    'virtual_account'        => $vanRecord->van,
                    'amount'                 => $amount,
                    'mnemonic_code'          => 'ADMIN_MANUAL',
                    'transaction_date'       => now(),
                    'value_date'             => now()->toDateString(),
                    'ifsc_code'              => $vanRecord->ifsc,
                    'cheque_no'              => null,
                    'transaction_description'=> 'Manual VAN credit by admin',
                    'account_number'         => $vanRecord->van,
                    'debit_credit'           => 'credit',
                    'raw_payload'            => [
                        'source'   => 'admin_manual',
                        'amount_added' => $amount,
                        'old_balance' => $old_balance,
                        'new_balance' => $newBalance,
                        'remarks'  => $this->remarks,
                    ],
                    'processing_status'      => 'success',
                ]);
            });

            $this->reset(['addAmount', 'remarks']);
            $this->dispatch('merchantBalanceUpdated');
            $this->dispatch('toast', [
                'message' => '₹' . number_format($amount, 2) . ' added. New balance: ₹' . number_format($this->currentBalance, 2),
                'type'    => 'success',
            ]);

        } catch (\Throwable $e) {
            $this->dispatch('toast', ['message' => 'Failed to add balance. Please try again.', 'type' => 'error']);
            Log::error('AddBalanceToVanComponent::addBalance: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('components.admin.add-balance-to-van-component')
            ->layout(null);
    }
}