<div>
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 overflow-y-auto py-8 px-4" style="display: none;">
        <div @click.away="$wire.closeModal()" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-fadeIn">

            @if($deposit)
                @php
                    $statusValue = $deposit->processing_status ?? 'unknown';
                    $statusColor = match($statusValue) {
                        'success'          => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700'],
                        'duplicate'        => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                        'technical_reject' => ['bg' => 'bg-red-500',    'light' => 'bg-red-50',    'text' => 'text-red-700'],
                        default        => ['bg' => 'bg-gray-500',   'light' => 'bg-gray-50',   'text' => 'text-gray-700'],
                    };
                    $formatDate = static function ($value) {
                        return $value ? \Carbon\Carbon::parse($value)->format('d M Y, h:i A') : 'N/A';
                    };
                    $rawPayload = $deposit->raw_payload;
                    if (is_array($rawPayload) || is_object($rawPayload)) {
                        $rawPayload = json_encode($rawPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    }
                @endphp

                <div class="{{ $statusColor['bg'] }} rounded-t-2xl px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Inward Deposit Details</p>
                            <p class="text-white font-mono font-bold text-sm sm:text-base break-all">{{ $deposit->alert_sequence_no ?? 'N/A' }}</p>
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
                            <p class="text-xs text-gray-500 mb-0.5">Inward Amount</p>
                            <p class="text-2xl sm:text-3xl font-bold {{ $statusColor['text'] }}">&#8377;{{ number_format((float) ($deposit->amount ?? 0), 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-0.5">Transaction Date</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700">{{ $formatDate($deposit->transaction_date ?? null) }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-3 border-t border-black/5">
                        <div class="bg-white/60 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-400 mb-0.5">Alert Sequence No</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 font-mono break-all">{{ $deposit->alert_sequence_no ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white/60 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-400 mb-0.5">Debit/Credit</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase">{{ $deposit->debit_credit ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 border border-gray-200 shadow-sm col-span-2 sm:col-span-1">
                            <p class="text-xs text-gray-400 mb-0.5">Account Number</p>
                            <p class="text-xs sm:text-sm font-bold {{ $statusColor['text'] }} font-mono">{{ $deposit->account_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-5 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Deposit Info</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Alert Sequence No</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono break-all">{{ $deposit->alert_sequence_no ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">User Reference Number</p>
                                <p class="text-sm font-medium text-gray-800 font-mono break-all">{{ $deposit->user_reference_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Mnemonic Code</p>
                                <p class="text-sm font-medium text-gray-800">{{ $deposit->mnemonic_code ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Processing Status</p>
                                <p class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $statusValue)) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Amount</p>
                                <p class="text-sm font-semibold text-gray-800">&#8377;{{ number_format((float) ($deposit->amount ?? 0), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Transaction Date</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($deposit->transaction_date ?? null) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Value Date</p>
                                <p class="text-sm font-medium text-gray-800">{{ $deposit->value_date ? \Carbon\Carbon::parse($deposit->value_date)->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Created At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($deposit->created_at ?? null) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Updated At</p>
                                <p class="text-sm font-medium text-gray-800">{{ $formatDate($deposit->updated_at ?? null) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Remitter Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Remitter Name</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $deposit->remitter_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Remitter Account</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $deposit->remitter_account ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Remitter Bank</p>
                                <p class="text-sm font-medium text-gray-800">{{ $deposit->remitter_bank ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Virtual Account</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $deposit->virtual_account ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Banking Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Account Number</p>
                                <p class="text-sm font-semibold text-gray-800 font-mono">{{ $deposit->account_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">IFSC Code</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $deposit->ifsc_code ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Cheque No</p>
                                <p class="text-sm font-medium text-gray-800 font-mono">{{ $deposit->cheque_no ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Debit Credit</p>
                                <p class="text-sm font-medium text-gray-800 uppercase">{{ $deposit->debit_credit ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Narration Details</p>
                        <div class="grid grid-cols-1 gap-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Transaction Description</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $deposit->transaction_description ?? 'N/A' }}</p>
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
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Raw Payload</p>
                        <div class="bg-gray-900 rounded-xl p-3 sm:p-4 overflow-x-auto">
                            <pre class="text-[11px] sm:text-xs text-gray-100 whitespace-pre-wrap break-words font-mono">{{ $rawPayload ?: 'N/A' }}</pre>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex items-center justify-end rounded-b-2xl bg-gray-50">
                    <button @click="$wire.closeModal()" class="px-5 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold transition">
                        Close
                    </button>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Deposit details not found.</p>
                    <button @click="$wire.closeModal()" class="mt-4 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">Close</button>
                </div>
            @endif
        </div>
    </div>
</div>
