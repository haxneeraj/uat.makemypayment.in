<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantKyc extends Model
{
    protected $table = 'merchant_kycs';

   protected $fillable = [
        'user_id',

        // Common fields
        'category',
        'sub_category',
        'business_type',
        'business_name',
        'business_address',
        'state',
        'city',
        'pin_code',
        'country',

        'website_url',
        'apk_link',
       
        'full_name',
        'email',
        'mobile',

        // PAN
        'pan',
        'pan_front',

        // Company PAN
        'company_pan',
        'company_pan_front',

        // CIN
        'cin_number',
        'cin_front',

        // Aadhaar
        'aadhaar',
        'aadhaar_front',
        'aadhaar_back',

        // Bank Docs
        'cancelled_cheque',
        'bank_statement',

        // Bank Details
        'bank_name',
        'branch',
        'account_holder',
        'account_number',
        'ifsc_code',
        'account_type',

        // Proprietor & incorporation docs
        'proprietor_photo',
        'registration_certificate',

        // Address Proof
        'address_proof',

        // GST
        'gstin',
        'gst_certificate',

        // Business Docs
        'document_aoa',
        'document_moi',
        'document_coi',

        // Step
        'step',

        // KYC Remark
        'kyc_remark',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
