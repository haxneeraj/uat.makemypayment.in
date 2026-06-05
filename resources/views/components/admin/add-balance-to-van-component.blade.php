<div>
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 overflow-y-auto py-8 px-4" style="display: none;">
        <div @click.away="$wire.closeModal()" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl animate-fadeIn">

            @if($merchant)
                @php
                    $vanStatus = $van['status'] ?? 'unknown';
                    $vanStatusColor = match($vanStatus) {
                        'active'    => 'bg-green-100 text-green-700',
                        'frozen'    => 'bg-blue-100 text-blue-700',
                        'inactive'  => 'bg-yellow-100 text-yellow-700',
                        'suspended' => 'bg-orange-100 text-orange-700',
                        'closed'    => 'bg-red-100 text-red-700',
                        default     => 'bg-gray-100 text-gray-700',
                    };
                    $merchantStatusColor = match($merchant['status'] ?? 'inactive') {
                        'active'    => 'bg-green-100 text-green-700',
                        'inactive'  => 'bg-yellow-100 text-yellow-700',
                        'suspended' => 'bg-red-100 text-red-700',
                        default     => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                {{-- Header --}}
                <div class="bg-gradient-to-r from-[#1a2870] to-[#4158c0] rounded-t-2xl px-5 sm:px-6 py-4 sm:py-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Add Balance to VAN</p>
                            <p class="text-white font-bold text-base sm:text-lg truncate">{{ $merchant['full_name'] }}</p>
                            <p class="text-white/60 font-mono text-xs mt-0.5">{{ $merchant['merchant_id'] }}</p>
                        </div>
                        <button @click="$wire.closeModal()" class="text-white/70 hover:text-white transition shrink-0 mt-1" title="Close">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Merchant Info --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 bg-gray-50/70">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Merchant Details</p>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-2.5">
                        <div>
                            <p class="text-xs text-gray-400">Full Name</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $merchant['full_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Merchant ID</p>
                            <p class="text-sm font-semibold text-gray-800 font-mono">{{ $merchant['merchant_id'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p class="text-sm font-medium text-gray-800">{{ $merchant['phone'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $merchant['email'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Account Status</p>
                            <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $merchantStatusColor }}">{{ ucfirst($merchant['status'] ?? 'N/A') }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">KYC Status</p>
                            <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full
                                {{ ($merchant['kyc_status'] ?? '') === 'verified' ? 'bg-green-100 text-green-700' : (($merchant['kyc_status'] ?? '') === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($merchant['kyc_status'] ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- VAN Info --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Virtual Account Details</p>
                    @if($van)
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2.5">
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400">VAN Number</p>
                                <p class="text-sm font-bold text-gray-800 font-mono tracking-wider">{{ $van['van'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Account Holder</p>
                                <p class="text-sm font-medium text-gray-800">{{ $van['account_holder'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">IFSC</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $van['ifsc'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">VAN Status</p>
                                <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $vanStatusColor }}">
                                    {{ ucfirst($vanStatus) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">No VAN assigned to this merchant.</p>
                    @endif
                </div>

                {{-- Balance Form --}}
                @if($van)
                <div class="px-5 sm:px-6 py-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Add Balance</p>

                    <div class="space-y-4">
                        {{-- Current Balance (read-only) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Current Balance</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm select-none">₹</span>
                                <input
                                    type="text"
                                    value="{{ number_format($currentBalance, 2) }}"
                                    readonly
                                    class="w-full pl-7 pr-4 py-2.5 rounded-lg bg-gray-100 border border-gray-200 text-gray-700 font-mono text-sm font-semibold cursor-not-allowed focus:outline-none"
                                />
                            </div>
                        </div>

                        {{-- Amount to Add --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Amount to Add <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm select-none">₹</span>
                                <input
                                    wire:model.live="addAmount"
                                    type="number"
                                    min="1"
                                    step="0.01"
                                    placeholder="0.00"
                                    class="w-full pl-7 pr-4 py-2.5 rounded-lg border {{ $errors->has('addAmount') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' }} text-gray-800 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-[#2b3990]/30 focus:border-[#2b3990] transition"
                                />
                            </div>
                            @error('addAmount')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Balance Preview --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">New Balance (After Adding)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 font-semibold text-sm select-none
                                    {{ (is_numeric($addAmount) && (float)$addAmount > 0) ? 'text-green-600' : 'text-gray-400' }}">₹</span>
                                <input
                                    type="text"
                                    value="{{ number_format($this->newBalance, 2) }}"
                                    readonly
                                    class="w-full pl-7 pr-4 py-2.5 rounded-lg border font-mono text-sm font-bold cursor-not-allowed focus:outline-none
                                        {{ (is_numeric($addAmount) && (float)$addAmount > 0) ? 'border-green-300 bg-green-50 text-green-700' : 'border-gray-200 bg-gray-50 text-gray-500' }}"
                                />
                            </div>
                            @if(is_numeric($addAmount) && (float)$addAmount > 0)
                                <p class="text-xs text-green-600 mt-1 font-medium">
                                    +₹{{ number_format((float)$addAmount, 2) }} will be credited to the VAN.
                                </p>
                            @endif
                        </div>

                        {{-- Remarks --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Remarks <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea
                                wire:model="remarks"
                                rows="2"
                                placeholder="Reason for balance addition..."
                                class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-[#2b3990]/30 focus:border-[#2b3990] transition"
                            ></textarea>
                            @error('remarks')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- Footer --}}
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 rounded-b-2xl bg-gray-50 flex items-center justify-end gap-2">

                    <div class="flex items-center gap-2 shrink-0">
                        @if($van)
                            <button
                                wire:click="addBalance"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-[#1a2870] hover:bg-[#0f1a55] text-white text-sm font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                                <svg wire:loading.remove wire:target="addBalance" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <svg wire:loading wire:target="addBalance" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="addBalance">Add Balance</span>
                                <span wire:loading wire:target="addBalance">Processing...</span>
                            </button>
                        @endif

                        <button @click="$wire.closeModal()"
                            class="px-5 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition">
                            Close
                        </button>
                    </div>
                </div>

            @else
                {{-- No merchant loaded --}}
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Merchant details not found.</p>
                    <button @click="$wire.closeModal()" class="mt-4 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">Close</button>
                </div>
            @endif

        </div>
    </div>
</div>
