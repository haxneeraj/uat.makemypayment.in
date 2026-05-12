<?php

namespace App\Livewire\Site;

use Livewire\Component;

use App\Models\Enquiry;

class HomeComponent extends Component
{
    public $business_name;
    public $full_name;
    public $email;
    public $phone;
    public $message;
    public $type;

    protected $rules = [
        'business_name' => 'required|string|max:255',
        'full_name'     => 'required|string|max:255',
        'email'         => 'required|email|max:255',
        'phone'         => 'required|string|max:20',
        'message'       => 'required|string',
        'type'          => 'required|in:support_request,onboarding_request,merchant_inquiry,registration_request,partnership_request,feedback_suggestion',
    ];

    protected $messages = [
        'business_name.required' => 'Business name is required.',
        'full_name.required'     => 'Full name is required.',
        'email.required'         => 'Email is required.',
        'phone.required'         => 'Phone number is required.',
        'message.required'       => 'Message cannot be empty.',
        'type.required'          => 'Please select a valid enquiry type.',
    ];

    public function submit()
    {
        // Create enquiry using ContactService
        Enquiry::create($this->validate());

        // Reset form fields
        $this->reset();

        // Show success message
        session()->flash('success', 'Enquiry submitted successfully!');
    }

    public function render()
    {
        return view('site.home-component')
        ->layout('layouts.site')
        ->layoutData([
            'metaTitle' => 'Make My Payment - Secure Online Fintech Payment Solutions',
            'metaDescription' => 'Make My Payment offers secure, fast, and reliable fintech payment solutions for businesses and individuals. Experience seamless online transactions, bill payments, and financial services with advanced security and support.',
            'metaKeywords' => 'payment gateway, fintech, online payments, secure transactions, bill payments'
        ]);
    }
}
