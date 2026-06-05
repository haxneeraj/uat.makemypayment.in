<?php

namespace App\Livewire\Merchant;

use App\Models\MerchantKyc;
use App\Models\SourceAccount;
use App\Models\State;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class KycComponent extends Component
{
    use WithFileUploads;

    // ---------- Internal state ----------
    protected $user;
    protected $kyc;

    // category and subcategory
    public $category_id;
    public $subcategory_id;

    // ---------- Wizard state ----------
    public $step = 1;
    public $successMessage;
    public $agreeTerms = false;
    public $kyc_remark;

    // ---------- Basic details ----------
    public $website_url;
    public $apk_link;
    public $full_name;
    public $email;
    public $mobile;

    // ---------- KYC identifiers ----------
    public $pan;
    public $company_pan;
    public $cin_number;
    public $aadhaar;
    public $gstin;

    // ---------- Business details ----------
    public $business_type;
    public $business_name;
    public $business_address;
    public $state;
    public $city;
    public $pin_code;
    public $country = "India";

    // ---------- Bank details ----------
    public $bank_name;
    public $branch;
    public $account_type;
    public $account_holder;
    public $account_number;
    public $ifsc_code;

    // ---------- Document files ----------
    public $pan_front;
    public $company_pan_front;
    public $cin_front;
    public $aadhaar_front;
    public $aadhaar_back;
    public $cancelled_cheque;
    public $bank_statement;
    public $proprietor_photo;
    public $registration_certificate;
    public $address_proof;
    public $gst_certificate;
    public $document_aoa;
    public $document_moi;
    public $document_coi;

    // ---------- Document preview URLs ----------
    public $pan_front_url;
    public $company_pan_front_url;
    public $cin_front_url;
    public $aadhaar_front_url;
    public $aadhaar_back_url;
    public $cancelled_cheque_url;
    public $bank_statement_url;
    public $proprietor_photo_url;
    public $registration_certificate_url;
    public $address_proof_url;
    public $gst_certificate_url;
    public $document_aoa_url;
    public $document_moi_url;
    public $document_coi_url;

    // ---------- Lifecycle ----------
    public function mount()
    {
        # Set Authenticated User
        $this->user = auth()->user();

        # check for kyc status if submitted or verified then redirect to kyc status
        if($this->user->kyc_status == 'submitted' || $this->user->kyc_status == 'verified')
        {
            return redirect()->route('merchant.kyc.status');
        }

        # Set Kyc
        $this->setKyc();
    }

    public function setKyc()
    {
        $this->kyc = MerchantKyc::where('user_id', auth()->id())->exists() ? MerchantKyc::where('user_id', auth()->id())->first() : null;

        if(!blank($this->kyc))
        {
            $this->step = $this->kyc->step;
            $this->kyc_remark = $this->kyc->kyc_remark;

            $this->website_url = $this->kyc->website_url;
            $this->apk_link = $this->kyc->apk_link;

            // Individual fields
            $this->full_name = $this->kyc->full_name;
            $this->email = $this->kyc->email;
            $this->mobile = $this->kyc->mobile;
            $this->pan = $this->kyc->pan;
            $this->company_pan = $this->kyc->company_pan;
            $this->cin_number = $this->kyc->cin_number;
            $this->aadhaar = $this->kyc->aadhaar;
            $this->gstin = $this->kyc->gstin;

            // $bank Details
            $this->bank_name = $this->kyc->bank_name;
            $this->branch = $this->kyc->branch;
            $this->account_type = $this->kyc->account_type;
            $this->account_holder = $this->kyc->account_holder;
            $this->account_number = $this->kyc->account_number;
            $this->ifsc_code = $this->kyc->ifsc_code;

            $this->business_type = $this->kyc->business_type;
            $this->business_name = $this->kyc->business_name;
            $this->business_address = $this->kyc->business_address;
            $this->state = $this->kyc->state;
            $this->city = $this->kyc->city;
            $this->pin_code = $this->kyc->pin_code;
            $this->country = $this->kyc->country;

            // File URLs - assign URLs for existing files
            $docs = [
                'pan_front', 'company_pan_front', 'cin_front', 'aadhaar_front', 'aadhaar_back', 'cancelled_cheque',
                'bank_statement', 'proprietor_photo', 'registration_certificate',
                'address_proof', 'gst_certificate', 'document_aoa', 'document_moi', 'document_coi'
            ];

            $basePath = 'kyc_docs/'.auth()->user()->merchant_id;

            foreach ($docs as $doc) {
                $filePath = $basePath . '/' . $this->kyc->$doc;

                $this->{$doc . '_url'} = ($this->kyc->$doc && Storage::disk('public')->exists($filePath))
                    ? asset('storage/' . $filePath)
                    : null;
            }

            // Reverse-lookup category_id and subcategory_id from saved names
            if (!blank($this->kyc->category)) {
                $cat = \App\Models\Category::where('name', $this->kyc->category)->first();
                $this->category_id = $cat?->id;
            }
            if (!blank($this->kyc->sub_category)) {
                $subcat = \App\Models\SubCategory::where('name', $this->kyc->sub_category)->first();
                $this->subcategory_id = $subcat?->id;
            }
        }
    }

    // ---------- Navigation ----------
    public function previousStep()
    {
        # Set Kyc
        $this->setKyc();

        # Check if KYC exists and step is greater than 1
        if($this->kyc && $this->step > 1)
        {
            $this->step--;
            $this->kyc->step = $this->step;
            $this->kyc->save();
        }
    }

    // ---------- Live validations ----------
    public function updatedBusinessType($value)
    {
        if ($value === 'proprietor') {
            $this->resetErrorBag('cin_number');
        }
    }

    public function updatedPan($value)
    {
        $this->pan = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $value)), 0, 10);

        if (blank($this->pan)) {
            $this->resetErrorBag('pan');
            $this->validateLiveGstinPanMatch();
            return;
        }

        if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $this->pan)) {
            $this->addError('pan', 'PAN format should be like ABCDE1234F.');
        } else {
            $this->resetErrorBag('pan');
        }

        $this->validateLiveGstinPanMatch();
    }

    public function updatedCompanyPan($value)
    {
        $this->company_pan = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $value)), 0, 10);

        if (blank($this->company_pan)) {
            $this->resetErrorBag('company_pan');
            $this->validateLiveGstinPanMatch();
            return;
        }

        if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $this->company_pan)) {
            $this->addError('company_pan', 'Company PAN format should be like ABCDE1234F.');
        } else {
            $this->resetErrorBag('company_pan');
        }

        $this->validateLiveGstinPanMatch();
    }

    public function updatedGstin($value)
    {
        $this->gstin = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $value)), 0, 15);

        if (blank($this->gstin)) {
            $this->resetErrorBag('gstin');
            return;
        }

        if (!preg_match('/^[0-9A-Z]{15}$/', $this->gstin)) {
            $this->addError('gstin', 'GSTIN must be 15 alphanumeric characters.');
            return;
        }

        $this->validateLiveGstinPanMatch();
    }

    public function updatedAadhaar($value)
    {
        $this->aadhaar = substr(preg_replace('/\D/', '', (string) $value), 0, 12);

        if (blank($this->aadhaar)) {
            $this->resetErrorBag('aadhaar');
            return;
        }

        if (!preg_match('/^\d{12}$/', $this->aadhaar)) {
            $this->addError('aadhaar', 'Aadhaar must be 12 digits only.');
        } else {
            $this->resetErrorBag('aadhaar');
        }
    }

    public function updatedCinNumber($value)
    {
        $this->cin_number = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $value)), 0, 21);

        if ($this->business_type === 'proprietor' || blank($this->cin_number)) {
            $this->resetErrorBag('cin_number');
            return;
        }

        if (!preg_match('/^[A-Z0-9]{1,21}$/', $this->cin_number)) {
            $this->addError('cin_number', 'CIN must be alphanumeric only and max 21 characters.');
        } else {
            $this->resetErrorBag('cin_number');
        }
    }

    private function validateLiveGstinPanMatch()
    {
        if (!preg_match('/^[0-9A-Z]{15}$/', (string) $this->gstin)) {
            return;
        }

        // For non-proprietor, GSTIN must embed company_pan; for proprietor, use personal pan
        $panToMatch = ($this->business_type !== 'proprietor' && !blank($this->company_pan))
            ? $this->company_pan
            : $this->pan;

        if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', (string) $panToMatch)) {
            return;
        }

        $gstPan = substr((string) $this->gstin, 2, 10);
        if ($gstPan !== (string) $panToMatch) {
            $panLabel = ($this->business_type !== 'proprietor') ? 'Company PAN' : 'PAN';
            $this->addError('gstin', "GSTIN must include the same {$panLabel} (3rd to 12th characters).");
        } else {
            $this->resetErrorBag('gstin');
        }
    }

    // ---------- Step submissions ----------
    public function submitStepOne()
    {
        # validate the request
        $this->validate([
            'business_type'  => 'required|in:proprietor,private_limited,llp,partnership,ngo/trust,other',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
        ], [
            'business_type.required'   => 'Business type is required.',
            'business_type.in'         => 'Please select a valid business type.',
            'category_id.required'     => 'Please select a category.',
            'category_id.exists'       => 'Selected category is invalid.',
            'subcategory_id.required'  => 'Please select a sub-category.',
            'subcategory_id.exists'    => 'Selected sub-category is invalid.',
        ]);

        # Resolve names from IDs
        $category    = \App\Models\Category::find($this->category_id);
        $subCategory = \App\Models\SubCategory::find($this->subcategory_id);

        # Get the authenticated user
        $user = auth()->user();

        # Create or update the KYC record
        $kyc = MerchantKyc::updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_type' => $this->business_type,
                'category'      => $category->name,
                'sub_category'  => $subCategory->name,
                'step'          => 2,
            ]
        );

        #updating daily transaction limit based on business type
        $user->daily_transfer_limit = $this->business_type === 'private_limited' ? 5000000 : 2500000;
        $user->save();

        # move to next step
        $this->step = 2;

        # Set Kyc
        $this->setKyc();
    }

    public function submitStepTwo()
    {
        # Validate the request
        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:15',
            'website_url'  => 'required_without:apk_link|nullable|url',
            'apk_link'     => 'required_without:website_url|nullable|url',
            'business_name' => 'required',
            'business_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pin_code' => 'required',
            'country' => 'required',
        ]);

        # Get the authenticated user
        $user = auth()->user();

        # Create or update the KYC record
        $kyc = MerchantKyc::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $this->full_name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'website_url' => $this->website_url,
                'apk_link' => $this->apk_link,
                'business_name' => $this->business_name,
                'business_address' => $this->business_address,
                'state' => $this->state,
                'city' => $this->city,
                'pin_code' => $this->pin_code,
                'country' => $this->country,
                'step' => 3,
            ]
        );

        # move to next step
        $this->step = 3;

        # Set Kyc
        $this->setKyc();
    }

    public function submitStepThree()
    {        
        $this->pan = strtoupper(trim((string) $this->pan));
        $this->gstin = strtoupper(trim((string) $this->gstin));
        if (!blank($this->cin_number)) {
            $this->cin_number = strtoupper(trim((string) $this->cin_number));
        }
        if (!blank($this->company_pan)) {
            $this->company_pan = strtoupper(trim((string) $this->company_pan));
        }

        # Validation data
        $validation = [
            'pan' => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 'size:10'],
            'aadhaar' => ['required', 'regex:/^\d{12}$/'],
            'gstin' => ['required', 'regex:/^[0-9A-Z]{15}$/'],
            'pan_front' => $this->pan_front_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'aadhaar_front' => $this->aadhaar_front_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'aadhaar_back' => $this->aadhaar_back_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gst_certificate' => $this->gst_certificate_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'address_proof' => $this->address_proof_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'registration_certificate' => $this->registration_certificate_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        # File uploads
        $files = [
            'pan_front'                => $this->pan_front,
            'company_pan_front'        => $this->company_pan_front,
            'cin_front'                => $this->cin_front,
            'aadhaar_front'            => $this->aadhaar_front,
            'aadhaar_back'             => $this->aadhaar_back,
            'gst_certificate'          => $this->gst_certificate,
            'address_proof'            => $this->address_proof,
            'registration_certificate' => $this->registration_certificate,
        ];

        # Individual user validation
        if($this->business_type === 'proprietor')
        {
            $validation['proprietor_photo'] = $this->proprietor_photo_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';

            # add to file upload
            $files['proprietor_photo'] = $this->proprietor_photo;
        }

        # Business user validation
        if($this->business_type != 'proprietor')
        {
            $validation['business_type'] = 'required|in:private_limited,llp,partnership,ngo/trust,other';
            $validation['business_name'] = 'required|string|max:255';
            $validation['company_pan'] = ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 'size:10'];
            $validation['cin_number'] = ['required', 'alpha_num', 'max:21'];
            $validation['cin_front'] = $this->cin_front_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $validation['company_pan_front'] = $this->company_pan_front_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $validation['document_aoa'] = $this->document_aoa_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:12288' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $validation['document_moi'] = $this->document_moi_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $validation['document_coi'] = $this->document_coi_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';

            # Add to file upload
            $files['document_aoa'] = $this->document_aoa;
            $files['document_moi'] = $this->document_moi;
            $files['document_coi'] = $this->document_coi;
        }

        # Validate the request
        $this->validate($validation, [
            'cin_front.required' => 'Board Resolution document is required.',
            'cin_front.file'     => 'The Board Resolution must be a valid file.',
            'cin_front.mimes'    => 'The Board Resolution must be a file of type: PDF, JPG, JPEG, or PNG.',
            'cin_front.max'      => 'The Board Resolution file size must not exceed 5 MB.',
        ]);

        $gstPan = substr($this->gstin, 2, 10);
        $panToMatch = ($this->business_type !== 'proprietor') ? $this->company_pan : $this->pan;
        $panLabel   = ($this->business_type !== 'proprietor') ? 'Company PAN' : 'PAN';
        if ($gstPan !== $panToMatch) {
            $this->addError('gstin', "GSTIN must include the same {$panLabel} (3rd to 12th characters).");
            return;
        }

        # Authenticated User
        $user = auth()->user();

        # File uploads
        foreach ($files as $name => $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $filename = $name . '_' . time() . '.' . $file->getClientOriginalExtension();

                // overwrite the property with filename
                $this->{$name} = $filename;

                // store the file with renamed filename
                $file->storeAs('kyc_docs/' . $user->merchant_id, $filename, 'public');
            }
        }

        # Authenticated User id
        $user_id = $user->id;
        

        # Update Merchant Kyc
        $kyc = MerchantKyc::where('user_id', $user_id)->first();
        if(!$kyc)
        {
            $kyc = new MerchantKyc();
            $kyc->user_id = $user_id;
        }
        $kyc->pan = $this->pan;
        $kyc->company_pan = $this->business_type !== 'proprietor' ? $this->company_pan : null;
        $kyc->aadhaar = $this->aadhaar;
        $kyc->gstin = $this->gstin;
        $kyc->cin_number = $this->cin_number;
        $kyc->business_type = $this->business_type;
        $kyc->business_name = $this->business_name;

        $kyc->pan_front = !blank($this->pan_front) ? $this->pan_front : $kyc->pan_front;
        $kyc->company_pan_front = !blank($this->company_pan_front) ? $this->company_pan_front : $kyc->company_pan_front;
        $kyc->cin_front = !blank($this->cin_front) ? $this->cin_front : $kyc->cin_front;
        $kyc->aadhaar_front = !blank($this->aadhaar_front) ? $this->aadhaar_front : $kyc->aadhaar_front;
        $kyc->aadhaar_back = !blank($this->aadhaar_back) ? $this->aadhaar_back : $kyc->aadhaar_back;
        $kyc->gst_certificate = !blank($this->gst_certificate) ? $this->gst_certificate : $kyc->gst_certificate;
        $kyc->address_proof = !blank($this->address_proof) ? $this->address_proof : $kyc->address_proof;
        $kyc->registration_certificate = !blank($this->registration_certificate) ? $this->registration_certificate : $kyc->registration_certificate;
        $kyc->proprietor_photo = !blank($this->proprietor_photo) ? $this->proprietor_photo : $kyc->proprietor_photo;
        $kyc->document_aoa = !blank($this->document_aoa) ? $this->document_aoa : $kyc->document_aoa;
        $kyc->document_moi = !blank($this->document_moi) ? $this->document_moi : $kyc->document_moi;
        $kyc->document_coi = !blank($this->document_coi) ? $this->document_coi : $kyc->document_coi;
        $kyc->step = 4;
        $kyc->save();

        # Move to next step
        $this->step = 4;

        # update kyc
        $this->setKyc();
    }

    public function submitStepFour()
    {
        # Validation data
        $validation = [
            'bank_name' => ['required'],
            'branch' => ['required'],
            'account_type' => ['required', 'in:SAVING,CURRENT'],
            'account_holder' => ['required'],
            'account_number' => ['required'],
            'ifsc_code' => ['required'],
            'cancelled_cheque' => $this->cancelled_cheque_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_statement' => $this->bank_statement_url ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        # File uploads
        $files = [
            'cancelled_cheque'         => $this->cancelled_cheque,
            'bank_statement'           => $this->bank_statement,
        ];

        # Validate the request
        $this->validate($validation);

        # Authenticated User
        $user = auth()->user();

        # File uploads
        foreach ($files as $name => $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $filename = $name . '_' . time() . '.' . $file->getClientOriginalExtension();

                // overwrite the property with filename
                $this->{$name} = $filename;

                // store the file with renamed filename
                $file->storeAs('kyc_docs/' . $user->merchant_id, $filename, 'public');
            }
        }

        # Authenticated User id
        $user_id = $user->id;
        

        # Update Merchant Kyc
        $kyc = MerchantKyc::where('user_id', $user_id)->first();
        if(!$kyc)
        {
            $kyc = new MerchantKyc();
            $kyc->user_id = $user_id;
        }
        $kyc->bank_name = $this->bank_name;
        $kyc->branch = $this->branch;
        $kyc->account_type = $this->account_type;
        $kyc->account_holder = $this->account_holder;
        $kyc->account_number = $this->account_number;
        $kyc->ifsc_code = $this->ifsc_code;
        $kyc->cancelled_cheque = !blank($this->cancelled_cheque) ? $this->cancelled_cheque : $kyc->cancelled_cheque;
        $kyc->bank_statement = !blank($this->bank_statement) ? $this->bank_statement : $kyc->bank_statement;
        $kyc->step = 5;
        $kyc->save();

        # Move to next step
        $this->step = 5;

        # update kyc
        $this->setKyc();
    }

    public function submit()
    {
        $this->validate([
            'agreeTerms' => 'required'
        ]);

        # Authenticated User
        $user = auth()->user();

        $kyc = MerchantKyc::where('user_id', $user->id)->first();
        if($kyc)
        {
            $kyc->kyc_remark = "Waiting For Approval";
            $kyc->save();

            # Add KYC bank account as primary source account (if not already added)
            if(!blank($kyc->account_number))
            {
                SourceAccount::firstOrCreate(
                    ['account_number' => $kyc->account_number],
                    [
                        'user_id'             => $user->id,
                        'ifsc_code'           => $kyc->ifsc_code,
                        'account_holder_name' => $kyc->account_holder,
                        'bank_name'           => $kyc->bank_name,
                        'document'            => $kyc->cancelled_cheque, // Storing cancelled cheque as document for source account
                        'document_type'       => 'cancelled_cheque',
                        'is_primary'          => true,
                        'status'              => 'inactive',
                    ]
                );
            }
        }

        $user->kyc_status = 'submitted';
        $user->save();

        return redirect()->route('merchant.kyc.status');
    }

    // ---------- Utility ----------
    public function resetForm()
    {
        $this->reset(['website_url', 'apk_link',
            'full_name', 'email', 'mobile', 'pan', 'pan_front',
            'company_pan', 'company_pan_front',
            'aadhaar', 'aadhaar_front', 'aadhaar_back',
            'cancelled_cheque', 'bank_statement',
            'proprietor_photo', 'registration_certificate',
            'address_proof', 'gstin', 'gst_certificate',
            'business_type', 'business_name',
            'document_aoa', 'document_moi', 'document_coi',
            'kyc_remark', 'agreeTerms'
        ]);
    }

    // ---------- Rendering ----------
    public function render()
    {
        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $this->category_id)->get();
        
        return view('merchant.kyc-component', [
            'states' => State::all(),
            'categories' => $categories,
            'subcategories' => $subcategories,
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'dashboard',
            'pageTitle' => 'KYC Verification',
            'metaTitle' => 'KYC Verification - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Complete your KYC verification to unlock all features on M.M.P Fintech Payment Solution.',
        ]);
    }
}
