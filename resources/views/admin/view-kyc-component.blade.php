<div class="max-w-7xl mx-auto" x-data="{ showConfirmModal: false, actionToConfirm: '', statusToConfirm: '' }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: KYC Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong class="text-gray-500">Merchant ID:</strong> <span class="font-mono">{{ $merchant->merchant_id }}</span></div>
                    <div><strong class="text-gray-500">Full Name:</strong> {{ $kyc->full_name }}</div>
                    <div><strong class="text-gray-500">Email:</strong> {{ $kyc->email }}</div>
                    <div><strong class="text-gray-500">Phone:</strong> {{ $kyc->mobile }}</div>
                    <div><strong class="text-gray-500">Category:</strong> {{ $kyc->category ?? '-' }}</div>
                    <div><strong class="text-gray-500">Sub Category:</strong> {{ $kyc->sub_category ?? '-' }}</div>
                    <div><strong class="text-gray-500">Website:</strong> <a href="{{ $kyc->website_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $kyc->website_url }}</a></div>
                    @if($kyc->apk_link)
                    <div><strong class="text-gray-500">APK Link:</strong> <a href="{{ $kyc->apk_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $kyc->apk_link }}</a></div>
                    @endif
                    <div><strong class="text-gray-500">User Type:</strong> <span class="capitalize">{{ $kyc->user_type }}</span></div>
                </div>
            </div>

            <!-- Business Information -->
            @if($kyc->user_type === 'business')
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Business Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong class="text-gray-500">Business Name:</strong> {{ $kyc->business_name }}</div>
                    <div><strong class="text-gray-500">Business Type:</strong> <span class="capitalize">{{ str_replace('_', ' ', $kyc->business_type) }}</span></div>
                </div>
            </div>
            @endif

            <!-- ID Information -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Identity Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong class="text-gray-500">PAN:</strong> {{ $kyc->pan }}</div>
                    <div><strong class="text-gray-500">Aadhaar:</strong> {{ $kyc->aadhaar }}</div>
                    <div><strong class="text-gray-500">GSTIN:</strong> {{ $kyc->gstin }}</div>
                </div>
            </div>



            <!-- Primary Bank Details -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Primary Bank Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong class="text-gray-500">Account Holder:</strong> {{ $kyc->account_holder }}</div>
                    <div><strong class="text-gray-500">Account Number:</strong> {{ $kyc->account_number }}</div>
                    <div><strong class="text-gray-500">IFSC Code:</strong> {{ $kyc->ifsc_code }}</div>
                    <div><strong class="text-gray-500">Account Type:</strong> {{ $kyc->account_type }}</div>
                    <div><strong class="text-gray-500">Bank Name:</strong> {{ $kyc->bank_name }}</div>
                    <div><strong class="text-gray-500">Branch:</strong> {{ $kyc->branch }}</div>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Uploaded Documents</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $documents = [
                            'pan_front' => 'PAN Card',
                            'aadhaar_front' => 'Aadhaar Front',
                            'aadhaar_back' => 'Aadhaar Back',
                            'gst_certificate' => 'GST Certificate',
                            'address_proof' => 'Address Proof',
                            'registration_certificate' => 'Registration Certificate',
                            'cancelled_cheque' => 'Cancelled Cheque',
                            'bank_statement' => 'Bank Statement',
                            'proprietor_photo' => 'Proprietor Photo',
                            'document_aoa' => 'Document AOA',
                            'document_moi' => 'Document MOI',
                            'document_coi' => 'Document COI',
                        ];
                    @endphp

                    @foreach($documents as $key => $label)
                        @if($kyc->{$key})
                        <div class="text-sm">
                            <strong class="text-gray-600">{{ $label }}</strong>
                            <a href="{{ asset('storage/kyc_docs/' . $merchant->merchant_id . '/' . $kyc->{$key}) }}" target="_blank" class="mt-1 flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                                View Document
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-sm border sticky top-6">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">KYC Action</h3>
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded-lg text-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="kyc_status" class="block text-sm font-medium text-gray-700">Current Status</label>
                        <p class="mt-1 px-3 py-2 bg-gray-100 rounded-md text-sm capitalize">{{ $kyc_status }}</p>
                    </div>
                    <div>
                        <label for="kyc_remark" class="block text-sm font-medium text-gray-700">Remark</label>
                        <textarea wire:model="kyc_remark" id="kyc_remark" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add a remark, especially for rejection..."></textarea>
                        @error('kyc_remark') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        @session('error')
                            <div class="bg-red-100 text-red-800 p-3 rounded-lg text-sm mb-4 mt-2">{{ session('error') }}</div>
                        @endsession
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button @click="actionToConfirm = 'Reject'; statusToConfirm = 'rejected'; showConfirmModal = true" wire:loading.attr="disabled" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reject
                        </button>
                        <button @click="actionToConfirm = 'Approve'; statusToConfirm = 'verified'; showConfirmModal = true" wire:loading.attr="disabled" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75" @keydown.escape.window="showConfirmModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4" @click.away="showConfirmModal = false">
            <h3 class="text-lg font-bold text-gray-900">Confirm Action</h3>
            <div class="mt-4 text-sm text-gray-600">
                <p>Are you sure you want to <strong x-text="actionToConfirm.toLowerCase()"></strong> this KYC application?</p>
                <template x-if="statusToConfirm === 'rejected'">
                    <p class="mt-2 p-2 bg-yellow-50 text-yellow-800 rounded-md">Please ensure you have provided a clear reason for rejection in the remarks field.</p>
                </template>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                {{-- Cancel Button --}}
                <button @click="showConfirmModal = false"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    wire:target="updateKyc"
                    class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                    <span wire:loading.remove wire:target="updateKyc" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </span>
                    <span wire:loading wire:target="updateKyc" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Please wait...
                    </span>
                </button>

                {{-- Confirm Button --}}
                <button 
                    @click="showConfirmModal = false"
                    wire:click="updateKyc(statusToConfirm)"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    wire:target="updateKyc"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-white font-semibold text-sm transition"
                    :class="{ 'bg-green-600 hover:bg-green-700': statusToConfirm === 'verified', 'bg-red-600 hover:bg-red-700': statusToConfirm === 'rejected' }"
                >
                    {{-- Loading state --}}
                    <span wire:loading wire:target="updateKyc" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Processing...
                    </span>

                    {{-- Normal state: icon changes based on action --}}
                    <span wire:loading.remove wire:target="updateKyc" class="flex items-center gap-2">
                        <template x-if="statusToConfirm === 'verified'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="statusToConfirm === 'rejected'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>
                        Confirm <span x-text="actionToConfirm"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
