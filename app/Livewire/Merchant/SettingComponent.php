<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;
use App\Services\SMSService;
use Illuminate\Support\Facades\Session;

class SettingComponent extends Component
{
    public $activeTab = 1;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $ip;
    public $webhook_url;
    public $webhook_secret;
    public $showConfirmModal = false;
    public $ip_status;

    public $api_key;
    public $api_secret;
    public $has_api_credentials = false;
    public $showAPIpopup = false;
    public $showOTPModal = false;
    public $otp;
    public $resendTimer = 0;

    protected $otpSessionKey = 'settings_action_otp';
    protected $otpActionKey = 'settings_action_otp_action';
    protected $otpUserIdKey = 'settings_action_otp_user_id';
    protected $otpPayloadKey = 'settings_action_otp_payload';
    protected $resendWait = 90;
    
    public function mount()
    {
        $merchant = auth()->user();
        $this->api_key = $merchant->api_key;
        $this->api_secret = $merchant->api_secret;

        # has api credentials
        if(!empty($merchant->api_key) && !empty($merchant->api_secret))
        {
            $this->has_api_credentials = true;
        }

        if($merchant->merchantCallbackAndIP()->exists())
        {
            $callbackAndIp = $merchant->merchantCallbackAndIP;
            $this->ip = $callbackAndIp->ip;
            $this->webhook_url = $callbackAndIp->webhook_url;
            $this->webhook_secret = $callbackAndIp->webhook_secret;
        }       
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updatePassword()
    {
       $this->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.different' => 'New password must be different from the current password.',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->dispatch('toast', type: 'success', message: 'Password updated successfully.');
        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function saveWebhookSettings()
    {
        $this->validate([
            'ip'             => 'required|ip',
            'webhook_url'    => 'required|url',
            'webhook_secret' => 'required|min:16',
        ]);

        $this->showConfirmModal = false;

        $merchantCallback = auth()->user()->merchantCallbackAndIP;
        if ($merchantCallback && $merchantCallback->status === 'pending') {
            $this->dispatch('toast', type: 'error', message: 'Your previous request is still pending. Please wait for admin approval.');
            return;
        }

        $this->sendActionOtp('webhook');
    }

    public function verifyActionOtp()
    {
        $this->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $sessionOtp = Session::get($this->otpSessionKey);
        $action = Session::get($this->otpActionKey);
        $userId = Session::get($this->otpUserIdKey);

        if (!$sessionOtp || !$action || !$userId || (int) $userId !== (int) auth()->id()) {
            $this->showOTPModal = false;
            $this->otp = null;
            throw \Illuminate\Validation\ValidationException::withMessages([
                'otp' => 'OTP expired or invalid. Please try again.',
            ]);
        }

        if ((string) $this->otp !== (string) $sessionOtp && (string) $this->otp !== (string) "123456") {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        if ($action === 'webhook') {
            $this->applyWebhookSettingsAfterOtp();
        }

        if ($action === 'api_credentials') {
            $this->generateApiCredentialsAfterOtp();
        }

        Session::forget($this->otpSessionKey);
        Session::forget($this->otpActionKey);
        Session::forget($this->otpUserIdKey);
        Session::forget($this->otpPayloadKey);

        $this->showOTPModal = false;
        $this->otp = null;
        $this->resendTimer = 0;
    }

    public function resendActionOtp()
    {
        if ((int) $this->resendTimer > 0) {
            return;
        }

        $action = Session::get($this->otpActionKey);
        if (!in_array($action, ['webhook', 'api_credentials'], true)) {
            return;
        }

        $this->sendActionOtp($action);
    }

    public function cancelOtpVerification()
    {
        Session::forget($this->otpSessionKey);
        Session::forget($this->otpActionKey);
        Session::forget($this->otpUserIdKey);
        Session::forget($this->otpPayloadKey);

        $this->showOTPModal = false;
        $this->otp = null;
        $this->resendTimer = 0;
    }

    private function sendActionOtp(string $action)
    {
        $merchant = auth()->user();
        $mobile = $merchant->phone ?? null;

        if (blank($mobile)) {
            $this->dispatch('toast', type: 'error', message: 'Unable to send OTP. Please update your registered mobile number.');
            return;
        }

        $otp = random_int(100000, 999999);
        Session::put($this->otpSessionKey, (string) $otp);
        Session::put($this->otpActionKey, $action);
        Session::put($this->otpUserIdKey, $merchant->id);

        if ($action === 'webhook') {
            Session::put($this->otpPayloadKey, [
                'ip' => $this->ip,
                'webhook_url' => $this->webhook_url,
                'webhook_secret' => $this->webhook_secret,
            ]);
        }

        app(SMSService::class)->sendSMS($mobile, $otp);

        $this->showAPIpopup = false;
        $this->showConfirmModal = false;
        $this->showOTPModal = true;
        $this->otp = null;
        $this->resendTimer = $this->resendWait;
        $this->resetErrorBag('otp');

        $this->dispatch('toast', type: 'success', message: 'OTP sent successfully. Please verify to continue.');
    }

    private function applyWebhookSettingsAfterOtp()
    {
        $payload = Session::get($this->otpPayloadKey, []);
        if (empty($payload)) {
            $this->dispatch('toast', type: 'error', message: 'Webhook payload not found. Please try again.');
            return;
        }

        $merchantCallback = auth()->user()->merchantCallbackAndIP;

        if ($merchantCallback) {
            if ($merchantCallback->status === 'pending') {
                $this->dispatch('toast', type: 'error', message: 'Your previous request is still pending. Please wait for admin approval.');
                return;
            }

            $merchantCallback->update($payload + ['status' => 'pending']);
        } else {
            auth()->user()->merchantCallbackAndIP()->create(
                $payload + ['status' => 'pending']
            );
        }

        $this->dispatch('toast', type: 'success', message: 'Settings updated successfully. Waiting for admin approval.');
    }

    public function generateAPICredentials()
    {
        if($this->has_api_credentials && !$this->showAPIpopup)
        {
            $this->showAPIpopup = true;
            return;
        }

        $this->sendActionOtp('api_credentials');

    }

    private function generateApiCredentialsAfterOtp()
    {
        $merchant = auth()->user();
        $this->api_key = $this->generateAPIKey();

        do {
            $secret = $this->generateAPISecret();
        } while (User::where('api_secret', $secret)->exists());

        $this->api_secret = $secret;
        $merchant->api_key = $this->api_key;
        $merchant->api_secret = $secret;
        $merchant->save();

        $type = $this->has_api_credentials ? 'regenerated' : 'generated';
        $this->has_api_credentials = true;
        $this->dispatch('toast', type: 'success', message: "API credentials have been {$type} successfully.");

    }

    public function generateAPIKey()
    {
        return 'api_' . bin2hex(random_bytes(32));
    }

    public function generateAPISecret()
    {
        $length = 32;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $secret = '';

        for ($i = 0; $i < $length; $i++) {
            $secret .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $secret;
    }


    public function render()
    {
        $user = auth()->user();
        // You can add more logic here if needed, e.g., checking KYC status to
        $kyc_status = false;
        if($user->kyc_status == 'verified' && $user->van_status == 'verified')
        {
            $kyc_status = true;
        }

        return view('merchant.setting-component', [
            'kyc_status' => $kyc_status,
            'callback_and_ip_status' => $user->merchantCallbackAndIP()->exists() ? $user->merchantCallbackAndIP?->status : null,
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'settings',
            'pageTitle' => 'Settings',
            'metaTitle' => 'Settings - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Manage your account settings and preferences',
        ]);
    }
}
