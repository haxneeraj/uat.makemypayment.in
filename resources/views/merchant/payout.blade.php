<div class="relative overflow-hidden rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6">

    {{-- Top Stat Cards --}}
    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Wallet Balance --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gray-900 text-white border border-gray-900 shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a2 2 0 100 4 2 2 0 000-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12h-5"/>
                </svg>
                <span>VAN Balance</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black">
                <livewire:components.merchant-balance-component />
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <button wire:click="openOneTimePayoutModal"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#6c3ec5] text-white text-xs font-bold shadow hover:bg-[#5a33a8] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    One Time Payout
                </button>
                <button wire:click="openBulkPayout"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/90 border border-[#d8c0ff] text-[#6d4ca2] text-xs font-bold shadow-sm hover:bg-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                    Bulk Payout
                </button>
            </div>
        </div>

        {{-- Success Rate --}}
        @php
            $circumference = 2 * M_PI * 28; // r=28 → ~175.93
            $offset = $circumference * (1 - ($successRate / 100));
        @endphp
        <div class="col-span-2 relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1a2870] via-[#2b3990] to-[#4158c0] border border-[#3c4fae] shadow-lg">

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute bottom-0 left-0 w-28 h-28 rounded-full bg-white/5 -translate-x-1/2 translate-y-1/2"></div>

            <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-6 p-5 sm:p-6">

                {{-- Left: info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-indigo-200 mb-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Success Rate</span>
                    </div>

                    <div class="flex items-end gap-3 mb-4">
                        <span class="text-4xl sm:text-5xl font-black text-white leading-none">{{ $successRate }}<span class="text-2xl sm:text-3xl font-bold text-indigo-200">%</span></span>
                        <span class="mb-1 inline-flex items-center gap-1 bg-emerald-400/20 text-emerald-300 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-400/30">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Overall
                        </span>
                    </div>

                    {{-- Segmented progress bar --}}
                    <div class="mb-3">
                        <div class="flex h-2 rounded-full overflow-hidden bg-white/10 gap-0.5">
                            <div class="h-full rounded-l-full bg-emerald-400 transition-all duration-500" style="width: {{ $successRate }}%"></div>
                            <div class="h-full rounded-r-full bg-rose-400/70 transition-all duration-500" style="width: {{ 100 - $successRate }}%"></div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <span class="text-xs text-indigo-200">Success <span class="text-white font-semibold">{{ $successRate }}%</span></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400/70 shrink-0"></span>
                            <span class="text-xs text-indigo-200">Failed / Other <span class="text-white font-semibold">{{ round(100 - $successRate, 1) }}%</span></span>
                        </div>
                    </div>
                </div>

                {{-- Right: SVG Donut chart --}}
                <div class="shrink-0 flex flex-col items-center justify-center">
                    <svg width="100" height="100" viewBox="0 0 80 80" class="-rotate-90">
                        {{-- Track --}}
                        <circle cx="40" cy="40" r="28"
                            fill="none"
                            stroke="rgba(255,255,255,0.1)"
                            stroke-width="8"/>
                        {{-- Failed arc --}}
                        <circle cx="40" cy="40" r="28"
                            fill="none"
                            stroke="rgba(251,113,133,0.55)"
                            stroke-width="8"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $circumference * ($successRate / 100) }}"
                            stroke-linecap="round"/>
                        {{-- Success arc --}}
                        <circle cx="40" cy="40" r="28"
                            fill="none"
                            stroke="#34d399"
                            stroke-width="8"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"
                            stroke-linecap="round"/>
                    </svg>
                    <span class="text-xs text-indigo-200 mt-1 font-semibold tracking-wide">Transactions</span>
                </div>

            </div>
        </div>

        

        {{-- Today's Total Payouts --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-[#6c3ec5] border border-[#5c31b0] shadow-sm">
            <div class="pointer-events-none absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/10"></div>
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-violet-200 mb-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>Today's Volume</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white">₹{{ number_format($todayTotalAmount, 2) }}</div>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-violet-200">Total transactions</span>
                <span class="inline-flex items-center justify-center text-xs bg-white/20 border border-white/30 text-white px-2.5 py-0.5 rounded-full font-bold">{{ $todayTotalTransactionCount }}</span>
            </div>
        </div>

        {{-- Today's Success Payouts --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-emerald-600 to-emerald-700 border border-emerald-600 shadow-sm">
            <div class="pointer-events-none absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/10"></div>
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-emerald-100 mb-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Today's Success</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white">₹{{ number_format($todayTransferredAmount, 2) }}</div>
            <div class="mt-3">
                <span class="inline-flex items-center gap-1 text-xs bg-white/20 border border-white/30 text-white px-2.5 py-0.5 rounded-full font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Transferred successfully
                </span>
            </div>
        </div>

        {{-- Today's Failed Payouts --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-rose-600 to-rose-700 border border-rose-600 shadow-sm">
            <div class="pointer-events-none absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/10"></div>
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-rose-100 mb-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Today's Failed</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white">₹{{ number_format($todayFailedAmount, 2) }}</div>
            <div class="mt-3">
                <span class="inline-flex items-center gap-1 text-xs bg-white/20 border border-white/30 text-white px-2.5 py-0.5 rounded-full font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Failed transactions
                </span>
            </div>
        </div>
    </div>

    {{-- Filters & Tabs --}}
    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6 space-y-4">

        {{-- Tabs --}}
        <div class="relative overflow-hidden rounded-2xl border border-[#e5eaf6] bg-gradient-to-r from-white via-[#f8fbff] to-white p-3">
            <div class="mb-2 flex items-center justify-between px-1">
                <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Quick Filters</p>
                <span class="text-[10px] sm:text-xs text-slate-400">Swipe on mobile</span>
            </div>
            <div class="-mx-1 overflow-x-auto pb-1">
                <div class="inline-flex min-w-max items-center gap-2 px-1 snap-x snap-mandatory">
                    <button wire:click="$set('status', '')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-[#2b3990] {{ !$status ? 'bg-[#2b3990] text-white border-[#2b3990] shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:border-[#cfd8ef] hover:bg-slate-50' }}">
                        <span class="h-2 w-2 rounded-full {{ !$status ? 'bg-white/90' : 'bg-slate-300' }}"></span>
                        All
                    </button>
                    <button wire:click="$set('status', 'success')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-emerald-400 {{ $status == 'success' ? 'bg-emerald-100 text-emerald-700 border-emerald-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-emerald-50 hover:border-emerald-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'success' ? 'bg-emerald-500' : 'bg-emerald-300' }}"></span>
                        Success
                    </button>
                    <button wire:click="$set('status', 'initiated')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-sky-400 {{ $status == 'initiated' ? 'bg-sky-100 text-sky-700 border-sky-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-sky-50 hover:border-sky-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'initiated' ? 'bg-sky-500' : 'bg-sky-300' }}"></span>
                        Initiated
                    </button>
                    <button wire:click="$set('status', 'pending')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-amber-400 {{ $status == 'pending' ? 'bg-amber-100 text-amber-700 border-amber-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-amber-50 hover:border-amber-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'pending' ? 'bg-amber-500' : 'bg-amber-300' }}"></span>
                        Pending
                    </button>
                    <button wire:click="$set('status', 'processed')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-indigo-400 {{ $status == 'processed' ? 'bg-indigo-100 text-indigo-700 border-indigo-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-indigo-50 hover:border-indigo-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'processed' ? 'bg-indigo-500' : 'bg-indigo-300' }}"></span>
                        Processed
                    </button>
                    <button wire:click="$set('status', 'send_to_bank')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-violet-400 {{ $status == 'send_to_bank' ? 'bg-violet-100 text-violet-700 border-violet-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-violet-50 hover:border-violet-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'send_to_bank' ? 'bg-violet-500' : 'bg-violet-300' }}"></span>
                        Send to Bank
                    </button>
                    <button wire:click="$set('status', 'failed')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-rose-400 {{ $status == 'failed' ? 'bg-rose-100 text-rose-700 border-rose-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-rose-50 hover:border-rose-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $status == 'failed' ? 'bg-rose-500' : 'bg-rose-300' }}"></span>
                        Failed
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="searchColumn"
                class="cursor-pointer border border-[#dde2ef] rounded-2xl px-8 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="account_holder">Beneficiary Name</option>
                <option value="account_number">Account Number</option>
                <option value="utr">UTR Number</option>
                <option value="transaction_id">Transfer ID</option>
            </select>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    placeholder="Search...">
            </div>

            @if($search || $dateFrom || $dateTo || $status || $searchColumn !== 'account_holder')
            <button wire:click="clearFilters"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-rose-200 bg-rose-50 text-rose-600 text-sm font-semibold hover:bg-rose-100 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear Filters
            </button>
            @endif
        </div>

        {{-- Note --}}
        <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3 text-sm text-indigo-700">
            <svg class="w-4 h-4 mt-0.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
            </svg>
            <p class="leading-relaxed">
                Click on <span class="inline-block font-mono text-xs bg-white border border-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded mx-0.5 align-middle">Transfer ID</span> to view transfer details.
                To view all transactions, visit the
                <a href="{{ route('merchant.reports') }}" class="font-semibold text-indigo-600 hover:underline">Report Page</a>.
            </p>
        </div>
    </div>

    {{-- Payout Table --}}
    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-2 mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h10.5M8.25 12h10.5m-10.5 5.25h10.5M3.75 6.75h.008v.008H3.75V6.75zm0 5.25h.008v.008H3.75V12zm0 5.25h.008v.008H3.75v-.008z"/>
                </svg>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Today's Payout History</h3>
            </div>
            <select wire:model.live="perPage"
                class="cursor-pointer border border-[#dde2ef] rounded-2xl px-5 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="1">1 per page</option>
                <option value="2">2 per page</option>
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-white text-left">
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Beneficiary</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Bank Details</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Mobile</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">UTR Number</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Transfer ID</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Amount</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Fee</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Total Amount</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Mode</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Initiated At</th>
                        <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payouts as $payout)
                    <tr class="hover:bg-white/90 transition">
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                            <div class="font-medium text-slate-900">{{ $payout->account_holder }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $payout->account_number }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                            <div class="text-slate-700">{{ $payout->bank_name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $payout->ifsc_code }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $payout->mobile }}</td>
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                            @if(!blank($payout->utr))
                                {{ $payout->utr }}
                            @else
                                <span class="text-slate-400 text-xs">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 cursor-pointer text-indigo-600 whitespace-nowrap font-mono text-xs font-semibold"
                            wire:click="$dispatch('openTransferDetailModal', { transferId: '{{ $payout->transaction_id }}' })">
                            {{ $payout->transaction_id }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">₹{{ number_format($payout->amount, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">₹{{ number_format($payout->fee ?? 0, 2) }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">₹{{ number_format($payout->total_amount ?? ($payout->amount + ($payout->fee ?? 0)), 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-semibold uppercase">{{ $payout->mode }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">
                            {{ \Carbon\Carbon::parse($payout->initiated_at)->format('d M Y, h:i A') }}
                            @if($payout->processed_at)
                                <div class="text-slate-400 mt-0.5">Done: {{ \Carbon\Carbon::parse($payout->processed_at)->format('d M Y, h:i A') }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusClass = match($payout->status) {
                                    'success'      => 'bg-emerald-100 text-emerald-700',
                                    'failed'       => 'bg-rose-100 text-rose-700',
                                    'pending'      => 'bg-amber-100 text-amber-700',
                                    'initiated'    => 'bg-sky-100 text-sky-700',
                                    'processed'    => 'bg-indigo-100 text-indigo-700',
                                    'send_to_bank' => 'bg-violet-100 text-violet-700',
                                    default        => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-10 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                            </svg>
                            No payouts found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-100 mt-1">
            @if($payouts->hasPages())
                {{ $payouts->links() }}
            @else
                <div class="flex items-center justify-center gap-2 py-1 text-sm text-slate-500">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Showing all
                    <span class="inline-flex items-center justify-center bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-xs px-2.5 py-0.5 rounded-full">
                        {{ $payouts->total() }}
                    </span>
                    {{ $payouts->total() === 1 ? 'transaction' : 'transactions' }}
                </div>
            @endif
        </div>
    </div>

    @if($showVerificationBlockModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" wire:key="payout-verification-block-modal">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center">
                <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-100 mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Verification Required</h3>
                <p class="text-sm text-slate-500 mb-5">Payout actions are allowed only when account is active and KYC/VAN are verified.</p>
                <div class="space-y-2 text-left bg-slate-50 rounded-2xl p-4 mb-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="{{ auth()->user()->status === 'active' ? 'text-emerald-500' : 'text-rose-400' }}">
                            {{ auth()->user()->status === 'active' ? '✔' : '✘' }}
                        </span>
                        <span class="text-slate-700">Account Status:
                            <span class="font-semibold {{ auth()->user()->status === 'active' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="{{ auth()->user()->kyc_status === 'verified' ? 'text-emerald-500' : 'text-rose-400' }}">
                            {{ auth()->user()->kyc_status === 'verified' ? '✔' : '✘' }}
                        </span>
                        <span class="text-slate-700">KYC Status:
                            <span class="font-semibold {{ auth()->user()->kyc_status === 'verified' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ ucfirst(auth()->user()->kyc_status) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="{{ auth()->user()->van_status === 'verified' ? 'text-emerald-500' : 'text-rose-400' }}">
                            {{ auth()->user()->van_status === 'verified' ? '✔' : '✘' }}
                        </span>
                        <span class="text-slate-700">VAN Status:
                            <span class="font-semibold {{ auth()->user()->van_status === 'verified' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ ucfirst(auth()->user()->van_status) }}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('merchant.kyc') }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-[#2b3990] text-white py-2.5 rounded-2xl font-semibold text-sm hover:opacity-90 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        KYC Page
                    </a>
                    <button wire:click="closeVerificationBlockModal"
                        wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                        wire:target="closeVerificationBlockModal"
                        class="flex-1 bg-slate-100 text-slate-700 py-2.5 rounded-2xl font-semibold text-sm hover:bg-slate-200 transition">
                        <span wire:loading.remove wire:target="closeVerificationBlockModal">
                            <span class="flex items-center gap-2 justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Close
                            </span>
                        </span>
                        <span wire:loading wire:target="closeVerificationBlockModal">
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

    <livewire:components.merchant.one-time-payout-form />
    <livewire:components.transfer-detail-component />
</div>
