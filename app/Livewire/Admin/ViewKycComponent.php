<?php

namespace App\Livewire\Admin;

use App\Events\KycApproved;
use App\Events\KycRejected;
use App\Models\User;
use App\Models\MerchantKyc;
use App\Models\MerchantVirtualAccount;
use App\Models\SourceAccount;
use Livewire\Component;

use Carbon\Carbon;

use App\Services\BankServices\CastlerService;
use App\Services\Van\VanService;
use Illuminate\Support\Facades\DB;

class ViewKycComponent extends Component
{
    public User $merchant;
    public $kyc_status;
    public $kyc_remark;

    public MerchantKyc $kyc;

    public function mount($merchant_id)
    {
        $this->merchant = User::where('merchant_id', $merchant_id)->firstOrFail();
        $this->kyc_status = $this->merchant->kyc_status;

        $this->kyc = MerchantKyc::where('user_id', $this->merchant->id)->firstOrFail();
        
        $this->kyc_remark = $this->kyc->kyc_remark;
    }

    public function updateKyc($status)
    {
        # Begin DB Transaction
        DB::beginTransaction();

        try
        {
            if($status === 'verified')
            {
                # check for already has a virtual account
                if(!MerchantVirtualAccount::where('user_id', $this->merchant->id)->exists())
                {
                    # Create Virtual Account
                    $van = app(VanService::class)->createVan([
                        'user_id' => $this->merchant->id,
                        'account_holder' => $this->kyc->account_holder,
                        'purpose' => 'Merchant Onboarding',
                        'validity' => 365,
                        'mobile' => $this->merchant->phone,
                    ]);
                }
                else
                {
                    $van = app(VanService::class)->getVanByUserId($this->merchant->id);
                }

                # Create Primary Source Account from KYC Bank Details (if not already exists)
                if(!SourceAccount::where('user_id', $this->merchant->id)->where('account_number', $this->kyc->account_number)->exists())
                {
                    # Mark any existing primary source accounts as non-primary
                    SourceAccount::where('user_id', $this->merchant->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);

                    SourceAccount::create([
                        'user_id'              => $this->merchant->id,
                        'account_number'       => $this->kyc->account_number,
                        'ifsc_code'            => $this->kyc->ifsc_code,
                        'account_holder_name'  => $this->kyc->account_holder,
                        'bank_name'            => $this->kyc->bank_name,
                        'is_primary'           => true,
                        'status'               => 'active',
                    ]);
                }
                else
                {
                    # If source account already exists, ensure it's marked as primary and active
                    $sourceAccount = SourceAccount::where('user_id', $this->merchant->id)->where('account_number', $this->kyc->account_number)->first();
                    $sourceAccount->update([
                        'is_primary' => true,
                        'status' => 'active',
                    ]);
                }

                #Send Onboard Mail
            }


            if ($status === 'rejected') {
                $this->validate(
                    ['kyc_remark' => 'required'],
                    ['kyc_remark.required' => 'A remark is required when rejecting KYC.']
                );
            }

            $this->merchant->kyc_status = $status;
            $this->merchant->van_status = $status === 'verified' ? 'verified' : 'pending';
            $this->kyc->kyc_remark = $this->kyc_remark;
            $this->merchant->save();
            $this->kyc->save();


            # Commit DB Transaction
            DB::commit();

            if ($status === 'verified') {
                event(new KycApproved($this->merchant->fresh()));
            }

            if ($status === 'rejected') {
                event(new KycRejected($this->merchant->fresh(), (string) $this->kyc->kyc_remark));
            }

            # Dispatch Swal Success Event
            $this->dispatch('swal:success', [
                'title' => 'Success',
                'text' => 'KYC status updated successfully!',
                'icon' => 'success',
            ]);
        }
        catch(\Exception $e)
        {
            # Rollback DB Transaction
            DB::rollBack();

            \Log::error([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            # Dispatch Swal Error Event
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Failed to update KYC status. Please try again.',
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('admin.view-kyc-component')
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'pending-kyc',
            'pageTitle' => 'View Merchant KYC',
            'metaTitle' => 'View KYC Details - MMP Fintech',
            'metaDescription' => 'Review and approve or reject merchant KYC details.',
        ]);
    }
}
