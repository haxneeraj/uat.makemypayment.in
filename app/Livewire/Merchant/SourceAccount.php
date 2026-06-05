<?php

namespace App\Livewire\Merchant;

use App\Services\SMSService;
use Livewire\Component;
use App\Models\SourceAccount as SourceAccountModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SourceAccount extends Component
{
    use WithFileUploads;

    public $showAddModal      = false;
    public $showKycBlockModal = false;
    public $deleteConfirmId   = null;

    public $account_number;
    public $ifsc_code;
    public $account_holder_name;
    public $bank_name;
    public $document_type;
    public $document_file;
    public $showOTPModal = false;
    public $otp;
    public $otpAction;
    public $resendTimer = 0;

    protected $otpSessionKey = 'source_account_action_otp';
    protected $otpActionKey = 'source_account_action_otp_action';
    protected $otpUserIdKey = 'source_account_action_otp_user_id';
    protected $otpPayloadKey = 'source_account_action_otp_payload';
    protected $resendWait = 90;

    protected function rules(): array
    {
        return [
            'account_number'      => 'required|string|max:20|unique:source_accounts,account_number',
            'ifsc_code'           => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i',
            'account_holder_name' => 'required|string|max:100',
            'bank_name'           => 'required|string|max:100',
            'document_type'       => 'required|in:statement,cancel_cheque',
            'document_file'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:12288',
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
            $this->dispatch('toast', type: 'error', message: "You have reached the maximum limit of {$merchant->max_source_accounts} source accounts.");
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
            $this->dispatch('toast', type: 'error', message: "You have reached the maximum limit of {$merchant->max_source_accounts} source accounts.");
            return;
        }

        $this->validate();

        if (blank($merchant->phone)) {
            $this->dispatch('toast', type: 'error', message: 'Unable to send OTP. Please update your registered mobile number.');
            return;
        }

        $documentPath = $this->document_file->store('source-accounts/pending', 'public');

        $this->sendActionOtp('create', [
            'account_number'      => $this->account_number,
            'ifsc_code'           => strtoupper($this->ifsc_code),
            'account_holder_name' => $this->account_holder_name,
            'bank_name'           => $this->bank_name,
            'document_type'       => $this->document_type,
            'document_path'       => $documentPath,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $account = SourceAccountModel::where('user_id', auth()->id())->findOrFail($id);

        if ($account->is_primary) {
            $this->dispatch('toast', type: 'error', message: 'Primary account cannot be deleted.');
            return;
        }

        $this->deleteConfirmId = $id;
    }

    public function requestDeleteOtp(int $id): void
    {
        $merchant = auth()->user();

        if (blank($merchant->phone)) {
            $this->dispatch('toast', type: 'error', message: 'Unable to send OTP. Please update your registered mobile number.');
            return;
        }

        $account = SourceAccountModel::where('user_id', auth()->id())->findOrFail($id);

        if ($account->is_primary) {
            $this->dispatch('toast', type: 'error', message: 'Primary account cannot be deleted.');
            return;
        }

        $this->sendActionOtp('delete', [
            'account_id' => $account->id,
        ]);

        $this->deleteConfirmId = null;
    }

    public function cancelDelete(): void
    {
        $this->deleteConfirmId = null;
    }

    public function deleteAccount(int $id): void
    {
        $account = SourceAccountModel::where('user_id', auth()->id())->findOrFail($id);

        if ($account->is_primary) {
            $this->dispatch('toast', type: 'error', message: 'Primary account cannot be deleted.');
            $this->deleteConfirmId = null;
            return;
        }

        $account->delete();
        $this->deleteConfirmId = null;
        $this->dispatch('toast', type: 'success', message: 'Source account removed successfully.');
    }

    public function verifyActionOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $sessionOtp = Session::get($this->otpSessionKey);
        $action = Session::get($this->otpActionKey);
        $userId = Session::get($this->otpUserIdKey);
        $payload = Session::get($this->otpPayloadKey, []);

        if (!$sessionOtp || !$action || !$userId || (int) $userId !== (int) auth()->id()) {
            $this->closeOtpModal();
            $this->forgetOtpSession();

            throw ValidationException::withMessages([
                'otp' => 'OTP expired or invalid. Please try again.',
            ]);
        }

        if ((string) $this->otp !== (string) $sessionOtp) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        if ($action === 'create') {
            $this->createAccountAfterOtp(is_array($payload) ? $payload : []);
        }

        if ($action === 'delete') {
            $this->deleteAccountAfterOtp(is_array($payload) ? $payload : []);
        }

        $this->forgetOtpSession();
        $this->closeOtpModal();
        $this->otp = null;
        $this->otpAction = null;
        $this->resendTimer = 0;
    }

    public function resendActionOtp(): void
    {
        if ((int) $this->resendTimer > 0) {
            return;
        }

        $action = Session::get($this->otpActionKey);
        if (!in_array($action, ['create', 'delete'], true)) {
            return;
        }

        $payload = Session::get($this->otpPayloadKey, []);
        $this->sendActionOtp($action, is_array($payload) ? $payload : []);
    }

    public function cancelOtpVerification(): void
    {
        $action = Session::get($this->otpActionKey, $this->otpAction);
        $payload = Session::get($this->otpPayloadKey, []);

        if ($action === 'create' && is_array($payload) && !empty($payload['document_path'])) {
            Storage::disk('public')->delete($payload['document_path']);
        }

        $this->forgetOtpSession();
        $this->closeOtpModal();
        $this->otp = null;
        $this->otpAction = null;
        $this->resendTimer = 0;
        $this->deleteConfirmId = null;
        $this->showAddModal = $action === 'create';
    }

    private function sendActionOtp(string $action, array $payload = []): void
    {
        $merchant = auth()->user();
        $mobile = $merchant->phone ?? null;

        if (blank($mobile)) {
            if ($action === 'create' && !empty($payload['document_path'])) {
                Storage::disk('public')->delete($payload['document_path']);
            }

            $this->dispatch('toast', type: 'error', message: 'Unable to send OTP. Please update your registered mobile number.');
            return;
        }

        $otp = random_int(100000, 999999);

        Session::put($this->otpSessionKey, (string) $otp);
        Session::put($this->otpActionKey, $action);
        Session::put($this->otpUserIdKey, $merchant->id);
        Session::put($this->otpPayloadKey, $payload);

        $sent = app(SMSService::class)->sendSMS($mobile, $otp);
        if ($sent === false) {
            if ($action === 'create' && !empty($payload['document_path'])) {
                Storage::disk('public')->delete($payload['document_path']);
            }

            $this->forgetOtpSession();
            $this->dispatch('toast', type: 'error', message: 'Unable to send OTP right now. Please try again.');
            return;
        }

        $this->otpAction = $action;
        $this->showAddModal = false;
        $this->showOTPModal = true;
        $this->otp = null;
        $this->resendTimer = $this->resendWait;
        $this->resetErrorBag('otp');

        if ($action === 'delete') {
            $this->deleteConfirmId = null;
        }

        $this->dispatch('toast', type: 'success', message: 'OTP sent successfully. Please verify to continue.');
    }

    private function createAccountAfterOtp(array $payload): void
    {
        if (empty($payload)) {
            $this->dispatch('toast', type: 'error', message: 'Source account details not found. Please try again.');
            return;
        }

        $merchant = auth()->user();

        $merchant->merchantSourceAccounts()->create([
            'account_number'      => $payload['account_number'] ?? null,
            'ifsc_code'           => $payload['ifsc_code'] ?? null,
            'account_holder_name' => $payload['account_holder_name'] ?? null,
            'bank_name'           => $payload['bank_name'] ?? null,
            'document_type'       => $payload['document_type'] ?? null,
            'document'            => $payload['document_path'] ?? null,
            'status'              => 'inactive',
        ]);

        $this->showAddModal = false;
        $this->resetFields();
        $this->dispatch('toast', type: 'success', message: 'Source account submitted successfully and is now inactive pending review.');
    }

    private function deleteAccountAfterOtp(array $payload): void
    {
        $accountId = $payload['account_id'] ?? null;

        if (blank($accountId)) {
            $this->dispatch('toast', type: 'error', message: 'Source account not found. Please try again.');
            return;
        }

        $this->deleteAccount((int) $accountId);
    }

    private function forgetOtpSession(): void
    {
        Session::forget($this->otpSessionKey);
        Session::forget($this->otpActionKey);
        Session::forget($this->otpUserIdKey);
        Session::forget($this->otpPayloadKey);
    }

    private function closeOtpModal(): void
    {
        $this->showOTPModal = false;
    }

    private function resetFields(): void
    {
        $this->account_number      = '';
        $this->ifsc_code           = '';
        $this->account_holder_name = '';
        $this->bank_name           = '';
        $this->document_type       = '';
        $this->document_file       = null;
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

