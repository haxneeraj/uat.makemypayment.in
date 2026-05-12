<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class PasswordResetComponent extends Component
{
    public function render()
    {
        return view('auth.password-reset-component')
            ->layout('layouts.auth')
            ->layoutData([
                'metaTitle' => 'Reset Password - M.M.P Fintech Payment Solution',
                'metaDescription' => 'Reset Password to your M.M.P Fintech Payment Solution account to access your dashboard and manage your payments.',           
            ]);
    }
}
