<div>
    @if($showDepositDetailModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 overflow-y-auto py-8 px-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-fadeIn">

                @if($deposit)
                    @php
                        $sc = match($deposit->processing_status) {
                            'success'          => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'text' => 'text-green-700'],
                            'duplicate'        => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                            'technical_reject' => ['bg' => 'bg-red-500',    'light' => 'bg-red-50',    'text' => 'text-red-700'],
                            default            => ['bg' => 'bg-gray-500',   'light' => 'bg-gray-50',   'text' => 'text-gray-700'],
                        };
                    @endphp

                    {{-- Header Banner --}}
                    <div class="{{ $sc['bg'] }} rounded-t-2xl px-6 py-5 flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Deposit Details</p>
                            <p class="text-white font-mono font-bold text-base">{{ $deposit->alert_sequence_no }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                {{ ucfirst(str_replace('_', ' ', $deposit->processing_status)) }}
                            </span>
                            <button wire:click="close" class="text-white/70 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Amount Hero --}}
                    <div class="{{ $sc['light'] }} px-6 py-4 flex items-center justify-between border-b border-gray-100">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Amount Received</p>
                            <p class="text-3xl font-bold {{ $sc['text'] }}">₹{{ number_format($deposit->amount, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-0.5">Type</p>
                            <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                                {{ $deposit->mnemonic_code ?? $deposit->debit_credit }}
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-6">

                        {{-- Transaction Info --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Transaction Info</p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs text-gray-400">Transaction ID</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $deposit->alert_sequence_no }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">User Reference No</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $deposit->user_reference_number ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Transaction Date</p>
                                    <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($deposit->transaction_date)->format('d M Y, h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Value Date</p>
                                    <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($deposit->value_date)->format('d M Y') }}</p>
                                </div>
                                @if($deposit->cheque_no)
                                <div>
                                    <p class="text-xs text-gray-400">Cheque No</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $deposit->cheque_no }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        

                        <hr class="border-gray-100">

                        {{-- Account Info --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Account Info</p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs text-gray-400">Virtual Account</p>
                                    <p class="text-sm font-semibold text-gray-800 font-mono">{{ $deposit->virtual_account ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Debit / Credit</p>
                                    <span class="inline-block bg-gray-100 text-gray-700 text-xs font-bold px-2 py-0.5 rounded uppercase">{{ $deposit->debit_credit }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($deposit->transaction_description)
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Description</p>
                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $deposit->transaction_description }}</p>
                            </div>
                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end rounded-b-2xl bg-gray-50">
                        <button wire:click="close"
                                class="px-5 py-2 rounded-lg bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold transition">
                            Close
                        </button>
                    </div>

                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Deposit record not found</p>
                        <button wire:click="close" class="mt-4 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">Close</button>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
