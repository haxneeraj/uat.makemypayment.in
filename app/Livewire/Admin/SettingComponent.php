<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use \Illuminate\Support\Facades\Hash;

class SettingComponent extends Component
{
    public $activeTab = 1;
    public $current_password;
    public $password;
    public $password_confirmation;

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

    public function render()
    {
        return view('admin.setting-component')
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'settings',
            'pageTitle' => 'Settings',
            'metaTitle' => 'Settings - MMP Fintech',
            'metaDescription' => 'Settings',
        ]);
    }
}
