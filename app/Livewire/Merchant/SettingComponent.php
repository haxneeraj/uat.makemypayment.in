<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;

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

        session()->flash('success', 'Password updated successfully.');
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

        if ($merchantCallback) {
            if ($merchantCallback->status === 'pending') {
                return session()->flash('error', 'Your previous request is still pending. Please wait for admin approval.');
            }

            $merchantCallback->update($this->only(['ip', 'webhook_url', 'webhook_secret']) + ['status' => 'pending']);
        } else {
            auth()->user()->merchantCallbackAndIP()->create(
                $this->only(['ip', 'webhook_url', 'webhook_secret']) + ['status' => 'pending']
            );
        }

        session()->flash('success', 'Settings updated successfully. Waiting for admin approval.');
    }

    public function generateAPICredentials()
    {
        if($this->has_api_credentials && !$this->showAPIpopup)
        {
            $this->showAPIpopup = true;
            return;
        }

        $this->showAPIpopup = false;

        $merchant = auth()->user();
        $this->api_key = $this->generateAPIKey();

        // secret 
        do {
            $secret = $this->generateAPISecret();
        } while (User::where('api_secret', $secret)->exists());

        $this->api_secret = $this->generateAPISecret();
        $merchant->api_key = $this->api_key;
        $merchant->api_secret = $secret;
        $merchant->save();

        $type = $this->has_api_credentials ? 'regenerated' : 'generated';
        session()->flash('success', "API credentials have been {$type} successfully.");

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
