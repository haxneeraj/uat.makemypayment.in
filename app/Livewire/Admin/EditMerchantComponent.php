<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditMerchantComponent extends Component
{
    public $merchant_id;
    public $merchant;
    public $daily_transfer_limit;
    public $below_thousand_charge;
    public $above_thousand_charge;
    public $status;

    // KYC Details
    public $business_type;
    public $business_name;
    public $business_address;
    public $state;
    public $city;
    public $pin_code;
    public $website_url;
    public $gstin;
    public $pan;
    public $cin_number;
    public $kyc_status;

    protected $rules = [
        'daily_transfer_limit' => 'required|numeric|min:0',
        'below_thousand_charge' => 'required|numeric|min:0|max:100',
        'above_thousand_charge' => 'required|numeric|min:0|max:100',
        'status' => 'required|in:active,inactive,suspended',

        // KYC rules
        'business_type' => 'required|string',
        'business_name' => 'required|string',
        'business_address' => 'required|string',
        'state' => 'required|string',
        'city' => 'required|string',
        'pin_code' => 'required|string',
        'gstin' => 'required|string',
        'pan' => 'required|string',
        'kyc_status' => 'required|in:pending,verified,rejected,submitted',
    ];

    public function mount($merchant_id)
    {
        $this->merchant_id = $merchant_id;
        $this->merchant = User::where('merchant_id', $merchant_id)
            ->with(['merchantKyc', 'merchantVirtualAccount'])
            ->firstOrFail();

        // Load transaction settings
        $this->daily_transfer_limit = $this->merchant->daily_transfer_limit;
        $this->below_thousand_charge = $this->merchant->below_thousand_charge;
        $this->above_thousand_charge = $this->merchant->above_thousand_charge;
        $this->status = $this->merchant->status;
        $this->kyc_status = $this->merchant->kyc_status;

        // Load KYC details
        if ($this->merchant->merchantKyc) {
            $kyc = $this->merchant->merchantKyc;
            $this->business_type = $kyc->business_type;
            $this->business_name = $kyc->business_name;
            $this->business_address = $kyc->business_address;
            $this->state = $kyc->state;
            $this->city = $kyc->city;
            $this->pin_code = $kyc->pin_code;
            $this->website_url = $kyc->website_url;
            $this->gstin = $kyc->gstin;
            $this->pan = $kyc->pan;
            $this->cin_number = $kyc->cin_number;
        }
    }

    public function updateMerchant()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update merchant settings
            $this->merchant->update([
                'daily_transfer_limit' => $this->daily_transfer_limit,
                'below_thousand_charge' => $this->below_thousand_charge,
                'above_thousand_charge' => $this->above_thousand_charge,
                'status' => $this->status,
                'kyc_status' => $this->kyc_status
            ]);

            // Update KYC details
            $this->merchant->merchantKyc()->update([
                'business_type' => $this->business_type,
                'business_name' => $this->business_name,
                'business_address' => $this->business_address,
                'state' => $this->state,
                'city' => $this->city,
                'pin_code' => $this->pin_code,
                'website_url' => $this->website_url,
                'gstin' => $this->gstin,
                'pan' => $this->pan,
                'cin_number' => $this->cin_number,
            ]);

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Merchant details updated successfully.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to update merchant details.',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('admin.edit-merchant-component')
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'merchants',
            'pageTitle' => 'Edit Merchant: ' . $this->merchant->full_name,
            'metaTitle' => 'Edit Merchant Details - MMP Fintech',
            'metaDescription' => 'Edit merchant profile and transaction limits.',
        ]);
    }
}
