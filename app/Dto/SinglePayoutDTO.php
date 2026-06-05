<?php

namespace App\Dto;

class SinglePayoutDTO
{
    public ?string $merchantReferenceId;
    public string $accountHolder;
    public string $accountNumber;
    public string $ifscCode;
    public string $bankName;
    public string $branchName;
    public string $branchCode;
    public string $mobile;
    public ?string $email;
    public string $city;
    public ?string $state;
    public ?string $pincode;
    public string $beneficiaryAddress;
    public float $amount;
    public string $mode;
    public string $purpose;
    public ?string $remarks;
    public ?string $narration;
    public ?string $type;
    public ?string $initiatedFrom;
    // Required when type = 2
    public ?string $beneMode;
    public ?string $beneType;
    public ?string $beneBankId;


    public function __construct(
        string $accountHolder,
        string $accountNumber,
        string $ifscCode,
        string $bankName,
        string $branchName,
        string $branchCode,
        string $mobile,
        string $city,
        string $beneficiaryAddress,
        float $amount,
        string $mode,
        string $purpose,
        ?string $merchantReferenceId = null,
        ?string $email = null,
        ?string $state = null,
        ?string $pincode = null,
        ?string $remarks = null,
        ?string $narration = null,
        ?string $type = null,
        ?string $initiatedFrom = 'api',
        ?string $beneMode = null,
        ?string $beneType = null,
        ?string $beneBankId = null,
    ) {
        $this->merchantReferenceId = $merchantReferenceId;
        $this->accountHolder      = $accountHolder;
        $this->accountNumber      = $accountNumber;
        $this->ifscCode           = $ifscCode;
        $this->bankName           = $bankName;
        $this->branchName         = $branchName;
        $this->branchCode         = $branchCode;
        $this->mobile             = $mobile;
        $this->email              = $email;
        $this->city               = $city;
        $this->state              = $state;
        $this->pincode            = $pincode;
        $this->beneficiaryAddress = $beneficiaryAddress;
        $this->amount             = $amount;
        $this->mode               = $mode;
        $this->purpose            = $purpose;
        $this->remarks            = $remarks;
        $this->narration          = $narration;
        $this->type               = $type;
        $this->initiatedFrom      = $initiatedFrom;
        $this->beneMode           = $beneMode;
        $this->beneType           = $beneType;
        $this->beneBankId         = $beneBankId;
    }
}
