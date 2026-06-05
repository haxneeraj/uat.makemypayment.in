<div class="mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Source Accounts</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $usedSlots }} / {{ $maxSourceAccounts }} accounts used
            </p>
        </div>
        <button
            wire:click="openAddModal"
            @class([
                'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm shadow transition',
                'bg-appPrimary text-white hover:opacity-90' => $usedSlots < $maxSourceAccounts,
                'bg-gray-200 text-gray-400 cursor-not-allowed' => $usedSlots >= $maxSourceAccounts,
            ])
            @disabled($usedSlots >= $maxSourceAccounts)
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Account
        </button>
    </div>

    {{-- Limit Progress Bar --}}
    <div class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
        <div class="flex-1">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Account Slots</span>
                <span>{{ $usedSlots }} of {{ $maxSourceAccounts }} used</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div
                    class="h-2 rounded-full transition-all {{ $usedSlots >= $maxSourceAccounts ? 'bg-red-500' : 'bg-appPrimary' }}"
                    style="width: {{ $maxSourceAccounts > 0 ? round(($usedSlots / $maxSourceAccounts) * 100) : 0 }}%"
                ></div>
            </div>
        </div>
        <div class="text-sm {{ $usedSlots >= $maxSourceAccounts ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
            @if($usedSlots >= $maxSourceAccounts)
                Limit Reached
            @else
                {{ $maxSourceAccounts - $usedSlots }} slot(s) remaining
            @endif
        </div>
    </div>

    {{-- Accounts List --}}
    @forelse($sourceAccounts as $account)
        <div class="bg-white rounded-xl border {{ $account->is_primary ? 'border-appPrimary/40' : 'border-gray-200' }} shadow-sm p-6 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Bank Icon --}}
                <div class="w-12 h-12 rounded-full bg-appPrimary/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-appPrimary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 10v11m4-11v11m4-11v11"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-semibold text-gray-900">{{ $account->account_holder_name }}</span>
                        @if($account->is_primary)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-appPrimary/10 text-appPrimary border border-appPrimary/30">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/>
                                </svg>
                                Primary
                            </span>
                        @endif
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $account->status === 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                            {{ $account->status === 'active' ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">{{ $account->bank_name }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-2 text-sm">
                <div>
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Account Number</div>
                    <div class="text-gray-800 font-medium font-mono">{{ $account->account_number }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-0.5">IFSC Code</div>
                    <div class="text-gray-800 font-medium font-mono">{{ $account->ifsc_code }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Added On</div>
                    <div class="text-gray-800 font-medium">{{ $account->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Document</div>
                    <div class="text-gray-800 font-medium">
                        {{ $account->document_type === 'statement' ? 'Bank Statement' : ($account->document_type === 'cancel_cheque' ? 'Cancelled Cheque' : 'Document') }}
                    </div>
                    @if($account->document)
                        <a href="{{ asset('storage/' . $account->document) }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-xs font-semibold text-appPrimary hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            View File
                        </a>
                    @endif
                </div>
            </div>

            {{-- Delete --}}
            <div class="shrink-0">
                @if($account->is_primary)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-400 cursor-not-allowed select-none" title="Primary account cannot be deleted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0 0v2m0-2h2m-2 0H10M9 9h.01M15 9h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                        Protected
                    </span>
                @elseif($deleteConfirmId === $account->id)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-red-600 font-medium">Send OTP to delete?</span>
                        <button wire:click="requestDeleteOtp({{ $account->id }})"
                            class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg font-semibold hover:bg-red-700 transition">
                            Yes, Send OTP
                        </button>
                        <button wire:click="cancelDelete"
                            class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs rounded-lg font-semibold hover:bg-gray-200 transition">
                            Cancel
                        </button>
                    </div>
                @else
                    <button wire:click="confirmDelete({{ $account->id }})"
                        class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition"
                        title="Remove account">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 10v11m4-11v11m4-11v11"/>
            </svg>
            <p class="text-gray-500 font-medium mb-4">No source accounts added yet.</p>
            <button wire:click="openAddModal"
                class="inline-flex items-center gap-2 bg-appPrimary text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow hover:opacity-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Your First Account
            </button>
        </div>
    @endforelse

    {{-- KYC Block Modal --}}
    @if($showKycBlockModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" wire:key="kyc-block-modal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 mx-auto mb-4">
                    <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">KYC Verification Required</h3>
                <p class="text-sm text-gray-500 mb-1">You cannot add source accounts until your</p>
                <p class="text-sm text-gray-700 font-semibold mb-5">
                    KYC and Virtual Account (VAN) are both <span class="text-green-600">Verified</span>.
                </p>
                <div class="space-y-2 text-left bg-gray-50 rounded-xl p-4 mb-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="{{ auth()->user()->kyc_status === 'verified' ? 'text-green-500' : 'text-red-400' }}">
                            {{ auth()->user()->kyc_status === 'verified' ? '✔' : '✘' }}
                        </span>
                        <span class="text-gray-700">KYC Status:
                            <span class="font-semibold {{ auth()->user()->kyc_status === 'verified' ? 'text-green-600' : 'text-red-500' }}">
                                {{ ucfirst(auth()->user()->kyc_status) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="{{ auth()->user()->van_status === 'verified' ? 'text-green-500' : 'text-red-400' }}">
                            {{ auth()->user()->van_status === 'verified' ? '✔' : '✘' }}
                        </span>
                        <span class="text-gray-700">VAN Status:
                            <span class="font-semibold {{ auth()->user()->van_status === 'verified' ? 'text-green-600' : 'text-red-500' }}">
                                {{ ucfirst(auth()->user()->van_status) }}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="flex gap-3">
                    @if(auth()->user()->kyc_status !== 'verified')
                        <a href="{{ route('merchant.kyc') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-appPrimary text-white py-2.5 rounded-lg font-semibold text-sm hover:opacity-90 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Complete KYC
                        </a>
                    @endif
                    <button wire:click="closeKycBlockModal"
                        wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                        wire:target="closeKycBlockModal"
                        class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                        <span wire:loading.remove wire:target="closeKycBlockModal">
                            <span class="flex items-center gap-2 justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Close
                            </span>
                        </span>

                        <span wire:loading wire:target="closeKycBlockModal">
                            <span class="flex items-center gap-2 justify-center">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Closing...
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Add Account Modal --}}
    @if($showAddModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-start justify-center py-8 px-4" wire:key="add-modal">
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 p-8" @click.outside="$wire.closeAddModal()">
                <div class="absolute top-4 right-4">
                    <button wire:click="closeAddModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-1">Add Source Account</h2>

                <div class="flex items-center gap-2 mb-6">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-600 text-white">1 Details</span>
                    <span class="text-gray-300 text-xs">›</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-500">2 OTP</span>
                    <span class="text-gray-300 text-xs">›</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-500">3 Review</span>
                </div>

                <form wire:submit.prevent="addAccount" class="space-y-4">
                    {{-- Account Holder Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                        <input type="text" wire:model.defer="account_holder_name"
                            placeholder="As per bank records"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary sm:text-sm @error('account_holder_name') border-red-400 @enderror">
                        @error('account_holder_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                        <input type="text" wire:model.defer="bank_name"
                            placeholder="e.g. HDFC Bank"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary sm:text-sm @error('bank_name') border-red-400 @enderror">
                        @error('bank_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Document Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                        <select wire:model.live="document_type"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary sm:text-sm @error('document_type') border-red-400 @enderror">
                            <option value="">Select document type</option>
                            <option value="statement">Bank Statement</option>
                            <option value="cancel_cheque">Cancelled Cheque</option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Document Upload --}}
                    @include('merchant.kyc._file-uploader', [
                        'wireModel'   => 'document_file',
                        'xRef'        => 'document_file_input',
                        'label'       => $document_type === 'statement' ? 'Statement' : ($document_type === 'cancel_cheque' ? 'Cancel Cheque' : 'Statement / Cancel Cheque'),
                        'required'    => true,
                        'existingUrl' => null,
                        'errorKey'    => 'document_file',
                        'fileSize'    => 12,
                    ])

                    {{-- Account Number --}}
                    <div x-data="{
                        accNum: '',
                        get error() {
                            if (this.accNum === '') return '';
                            if (!/^[A-Z0-9]+$/.test(this.accNum)) return 'Only alphanumeric characters are allowed.';
                            if (this.accNum.length < 9) return 'Minimum 9 characters required.';
                            if (this.accNum.length > 17) return 'Maximum 17 characters allowed.';
                            return '';
                        }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <input type="text" wire:model.defer="account_number"
                            x-model="accNum"
                            @input="accNum = $event.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 17); $event.target.value = accNum"
                            placeholder="Enter account number"
                            maxlength="17"
                            :class="error ? 'border-red-400' : ''"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary sm:text-sm font-mono @error('account_number') border-red-400 @enderror">
                        <p x-show="error" x-text="error" class="mt-1 text-xs text-red-600"></p>
                        @error('account_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- IFSC Code --}}
                    <div x-data="{
                        ifsc: '',
                        get error() {
                            if (this.ifsc === '') return '';
                            if (this.ifsc.length < 11) return 'IFSC code must be 11 characters.';
                            if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(this.ifsc)) return 'Invalid IFSC format. e.g. HDFC0001234';
                            return '';
                        }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                        <input type="text" wire:model.defer="ifsc_code"
                            x-model="ifsc"
                            @input="ifsc = $event.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 11); $event.target.value = ifsc"
                            placeholder="e.g. HDFC0001234"
                            maxlength="11"
                            :class="error ? 'border-red-400' : ''"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary sm:text-sm font-mono uppercase @error('ifsc_code') border-red-400 @enderror">
                        <p x-show="error" x-text="error" class="mt-1 text-xs text-red-600"></p>
                        @error('ifsc_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-appPrimary text-white py-2.5 rounded-lg font-semibold text-sm shadow hover:opacity-90 transition"
                            wire:loading.attr="disabled" wire:loading.class="opacity-70">
                            <span wire:loading.remove wire:target="addAccount">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Submit for Review
                                </span>
                            </span>
                            <span wire:loading wire:target="addAccount">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Submitting...
                                </span>
                            </span>
                        </button>
                        <button type="button" wire:click="closeAddModal"
                            wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                            wire:target="closeAddModal"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                            <span wire:loading.remove wire:target="closeAddModal">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel
                                </span>
                            </span>
                            <span wire:loading wire:target="closeAddModal">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Closing...
                                </span> 
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- OTP Verification Modal --}}
    @if($showOTPModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 px-4" x-data="{
            resendTimer: @entangle('resendTimer'),
            resendInterval: null,
            startTimer() {
                if (this.resendInterval) clearInterval(this.resendInterval);
                if (this.resendTimer > 0) {
                    this.resendInterval = setInterval(() => {
                        if (this.resendTimer > 0) this.resendTimer--;
                        if (this.resendTimer <= 0 && this.resendInterval) {
                            clearInterval(this.resendInterval);
                            this.resendInterval = null;
                        }
                    }, 1000);
                }
            }
        }" x-init="startTimer(); $watch('resendTimer', (value) => {
            if (value > 0 && !resendInterval) startTimer();
            if (value <= 0 && resendInterval) {
                clearInterval(resendInterval);
                resendInterval = null;
            }
        })">
            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-indigo-100 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.20)]">
                <div class="bg-gradient-to-r from-indigo-600 to-cyan-600 px-6 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11V7m0 0l-3 3m3-3l3 3m6 3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold leading-tight">
                                {{ $otpAction === 'delete' ? 'Verify Delete Request' : 'Verify Source Account' }}
                            </h3>
                            <p class="text-xs text-indigo-100">
                                {{ $otpAction === 'delete' ? 'Enter the OTP to delete this source account.' : 'Enter the OTP to submit this source account.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <p class="mb-4 text-sm text-slate-600">
                        We have sent an OTP to your registered mobile number. Verify to continue this action.
                    </p>

                    <div>
                        <label for="otp" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">One-Time Password</label>
                        <input
                            type="text"
                            id="otp"
                            wire:model.defer="otp"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="mt-2 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-center text-2xl font-black tracking-[0.35em] text-slate-900 placeholder:tracking-normal placeholder:font-medium placeholder:text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            placeholder="000000"
                        >
                        @error('otp') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-4 flex items-center justify-between rounded-xl border border-indigo-100 bg-indigo-50 px-3 py-2">
                        <span class="text-xs font-medium text-indigo-700">Did not receive OTP?</span>
                        <template x-if="resendTimer > 0">
                            <span class="text-xs font-semibold text-indigo-700">Resend in <span x-text="resendTimer"></span>s</span>
                        </template>
                        <template x-if="resendTimer <= 0">
                            <button type="button" wire:click="resendActionOtp" class="text-xs font-semibold text-indigo-700 hover:text-indigo-900">
                                Resend OTP
                            </button>
                        </template>
                    </div>

                    <div class="mt-6 flex flex-wrap justify-end gap-2">
                        <button
                            wire:click="cancelOtpVerification"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                            wire:target="cancelOtpVerification"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700"
                        >
                            <span wire:loading.remove wire:target="cancelOtpVerification">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel
                                </span>
                            </span>
                            <span wire:loading wire:target="cancelOtpVerification">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Closing...
                                </span>
                            </span>
                        </button>
                        <button
                            wire:click="verifyActionOtp"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                            wire:target="verifyActionOtp"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-appPrimary px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            <span wire:loading.remove wire:target="verifyActionOtp">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Verify & Continue
                                </span>
                            </span>
                            <span wire:loading wire:target="verifyActionOtp">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Verifying...
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

