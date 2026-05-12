<div>
    @if($showTransferDetailModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 overflow-y-auto py-8 px-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-fadeIn">

                @if($payout)
                    @php
                        $statusColor = match($payout->status) {
                            'success'      => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700',  'badge' => 'bg-green-100'],
                            'failed'       => ['bg' => 'bg-red-500',    'light' => 'bg-red-50',    'text' => 'text-red-700',    'badge' => 'bg-red-100'],
                            'pending'      => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'badge' => 'bg-yellow-100'],
                            'initiated'    => ['bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'text' => 'text-blue-700',   'badge' => 'bg-blue-100'],
                            'processed'    => ['bg' => 'bg-indigo-500', 'light' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'badge' => 'bg-indigo-100'],
                            'send_to_bank' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700', 'badge' => 'bg-purple-100'],
                            default        => ['bg' => 'bg-gray-500',   'light' => 'bg-gray-50',   'text' => 'text-gray-700',   'badge' => 'bg-gray-100'],
                        };
                    @endphp

                    {{-- Header Banner --}}
                    <div class="{{ $statusColor['bg'] }} rounded-t-2xl px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Transfer Details</p>
                                <p class="text-white font-mono font-bold text-sm sm:text-base break-all">{{ $payout->transaction_id }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                    {{ str_replace('_', ' ', $payout->status) }}
                                </span>
                                <button wire:click="close" class="text-white/70 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Amount Hero --}}
                    <div class="{{ $statusColor['light'] }} px-4 sm:px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Amount Transferred</p>
                                <p class="text-2xl sm:text-3xl font-bold {{ $statusColor['text'] }}">₹{{ number_format($payout->amount, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-0.5">Mode</p>
                                <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full uppercase">{{ $payout->mode }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-black/5">
                            <div class="bg-white/60 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-400 mb-0.5">Fee</p>
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">₹{{ number_format($payout->fee ?? 0, 2) }}</p>
                            </div>
                            <div class="bg-white/60 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-400 mb-0.5">Amount</p>
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">₹{{ number_format($payout->amount, 2) }}</p>
                            </div>
                            <div class="bg-white rounded-lg px-3 py-2 border border-gray-200 shadow-sm">
                                <p class="text-xs text-gray-400 mb-0.5">Total Deducted</p>
                                <p class="text-xs sm:text-sm font-bold {{ $statusColor['text'] }}">₹{{ number_format($payout->total_amount ?? ($payout->amount + ($payout->fee ?? 0)), 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 sm:px-6 py-5 space-y-5">

                        {{-- Transaction Info --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Transaction Info</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs text-gray-400">UTR Number</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->utr ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Initiated At</p>
                                    <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($payout->initiated_at)->format('d M Y, h:i A') }}</p>
                                </div>
                                @if($payout->processed_at)
                                <div>
                                    <p class="text-xs text-gray-400">Processed At</p>
                                    <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($payout->processed_at)->format('d M Y, h:i A') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Beneficiary Info --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Beneficiary Details</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs text-gray-400">Account Holder</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $payout->account_holder }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Mobile</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->mobile }}</p>
                                </div>
                                @if($payout->email)
                                <div>
                                    <p class="text-xs text-gray-400">Email</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->email }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-xs text-gray-400">Account Number</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->account_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">IFSC Code</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->ifsc_code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Bank Name</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->bank_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Branch</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->branch_name }}</p>
                                </div>
                                @if($payout->branch_code)
                                <div>
                                    <p class="text-xs text-gray-400">Branch Code</p>
                                    <p class="text-sm font-medium text-gray-800 font-mono">{{ $payout->branch_code }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Address --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Address</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-400">Beneficiary Address</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->beneficiary_address }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">City</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->city }}</p>
                                </div>
                                @if($payout->state)
                                <div>
                                    <p class="text-xs text-gray-400">State</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $payout->state }}</p>
                                </div>
                                @endif
                                @if($payout->pincode)
                                <div>
                                    <p class="text-xs text-gray-400">Pincode</p>
                                    <p class="text-sm font-medium text-gray-800 font-mono">{{ $payout->pincode }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Remarks / Purpose / Narration --}}
                        @if($payout->remarks || $payout->purpose || $payout->narration)
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Additional Info</p>
                                <div class="space-y-3">
                                    @if($payout->remarks)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-1">Remarks</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->remarks }}</p>
                                    </div>
                                    @endif
                                    @if($payout->purpose)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-1">Purpose</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->purpose }}</p>
                                    </div>
                                    @endif
                                    @if($payout->narration)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-1">Narration</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->narration }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between rounded-b-2xl bg-gray-50 gap-3">

                        {{-- Refresh feedback --}}
                        <div class="flex-1 text-sm">
                            @if($refreshMessage)
                                @if($refreshMessage['type'] === 'success')
                                    <span class="text-green-600 font-medium">&#10003; {{ $refreshMessage['text'] }}</span>
                                @else
                                    <span class="text-red-500 font-medium">&#10005; {{ $refreshMessage['text'] }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                wire:click="refreshStatus"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition disabled:opacity-60"
                                title="Re-fetch status from SprintNXT"
                            >
                                <svg wire:loading.remove wire:target="refreshStatus" class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <svg wire:loading wire:target="refreshStatus" class="w-4 h-4 animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="refreshStatus">Refresh Status</span>
                                <span wire:loading wire:target="refreshStatus">Fetching...</span>
                            </button>

                            <button wire:click="close"
                                    class="px-5 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold transition">
                                Close
                            </button>
                        </div>
                    </div>

                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Payout not found</p>
                        <button wire:click="close" class="mt-4 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">Close</button>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
