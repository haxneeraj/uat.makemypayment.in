<div x-data="{ completed: false }" class="w-full bg-white rounded-2xl shadow-lg p-0 mt-2 mb-10 pb-10">
    <h2 class="text-3xl font-bold text-[#1a237e] mb-8 text-center pt-8">KYC Verification</h2>
    @if($successMessage)
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center font-semibold">
            {{ $successMessage }}
        </div>
    @endif

    {{-- Modern Stepper --}}
    <div class="flex justify-center mb-10 w-full">
        <div class="flex w-full max-w-3xl mx-auto relative">
            {{-- Background Line --}}
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200"></div>
            <div style="width: {{ (($step - 1) / 4) * 100 }}%" class="absolute top-5 left-0 h-1 bg-green-500 transition-all duration-300"></div>
            
            @foreach([1,2,3,4,5] as $s)
                <div class="flex-1 flex flex-col items-center relative">
                    {{-- Step Circle --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition duration-200 z-10 relative {{ $step == $s ? 'bg-blue-600 text-white shadow-lg' : ($step > $s ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700') }}">
                        @if($step > $s)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span>{{ $s }}</span>
                        @endif
                    </div>
                    {{-- Step Label --}}
                    <span class="mt-2 text-xs font-semibold text-center {{ $step === $s ? 'text-blue-700' : 'text-gray-500' }}">
                        @if($s == 1)
                            Step 1<br>Requirements
                        @elseif($s == 2)
                            Step 2<br>Basic Info
                        @elseif($s == 3)
                            Step 3<br>Documents
                        @elseif($s == 4)
                            Step 4<br>Primary Bank Details
                        @else
                            Step 5<br>Review & Submit
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="w-full px-0 md:px-8">
        {{-- Step 1: Requirements & User Type --}}
        @if($step == 1)
            <div class="w-full">
                <form wire:submit.prevent="submitStepOne" enctype="multipart/form-data" novalidate class="w-full px-0 md:px-8">

                    {{-- Section: Business Details --}}
                    <div class="mb-8">
                        <h3 class="text-base font-bold text-[#1a237e] mb-4 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold">1</span>
                            Business Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 border border-gray-200 rounded-xl p-5">
                            {{-- Business Type --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">Business Type <span class="text-red-500">*</span></label>
                                <select wire:model.live="business_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 bg-white">
                                    <option value="">Select Business Type</option>
                                    <option value="proprietor">Proprietor</option>
                                    <option value="private_limited">Private Limited</option>
                                    <option value="llp">LLP</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="ngo/trust">NGO / Trust</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('business_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">Category <span class="text-red-500">*</span></label>
                                <select wire:model.live="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 bg-white">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Sub-Category --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">
                                    Sub-Category <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="subcategory_id" @disabled(!$category_id) class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 bg-white disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    <option value="">{{ $category_id ? 'Select Sub-Category' : 'Select Category first' }}</option>
                                    @foreach($subcategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                    @endforeach
                                </select>
                                @error('subcategory_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section: Required Documents --}}
                    <div class="mb-8">
                        <h3 class="text-base font-bold text-[#1a237e] mb-4 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold">2</span>
                            Required Documents
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Proprietor --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="font-bold text-sm text-blue-800">Sole Proprietorship / Individual</span>
                                </div>
                                <ul class="text-sm text-gray-700 space-y-1.5">
                                    @foreach(['Incorporation / Udyam / MSME Certificate', 'PAN Card (Proprietor)', 'Aadhaar Card (Both Sides)', 'Proprietor Photo with Stamp', 'GST Certificate & GSTIN (if applicable)', 'Rental Agreement or Utility Bill', 'Cancelled Cheque', 'Last 6 Months Bank Statement', 'Company Website URL or APK Link', 'Email ID & Mobile Number'] as $item)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Business --}}
                            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-bold text-sm text-indigo-800">Private Limited / LLP / Partnership / NGO / Trust</span>
                                </div>
                                <ul class="text-sm text-gray-700 space-y-1.5">
                                    @foreach(['PAN Card (Company & Directors)', 'Company Documents (AOA, MOA, COI)', 'CIN Number & CIN Document', 'Udyam Registration Certificate', 'Aadhaar Card (Both Sides)', 'GST Certificate & GSTIN', 'Authorized Signatory Photo with Stamp', 'Rent Agreement or Utility Bill', 'Cancelled Cheque', 'Last 6 Months Bank Statement', 'Company Website URL or APK Link', 'Email ID & Mobile Number'] as $item)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-indigo-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            wire:loading.class="opacity-50 cursor-not-allowed" 
                            wire:loading.attr="disabled" 
                            wire:target="submitStepOne" 
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                            <span wire:loading.remove wire:target="submitStepOne" class="flex items-center gap-2">
                                Next
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                            <span wire:loading wire:target="submitStepOne">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Step 2: Basic Info --}}
        @if($step == 2)
            <div class="w-full">
                <form wire:submit.prevent="submitStepTwo" enctype="multipart/form-data" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mb-4">
                            <label class="block font-semibold mb-1">{{ $business_type === 'proprietor' ? "Proprietor" : "Director" }} Full Name <small class="text-red-600">*</small></label>
                            <input wire:model="full_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('full_name') border-red-500 @enderror" autocomplete="name" />
                            @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold mb-1">Email <small class="text-red-600">*</small></label>
                            <input wire:model="email" type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('email') border-red-500 @enderror" autocomplete="email" />
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold mb-1">Mobile <small class="text-red-600">*</small></label>
                            <input wire:model="mobile" type="text" maxlength="10" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('mobile') border-red-500 @enderror" autocomplete="tel" />
                            @error('mobile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold mb-1">Website URL <small class="text-red-600">*</small></label>
                            <input wire:model="website_url" type="url" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('website_url') border-red-500 @enderror" autocomplete="url" />
                            @error('website_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold mb-1">APK Link (Android App)</label>
                            <input wire:model="apk_link" type="url" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('apk_link') border-red-500 @enderror" autocomplete="url" />
                            @error('apk_link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="my-4 bg-yellow-100 text-black p-2 rounded">
                        <strong>Note:</strong> You must provide at least one link, either a Website URL or an APK link.
                    </div>

                    <hr />

                    <div class="mt-8">
                        <h2 class="text-2xl font-bold text-[#1a237e]">Business (Proprietor, Private Limited, LLP, Partnership, NGO/Trust, Other) Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">                           
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">Business Name <small class="text-red-600">*</small></label>
                                <input wire:model="business_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('business_name') border-red-500 @enderror" autocomplete="name" />
                                @error('business_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">Business Address <small class="text-red-600">*</small></label>
                                <input wire:model="business_address" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('business_address') border-red-500 @enderror" autocomplete="address" />
                                @error('business_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <!-- City -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">City <small class="text-red-600">*</small></label>
                                <input wire:model="city" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('city') border-red-500 @enderror" autocomplete="address-level2" />
                                @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <!-- State -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">State <small class="text-red-600">*</small></label>
                                <select wire:model="state" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('state') border-red-500 @enderror" autocomplete="address-level1">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <!-- Pin Code -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">Pin Code <small class="text-red-600">*</small></label>
                                <input wire:model="pin_code" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('pin_code') border-red-500 @enderror" autocomplete="postal-code" />
                                @error('pin_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            
                            <!-- Country -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-1">Country <small class="text-red-600">*</small></label>
                                <select wire:model="country" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 bg-gray-50 @error('country') border-red-500 @enderror" autocomplete="postal-code">
                                    <option value="">Select Country</option>
                                    <option value="India" >India</option>
                                </select>
                                @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" 
                        wire:click="previousStep" 
                        wire:loading.attr="disabled"
                        wire:loading.class="cursor-not-allowed opacity-50"
                        wire:target="previousStep"
                        class="bg-gray-100 text-gray-700 px-8 py-2 rounded font-semibold hover:bg-gray-300 transition duration-200 flex items-center gap-2">
                            <span wire:loading.remove wire:target="previousStep">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Back</span>
                                </span>
                            </span>

                            <span wire:loading wire:target="previousStep">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span>Processing...</span>
                                </span>
                            </span>
                        </button>

                        <button type="submit" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="submitStepTwo" class="bg-blue-600 text-white px-8 py-2 rounded font-semibold hover:bg-blue-700 flex items-center gap-2 transition duration-200">
                            <span wire:loading.remove wire:target="submitStepTwo">
                                <span class="flex items-center gap-2">
                                    Next
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </span>
                            <span wire:loading wire:target="submitStepTwo">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span>Processing...</span>
                                </span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Step 3: Document Uploads --}}
        @if($step == 3)
        <div class="w-full">
            <form wire:submit.prevent="submitStepThree" enctype="multipart/form-data" novalidate>

                {{-- ══════════════════════════════════════
                     Section A: KYC Identifiers
                ══════════════════════════════════════ --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold">A</span>
                        <h3 class="text-base font-bold text-[#1a237e] uppercase tracking-wide">KYC Identifiers</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 border border-gray-200 rounded-xl p-5">

                        {{--Director PAN --}}
                        <div x-data="{ panError: '' }">
                            <label class="block font-semibold mb-1 text-sm">{{ $business_type === 'proprietor' ? 'Proprietor PAN' : 'Director PAN' }} Number <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="pan" type="text" maxlength="10"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 uppercase text-sm focus:ring focus:ring-blue-200 @error('pan') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10); panError = '';"
                                x-on:blur="panError = ($el.value && !/^[A-Z]{5}[0-9]{4}[A-Z]$/.test($el.value)) ? 'PAN format should be like ABCDE1234F' : '';" />
                            <span x-show="panError" x-text="panError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('pan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Aadhaar --}}
                        <div x-data="{ aadhaarError: '' }">
                            <label class="block font-semibold mb-1 text-sm">{{ $business_type === 'proprietor' ? 'Proprietor' : 'Director' }} Aadhaar Number <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="aadhaar" type="text" maxlength="12" inputmode="numeric" pattern="[0-9]*"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200 @error('aadhaar') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.replace(/\D/g, '').slice(0, 12); aadhaarError = '';"
                                x-on:blur="aadhaarError = ($el.value && !/^\d{12}$/.test($el.value)) ? 'Aadhaar must be exactly 12 digits' : '';" />
                            <span x-show="aadhaarError" x-text="aadhaarError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('aadhaar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if($business_type != 'proprietor')
                        {{--Company PAN --}}
                        <div x-data="{ companyPanError: '' }">
                            <label class="block font-semibold mb-1 text-sm">Company PAN Number <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="company_pan" type="text" maxlength="10"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 uppercase text-sm focus:ring focus:ring-blue-200 @error('company_pan') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10); companyPanError = '';"
                                x-on:blur="companyPanError = ($el.value && !/^[A-Z]{5}[0-9]{4}[A-Z]$/.test($el.value)) ? 'PAN format should be like ABCDE1234F' : '';" />
                            <span x-show="companyPanError" x-text="companyPanError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('company_pan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        {{-- GSTIN --}}
                        <div>
                            <label class="block font-semibold mb-1 text-sm">{{ $business_type === 'proprietor' ? 'Proprietor' : 'Company' }} GSTIN <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="gstin" type="text" maxlength="15"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 uppercase text-sm focus:ring focus:ring-blue-200 @error('gstin') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 15);" />
                            @error('gstin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- CIN (non-proprietor only) --}}
                        @if($business_type != 'proprietor')
                        <div x-data="{ cinError: '' }">
                            <label class="block font-semibold mb-1 text-sm">CIN Number <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="cin_number" type="text" maxlength="21"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 uppercase text-sm focus:ring focus:ring-blue-200 @error('cin_number') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 21); cinError = '';"
                                x-on:blur="cinError = ($el.value && !/^[A-Z0-9]+$/.test($el.value)) ? 'CIN must be alphanumeric only' : '';" />
                            <span x-show="cinError" x-text="cinError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('cin_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @endif

                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     Section B: Identity Documents
                ══════════════════════════════════════ --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold">B</span>
                        <h3 class="text-base font-bold text-[#1a237e] uppercase tracking-wide">Identity Documents</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- PAN Front --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'pan_front',
                            'xRef'        => 'pan_front_input',
                            'label'       => 'Director PAN Card',
                            'required'    => !$pan_front_url,
                            'existingUrl' => $pan_front_url,
                            'errorKey'    => 'pan_front',
                        ])

                        {{-- Aadhaar Front --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'aadhaar_front',
                            'xRef'        => 'aadhaar_front_input',
                            'label'       => 'Aadhaar Card (Front)',
                            'required'    => !$aadhaar_front_url,
                            'existingUrl' => $aadhaar_front_url,
                            'errorKey'    => 'aadhaar_front',
                        ])

                        {{-- Aadhaar Back --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'aadhaar_back',
                            'xRef'        => 'aadhaar_back_input',
                            'label'       => 'Aadhaar Card (Back)',
                            'required'    => !$aadhaar_back_url,
                            'existingUrl' => $aadhaar_back_url,
                            'errorKey'    => 'aadhaar_back',
                        ])

                        {{-- Proprietor Photo (proprietor only) --}}
                        @if($business_type === 'proprietor')
                            @include('merchant.kyc._file-uploader', [
                                'wireModel'   => 'proprietor_photo',
                                'xRef'        => 'proprietor_photo_input',
                                'label'       => 'Proprietor Photo With Stamp',
                                'required'    => !$proprietor_photo_url,
                                'existingUrl' => $proprietor_photo_url,
                                'errorKey'    => 'proprietor_photo',
                            ])
                        @endif

                        {{-- CIN Document (non-proprietor) --}}
                        @if($business_type != 'proprietor')
                            @include('merchant.kyc._file-uploader', [
                                'wireModel'   => 'cin_front',
                                'xRef'        => 'cin_front_input',
                                'label'       => 'Board Resolution',
                                'required'    => !$cin_front_url,
                                'existingUrl' => $cin_front_url,
                                'errorKey'    => 'cin_front',
                            ])
                        @endif

                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     Section C: Business & Tax Documents
                ══════════════════════════════════════ --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white text-xs font-bold">C</span>
                        <h3 class="text-base font-bold text-[#1a237e] uppercase tracking-wide">Business & Tax Documents</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Company PAN Front (non-proprietor only) --}}
                        @if($business_type != 'proprietor')
                            @include('merchant.kyc._file-uploader', [
                                'wireModel'   => 'company_pan_front',
                                'xRef'        => 'company_pan_front_input',
                                'label'       => 'Company PAN Card',
                                'required'    => !$company_pan_front_url,
                                'existingUrl' => $company_pan_front_url,
                                'errorKey'    => 'company_pan_front',
                            ])
                        @endif

                        {{-- GST Certificate --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'gst_certificate',
                            'xRef'        => 'gst_certificate_input',
                            'label'       => 'GST Certificate',
                            'required'    => !$gst_certificate_url,
                            'existingUrl' => $gst_certificate_url,
                            'errorKey'    => 'gst_certificate',
                        ])

                        {{-- Address Proof --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'address_proof',
                            'xRef'        => 'address_proof_input',
                            'label'       => 'Address Proof (Rental Agreement / EB Bill / Utility Bill)',
                            'required'    => !$address_proof_url,
                            'existingUrl' => $address_proof_url,
                            'errorKey'    => 'address_proof',
                        ])

                        {{-- Registration Certificate --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'registration_certificate',
                            'xRef'        => 'registration_certificate_input',
                            'label'       => ($business_type === 'proprietor' ? 'Incorporation / Udyam / MSME Certificate' : 'Udyam Registration Certificate'),
                            'required'    => !$registration_certificate_url,
                            'existingUrl' => $registration_certificate_url,
                            'errorKey'    => 'registration_certificate',
                        ])

                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     Section D: Company Documents (non-proprietor)
                ══════════════════════════════════════ --}}
                @if($business_type != 'proprietor')
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-amber-500 text-white text-xs font-bold">D</span>
                        <h3 class="text-base font-bold text-[#1a237e] uppercase tracking-wide">Company Documents (AOA / MOA / COI)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'document_aoa',
                            'xRef'        => 'document_aoa_input',
                            'label'       => 'Document AOA',
                            'required'    => !$document_aoa_url,
                            'existingUrl' => $document_aoa_url,
                            'errorKey'    => 'document_aoa',
                            'fileSize'    => 12288,
                        ])

                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'document_moi',
                            'xRef'        => 'document_moi_input',
                            'label'       => 'Document MOA',
                            'required'    => !$document_moi_url,
                            'existingUrl' => $document_moi_url,
                            'errorKey'    => 'document_moi',
                        ])

                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'document_coi',
                            'xRef'        => 'document_coi_input',
                            'label'       => 'Document COI',
                            'required'    => !$document_coi_url,
                            'existingUrl' => $document_coi_url,
                            'errorKey'    => 'document_coi',
                        ])

                    </div>
                </div>
                @endif

                {{-- Navigation --}}
                <div class="flex justify-between mt-8">
                    <button type="button" wire:click="previousStep" class="bg-gray-100 text-gray-700 px-8 py-2 rounded font-semibold hover:bg-gray-300 transition duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Back</span>
                    </button>
                    <button type="submit" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="submitStepThree"
                        class="bg-blue-600 text-white px-8 py-2 rounded font-semibold hover:bg-blue-700 flex items-center gap-2 transition duration-200">
                        <span wire:loading.remove wire:target="submitStepThree">Next</span>
                        <span wire:loading wire:target="submitStepThree">Saving...</span>
                        <svg class="w-5 h-5" wire:loading.remove wire:target="submitStepThree" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
        @endif

        {{-- Step 4: Primary Bank Details --}}
        @if($step == 4)
            <div class="w-full">
                <form wire:submit.prevent="submitStepFour" enctype="multipart/form-data" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mb-2">
                            <label class="block font-semibold mb-1">Bank Name <small class="text-red-600">*</small></label>
                            <input wire:model="bank_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring @error('bank_name') border-red-500 @enderror" autocomplete="off" />
                            @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block font-semibold mb-1">Branch <small class="text-red-600">*</small></label>
                            <input wire:model="branch" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring @error('branch') border-red-500 @enderror" autocomplete="off" />
                            @error('branch') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block font-semibold mb-1">Account Type <small class="text-red-600">*</small></label>
                            <select wire:model="account_type" type="text" class="w-full border rounded px-3 py-2 uppercase focus:ring @error('account_type') border-red-500 @enderror" autocomplete="off" >
                                <option value="" selected>Select Account Type</option>
                                <option value="SAVING" >SAVING</option>
                                <option value="CURRENT" >CURRENT</option>
                            </select>
                            @error('account_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block font-semibold mb-1">Account Holder <small class="text-red-600">*</small></label>
                            <input wire:model="account_holder" type="text" maxlength="40" class="w-full border rounded px-3 py-2 uppercase focus:ring @error('account_holder') border-red-500 @enderror" autocomplete="off" />
                            @error('account_holder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2" x-data="{ accError: '' }">
                            <label class="block font-semibold mb-1">Account Number <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="account_number" type="text" maxlength="17" inputmode="numeric" pattern="[0-9]*"
                                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200 @error('account_number') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.replace(/\D/g, '').slice(0, 17); accError = '';"
                                x-on:blur="accError = ($el.value && !/^\d{9,17}$/.test($el.value)) ? 'Account number must be 9–17 digits only' : '';" />
                            <span x-show="accError" x-text="accError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2" x-data="{ ifscError: '' }">
                            <label class="block font-semibold mb-1">IFSC Code <small class="text-red-600">*</small></label>
                            <input wire:model.live.debounce.500ms="ifsc_code" type="text" maxlength="11"
                                class="w-full border rounded px-3 py-2 uppercase focus:ring focus:ring-blue-200 @error('ifsc_code') border-red-500 @enderror"
                                autocomplete="off"
                                x-on:input="$el.value = $el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 11); ifscError = '';"
                                x-on:blur="ifscError = ($el.value && !/^[A-Z]{4}0[A-Z0-9]{6}$/.test($el.value)) ? 'IFSC format should be like SBIN0001234 (4 letters + 0 + 6 chars)' : '';" />
                            <span x-show="ifscError" x-text="ifscError" class="text-red-500 text-xs mt-1 block"></span>
                            @error('ifsc_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Cancelled Cheque --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'cancelled_cheque',
                            'xRef'        => 'cancelled_cheque_input',
                            'label'       => 'Cancelled Cheque',
                            'required'    => !$cancelled_cheque_url,
                            'existingUrl' => $cancelled_cheque_url,
                            'errorKey'    => 'cancelled_cheque',
                        ])

                        {{-- Bank Statement --}}
                        @include('merchant.kyc._file-uploader', [
                            'wireModel'   => 'bank_statement',
                            'xRef'        => 'bank_statement_input',
                            'label'       => 'Bank Statement (Last 6 Months)',
                            'required'    => !$bank_statement_url,
                            'existingUrl' => $bank_statement_url,
                            'errorKey'    => 'bank_statement',
                        ])
                    </div>
                    <div class="flex justify-between mt-8">
                        <button type="button" wire:click="previousStep" class="bg-gray-100 text-gray-700 px-8 py-2 rounded font-semibold hover:bg-gray-300 transition duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Back</span>
                        </button>
                        <button type="submit" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="submitStepFour" class="bg-blue-600 text-white px-8 py-2 rounded font-semibold hover:bg-blue-700 flex items-center gap-2 transition duration-200">
                            Next
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Step 5: Review & Submit --}}
        @if($step == 5)
            <div class="w-full">
                <form wire:submit.prevent="submit" enctype="multipart/form-data" novalidate>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold mb-4 text-[#1a237e]">Review Your Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li><strong>Full Name:</strong> {{ $full_name }}</li>
                                <li><strong>Email:</strong> {{ $email }}</li>
                                <li><strong>Mobile:</strong> {{ $mobile }}</li>
                                <li><strong>Website URL:</strong> {{ $website_url }}</li>
                                <li><strong>APK Link:</strong> {{ $apk_link }}</li>
                                <li><strong>Business Type:</strong> {{ $business_type }}</li>
                                <li><strong>Business Name:</strong> {{ $business_name }}</li>
                                {{-- ...add more review fields as needed... --}}
                            </ul>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li><strong>PAN:</strong> {{ $pan }}</li>
                                <li><strong>Aadhaar:</strong> {{ $aadhaar }}</li>
                                <li><strong>GSTIN:</strong> {{ $gstin }}</li>
                                <li class="p-2 bg-yellow-300 text-black rounded"><strong>KYC Remark:</strong> {{ $kyc_remark }}</li>
                                {{-- ...add more review fields as needed... --}}
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4 flex items-center">
                        <input wire:model="agreeTerms" type="checkbox" id="agreeTerms" class="mr-2" />
                        <label for="agreeTerms" class="text-sm cursor-pointer">I agree to the <a href="{{ route('site.terms') }}" target="_blank" class="text-blue-600 hover:underline">Terms and Conditions</a> <span class="text-red-500">*</span></label>
                        @error('agreeTerms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror 
                    </div>
                    @error('agreeTerms') <span class="text-red-500 text-xs mb-2 block">{{ $message }}</span> @enderror
                    <div class="flex justify-between mt-8">
                        <button type="button" wire:click="previousStep" class="bg-gray-100 text-gray-700 px-8 py-2 rounded font-semibold hover:bg-gray-300 transition duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Back</span>
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded font-semibold hover:bg-blue-700 flex items-center gap-2 transition duration-200" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" wire:target="submit" @click="completed = true">
                            <span>Submit KYC</span>
                            <span wire:loading wire:target="submit" class="ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
                <div x-show="completed" class="flex flex-col items-center mt-8">
                    <div class="bg-green-100 text-green-700 px-6 py-4 rounded-lg flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-semibold">KYC Submitted Successfully!</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>