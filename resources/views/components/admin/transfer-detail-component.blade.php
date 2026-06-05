<div>
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 overflow-y-auto py-8 px-4" style="display: none;">
        <div @click.away="$wire.closeModal()" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-fadeIn">

            @if($payout)
                @php
                    $statusValue = $payout->source_status ?? $payout->status ?? 'unknown';
                    $statusColor = match($statusValue) {
                        'success'      => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700'],
                        'failed'       => ['bg' => 'bg-red-500',    'light' => 'bg-red-50',    'text' => 'text-red-700'],
                        'pending'      => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                        'initiated'    => ['bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'text' => 'text-blue-700'],
                        'processed'    => ['bg' => 'bg-indigo-500', 'light' => 'bg-indigo-50', 'text' => 'text-indigo-700'],
                        'send_to_bank' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700'],
                        default        => ['bg' => 'bg-gray-500',   'light' => 'bg-gray-50',   'text' => 'text-gray-700'],
                    };
                    $transactionDate = $payout->txn_at ?? $payout->initiated_at;
                    $formatDate = static function ($value) {
                        return $value ? \Carbon\Carbon::parse($value)->format('d M Y, h:i A') : 'N/A';
                    };
                @endphp

                <div class="{{ $statusColor['bg'] }} rounded-t-2xl px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Transfer Details</p>
                            <p class="text-white font-mono font-bold text-sm sm:text-base break-all">{{ $payout->reference_no ?? $payout->transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                {{ str_replace('_', ' ', $statusValue) }}
                            </span>
                            <button @click="$wire.closeModal()" class="text-white/70 hover:text-white transition" title="Close">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="{{ $statusColor['light'] }} px-4 sm:px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Amount Transferred</p>
                            <p class="text-2xl sm:text-3xl font-bold {{ $statusColor['text'] }}">&#8377;{{ number_format((float) $payout->amount, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-0.5">Date</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700">{{ $formatDate($transactionDate) }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-3 border-t border-black/5">
                        <div class="bg-white/60 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-400 mb-0.5">Transaction ID</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 font-mono break-all">{{ $payout->transaction_id ?? $payout->reference_no ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white/60 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-400 mb-0.5">Bank</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700">{{ $payout->bank_name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 border border-gray-200 shadow-sm col-span-2 sm:col-span-1">
                            <p class="text-xs text-gray-400 mb-0.5">Account</p>
                            <p class="text-xs sm:text-sm font-bold {{ $statusColor['text'] }} font-mono">{{ $payout->account_number ?? $payout->account_no ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-5 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Transaction Info</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">UTR</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->utr ?? 'N/A' }}</p>
                            </div>   
                            <div>
                                <p class="text-xs text-gray-400">Transaction ID</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono break-all">{{ $payout->transaction_id ?? $payout->reference_no ?? 'N/A' }}</p>
                            </div>                         
                            <div>
                                <p class="text-xs text-gray-400">Batch ID</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $payout->batch_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">SprintNXT Txn ID</p>
                                <p class="text-sm font-medium text-gray-800 font-mono break-all">{{ $payout->sprintnxt_txn_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">SprintNXT Logger ID</p>
                                <p class="text-sm font-medium text-gray-800 font-mono break-all">{{ $payout->sprintnxt_logger_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Status</p>
                                <p class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $statusValue)) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Initiated From</p>
                                <p class="text-sm font-medium text-gray-800 uppercase">{{ $payout->initiated_from ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Initiated At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($payout->initiated_at ?? null) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Processed At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($payout->processed_at ?? null) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Created At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($payout->created_at ?? null) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Updated At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($payout->updated_at ?? null) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Payout Amount Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Amount</p>
                                <p class="text-sm font-semibold text-gray-800">&#8377;{{ number_format((float) ($payout->amount ?? 0), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Fee</p>
                                <p class="text-sm font-medium text-gray-800">&#8377;{{ number_format((float) ($payout->fee ?? 0), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Total Amount</p>
                                <p class="text-sm font-semibold text-gray-800">&#8377;{{ number_format((float) ($payout->total_amount ?? (($payout->amount ?? 0) + ($payout->fee ?? 0))), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Mode</p>
                                <p class="text-sm font-medium text-gray-800 uppercase">{{ $payout->mode ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Beneficiary And Bank Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Account Holder</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $payout->account_holder ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Mobile</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->mobile ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Email</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Account Number</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->account_number ?? $payout->account_no ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">IFSC Code</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payout->ifsc_code ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Bank Name</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->bank_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Branch Name</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->branch_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Branch Code</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $payout->branch_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Address Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div class="sm:col-span-2">
                                <p class="text-xs text-gray-400">Beneficiary Address</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->beneficiary_address ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">City</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">State</p>
                                <p class="text-sm font-medium text-gray-800">{{ $payout->state ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Pincode</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $payout->pincode ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Merchant Details</p>
                        @if($merchant)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs text-gray-400">Merchant Full Name</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $merchant->full_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Merchant ID</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $merchant->merchant_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Phone Number</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $merchant->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Email</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $merchant->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-gray-500 bg-gray-50 rounded-lg px-3 py-2">Merchant info not available.</div>
                        @endif
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Additional Info</p>
                        <div class="grid grid-cols-1 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Remarks</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->remarks ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Purpose</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->purpose ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Narration</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->narration ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Initiated From</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $payout->initiated_from ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!--Raw Data Section-->
                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Raw Payload</p>
                        <div class="bg-gray-900 rounded-xl p-3 sm:p-4 overflow-x-auto">
                            <pre class="text-[11px] sm:text-xs text-gray-100 whitespace-pre-wrap break-words font-mono">{{ (is_array($payout->raw_payload) || is_object($payout->raw_payload)) ? json_encode($payout->raw_payload, JSON_PRETTY_PRINT) : ($payout->raw_payload ?? 'N/A') }}</pre>
                        </div>
                    </div>

                    <!-- Manual Webhook or Payment Retry Section (if applicable) -->
                    <hr class="border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Manual Webhook or Payment Retry</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                wire:click="triggerWebhook"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition disabled:opacity-60"
                                title="Manually trigger webhook for this transaction"
                            >
                                <svg wire:loading.remove wire:target="triggerWebhook" class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011.9 9.8M15 13l-3.16-.66A4 4 0 0111 9.88V9a4 4 0 014-4h1m0 0a4 4 0 014 4v1a4 4 0 01-3.16 3.74L15 13"/>
                                </svg>
                                <svg wire:loading wire:target="triggerWebhook" class="w-4 h-4 animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="triggerWebhook">Trigger Webhook</span>
                                <span wire:loading wire:target="triggerWebhook">Processing...</span>
                            </button>

                            @if(blank($payout->sprintnxt_txn_id) || blank($payout->sprintnxt_logger_id))
                                <button
                                    wire:click="retryPayment"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition disabled:opacity-60"
                                    title="Retry this payment" 
                                    >
                                    <svg wire:loading.remove wire:target="retryPayment" class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <svg wire:loading wire:target="retryPayment" class="w-4 h-4 animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="retryPayment">Retry Payment</span>
                                    <span wire:loading wire:target="retryPayment">Processing...</span>

                                </button>
                            @endif
                        </div>
                    </div>
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

                        <button @click="$wire.closeModal()"
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
                    <p class="text-gray-500 font-medium">Transaction details not found.</p>
                    <button @click="$wire.closeModal()" class="mt-4 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">Close</button>
                </div>
            @endif
        </div>
    </div>
</div>
