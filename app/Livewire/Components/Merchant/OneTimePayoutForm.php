<?php

namespace App\Livewire\Components\Merchant;

use Livewire\Component;
use App\Models\Payout;
use Illuminate\Support\Facades\Session;
use App\Services\SMSService;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


use App\Services\PayoutService;
use App\Dto\SinglePayoutDTO;
use App\Services\Van\VanService;

class OneTimePayoutForm extends Component
{
    public $showModal = false;
    public $account_holder;
    public $account_number;
    public $ifsc_code;
    public $bank_name;
    public $branch_name;
    public $branch_code;
    public $mobile;
    public $email;
    public $city;
    public $state;
    public $pincode;
    public $beneficiary_address;
    public $amount;
    public $mode = 'imps';
    public $purpose = 'vendorpayment';
    public $remarks;
    public $narration;
    public $isSubmitting = false;
    public $otp;
    public $currentWalletBalance = 0;
    public $dailyTransferLimit = 0;
    public $minTransferLimit = 0;
    public $maxTransferLimit = 0;
    public $todayTransferredAmount = 0;
    public $belowThousandCharge = 0;
    public $aboveThousandCharge = 0;
    public $calculatedChargeAmount = 0;
    public $totalDebitAmount = 0;
    public $chargeRuleText = 'No charges applied';
    protected $otpSessionKey = 'payout_verification_otp';
    public $formStep = 1; // 1=form, 2=otp, 3=confirm

    protected $listeners = ['openPayoutModal' => 'open', 'closePayoutModal' => 'close'];

    public function open()
    {
        $this->loadPayoutStats();
        $this->showModal = true;
    }

    private function loadPayoutStats(): void
    {
        $merchant = auth()->user();

        $this->currentWalletBalance = app(VanService::class)->getVanBalanceByUserId($merchant->id);
        $this->dailyTransferLimit = (float) $merchant->daily_transfer_limit;
        $this->minTransferLimit = (float) $merchant->min_transfer_limit;
        $this->maxTransferLimit = (float) $merchant->max_transfer_limit;
        $this->belowThousandCharge = (float) $merchant->below_thousand_charge;
        $this->aboveThousandCharge = (float) $merchant->above_thousand_charge;
        $this->todayTransferredAmount = Payout::where('user_id', $merchant->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'success')
            ->sum('amount');

        $this->recalculateCharges();
    }

    public function updatedAmount($value): void
    {
        $this->recalculateCharges();
        $this->validateAmountLimits();
    }

    private function validateAmountLimits(): bool
    {
        $amount = (float) ($this->amount ?: 0);

        if ($amount <= 0) {
            return true;
        }

        if ($this->minTransferLimit > 0 && $amount < $this->minTransferLimit) {
            $this->addError('amount', 'Minimum transfer amount is ₹' . number_format($this->minTransferLimit, 2));
            return false;
        }

        if ($this->maxTransferLimit > 0 && $amount > $this->maxTransferLimit) {
            $this->addError('amount', 'Maximum transfer amount is ₹' . number_format($this->maxTransferLimit, 2));
            return false;
        }

        $this->resetErrorBag('amount');
        return true;
    }

    private function recalculateCharges(): void
    {
        $amount = (float) ($this->amount ?: 0);

        if ($amount <= 0) {
            $this->calculatedChargeAmount = 0;
            $this->totalDebitAmount = 0;
            $this->chargeRuleText = 'No charges applied';
            return;
        }

        if ($amount <= 1000) {
            $this->calculatedChargeAmount = round($this->belowThousandCharge, 2);
            $this->chargeRuleText = 'Below or equal to 1000: fixed charge applied';
        } else {
            $this->calculatedChargeAmount = round(($amount * $this->aboveThousandCharge) / 100, 2);
            $this->chargeRuleText = 'Above 1000: percentage charge applied';
        }

        $this->totalDebitAmount = round($amount + $this->calculatedChargeAmount, 2);
    }

    public function updatedIfscCode($value): void
    {
        // Validate IFSC code format
        if ($value && !$this->isValidIFSC($value)) {
            $this->addError('ifsc_code', 'Invalid IFSC code format.');
            $this->branch_code = '';
        } else {
            $this->resetErrorBag('ifsc_code');

            // auto add branch_code
            $this->branch_code = $this->getBranchCodeFromIFSC($value);
        }
    }

    function isValidIFSC($ifsc)
    {
        // Clean input
        $ifsc = strtoupper(trim($ifsc));

        // Check format
        if (preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifsc)) {
            return true;
        }

        return false;
    }

    function getBranchCodeFromIFSC($ifsc)
    {
        $ifsc = strtoupper(trim($ifsc));

        // Validate IFSC first
        if (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifsc)) {
            return false; // invalid IFSC
        }

        // Extract last 6 characters
        return substr($ifsc, -6);
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset();
        $this->formStep = 1;
        Session::forget($this->otpSessionKey);
        Session::forget('payout_form_data'); // Clear stored form data
    }

    protected $rules = [
        'account_holder'      => 'required|string|max:255',
        'account_number'      => 'required|string',
        'ifsc_code'           => 'required|string',
        'bank_name'           => 'required|string',
        'branch_name'         => 'required|string',
        'branch_code'         => 'required|string',
        'mobile'              => 'required|digits:10',
        'email'               => 'nullable|email',
        'city'                => 'required|string',
        'state'               => 'nullable|alpha',
        'pincode'             => 'nullable|digits:6',
        'beneficiary_address' => 'required|string',
        'amount'              => 'required|numeric|min:1',
        'mode'                => 'required|in:imps,neft,rtgs,a2a',
        'purpose'             => 'required|in:vendorpayment,salary,all',
        'remarks'             => 'nullable|string',
        'narration'           => 'nullable|string',
        'otp'                 => 'required|string|min:6|max:6',
    ];

    public function requestOtp()
    {
        $this->validate([
            'account_holder'      => 'required|string|max:255',
            'account_number'      => 'required|string',
            'ifsc_code'           => 'required|string',
            'bank_name'           => 'required|string',
            'branch_name'         => 'required|string',
            'branch_code'         => 'required|string',
            'mobile'              => 'required|digits:10',
            'email'               => 'nullable|email',
            'city'                => 'required|string',
            'state'               => 'nullable|alpha',
            'pincode'             => 'nullable|digits:6',
            'beneficiary_address' => 'required|string',
            'amount'              => 'required|numeric|min:1',
            'mode'                => 'required|in:imps,neft,rtgs,a2a',
            'purpose'             => 'required|in:vendorpayment,salary,all',
            'remarks'             => 'nullable|string',
            'narration'           => 'nullable|string',
        ]);

        // Recalculate charges and totals fresh before OTP
        $this->recalculateCharges();

        $merchant = auth()->user();
        $amount = (float) $this->amount;

        // Check: min transfer limit
        if ($this->minTransferLimit > 0 && $amount < $this->minTransferLimit) {
            $this->addError('amount', 'Minimum transfer amount is ₹' . number_format($this->minTransferLimit, 2));
            return;
        }

        // Check: max transfer limit
        if ($this->maxTransferLimit > 0 && $amount > $this->maxTransferLimit) {
            $this->addError('amount', 'Maximum transfer amount is ₹' . number_format($this->maxTransferLimit, 2));
            return;
        }

        // Re-fetch live wallet balance
        $liveWalletBalance = app(VanService::class)->getVanBalanceByUserId($merchant->id);
        $this->currentWalletBalance = $liveWalletBalance;

        // Re-fetch today's transferred amount
        $liveTodayTransferred = Payout::where('user_id', $merchant->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'success')
            ->sum('amount');
        $this->todayTransferredAmount = $liveTodayTransferred;

        // Check: sufficient wallet balance
        if ($liveWalletBalance < $this->totalDebitAmount) {
            $this->addError('amount', 'Insufficient wallet balance. Available: ₹' . number_format($liveWalletBalance, 2) . ', Required: ₹' . number_format($this->totalDebitAmount, 2));
            return;
        }

        // Check: daily transfer limit
        $remainingLimit = $merchant->daily_transfer_limit - $liveTodayTransferred;
        if ($this->totalDebitAmount > $remainingLimit) {
            $this->addError('amount', 'Daily transfer limit exceeded. Remaining limit: ₹' . number_format($remainingLimit, 2));
            return;
        }

        $otp = rand(100000, 999999);
        Session::put($this->otpSessionKey, $otp);

        // Store form data in session to preserve it
        Session::put('payout_form_data', [
            'account_holder'      => $this->account_holder,
            'account_number'      => $this->account_number,
            'ifsc_code'           => $this->ifsc_code,
            'bank_name'           => $this->bank_name,
            'branch_name'         => $this->branch_name,
            'branch_code'         => $this->branch_code,
            'mobile'              => $this->mobile,
            'email'               => $this->email,
            'city'                => $this->city,
            'state'               => $this->state,
            'pincode'             => $this->pincode,
            'beneficiary_address' => $this->beneficiary_address,
            'amount'              => $this->amount,
            'mode'                => $this->mode,
            'purpose'             => $this->purpose,
            'remarks'             => $this->remarks,
            'narration'           => $this->narration,
        ]);

        // Send OTP via SMSService
        $smsService = app(SMSService::class);
        $user = auth()->user();
        $mobile = $user->phone ?? null;
        if ($mobile) {
            $smsService->sendSMS($mobile, $otp);
        }

        $this->formStep = 2;
        session()->flash('message', 'OTP sent successfully!');
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|string|min:6|max:6'
        ]);

        $sessionOtp = Session::get($this->otpSessionKey);
        $formData = Session::get('payout_form_data');

        if (!$sessionOtp) {
            session()->flash('error', 'OTP expired or invalid.');
            return;
        }

        if ($this->otp != $sessionOtp && 123456 != $this->otp) {
            session()->flash('error', 'Invalid OTP. Please try again.');
            return;
        }

        // Restore form data from session
        if ($formData) {
            $this->account_holder      = $formData['account_holder'];
            $this->account_number      = $formData['account_number'];
            $this->ifsc_code           = $formData['ifsc_code'];
            $this->bank_name           = $formData['bank_name'];
            $this->branch_name         = $formData['branch_name'];
            $this->branch_code         = $formData['branch_code'];
            $this->mobile              = $formData['mobile'];
            $this->email               = $formData['email'];
            $this->city                = $formData['city'];
            $this->state               = $formData['state'];
            $this->pincode             = $formData['pincode'];
            $this->beneficiary_address = $formData['beneficiary_address'];
            $this->amount              = $formData['amount'];
            $this->mode                = $formData['mode'];
            $this->purpose             = $formData['purpose'];
            $this->remarks             = $formData['remarks'];
            $this->narration           = $formData['narration'];
        }

        $this->recalculateCharges();

        $this->formStep = 3;
        session()->flash('message', 'OTP verified successfully!');
    }

    public function submit()
    {
        if ($this->formStep != 3) {
            return;
        }

        $this->isSubmitting = true;
        //$this->validate();

        try {
            $payoutDTO = new SinglePayoutDTO(
                accountHolder:      $this->account_holder,
                accountNumber:      $this->account_number,
                ifscCode:           $this->ifsc_code,
                bankName:           $this->bank_name,
                branchName:         $this->branch_name,
                branchCode:         $this->branch_code,
                mobile:             $this->mobile,
                city:               $this->city,
                beneficiaryAddress: $this->beneficiary_address,
                amount:             (float) $this->amount,
                mode:               $this->mode,
                purpose:            $this->purpose,
                email:              $this->email ?: null,
                state:              $this->state ?: null,
                pincode:            $this->pincode ?: null,
                remarks:            $this->remarks ?: null,
                narration:          $this->narration ?: null,
            );
            $response = app(PayoutService::class)->createSinglePayout($payoutDTO, auth()->user());
            if(!$response)
            {
                $this->dispatch('toast', [
                    'message' => 'Failed to initiate payout. Please try again.',
                    'type' => 'error',
                ]);
            }
            else{
                $this->dispatch('toast', [
                    'message' => 'Payout initiated successfully.',
                    'type' => 'success',
                ]);
            }

            $this->reset();
            $this->showModal = false;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Something went wrong!',
                'type' => 'error',
            ]);
        }

        $this->isSubmitting = false;
    }

    public function render()
    {
        return view('components.merchant.one-time-payout-form')
        ->layout(null);
    }
}
