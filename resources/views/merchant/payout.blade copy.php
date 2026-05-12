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
                <span>Wallet Balance</span>
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

        {{-- Today's Payouts --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-[#6c3ec5] border border-[#6c3ec5] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-4-8h8M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/>
                </svg>
                <span>Today's Payouts</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">₹{{ number_format($todayStats, 2) }}</div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-xs text-white">Total transactions</span>
                <span class="text-xs bg-white text-[#6d4ca2] px-3 py-1 rounded-full font-bold border border-[#e6e872]">{{ $payouts->total() }}</span>
            </div>
        </div>

        {{-- Success Rate --}}
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#2b3990] via-[#3347ac] to-[#5164c4] border border-[#3c4fae] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-indigo-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Success Rate</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $successRate }}%</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/15 border border-white/25 text-indigo-100 text-xs font-semibold">
                Transactions processed successfully
            </div>
            <div class="mt-3 h-1.5 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full rounded-full bg-white/70" style="width: {{ $successRate }}%"></div>
            </div>
        </div>
    </div>

    {{-- Filters & Tabs --}}
    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6 space-y-4">

        {{-- Tabs --}}
        <div class="flex items-center gap-2">
            <button wire:click="$set('status', '')"
                class="px-4 py-2 rounded-2xl text-sm font-semibold transition {{ !$status ? 'bg-[#2b3990] text-white shadow-md' : 'bg-white border border-[#e2e6f3] text-slate-600 hover:bg-indigo-50' }}">
                All Transactions
            </button>
            <button wire:click="$set('status', 'initiated')"
                class="px-4 py-2 rounded-2xl text-sm font-semibold transition {{ $status == 'initiated' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white border border-[#e2e6f3] text-slate-600 hover:bg-indigo-50' }}">
                Initiated Transactions
            </button>
            <button wire:click="$set('status', 'failed')"
                class="px-4 py-2 rounded-2xl text-sm font-semibold transition {{ $status == 'failed' ? 'bg-rose-600 text-white shadow-md' : 'bg-white border border-[#e2e6f3] text-slate-600 hover:bg-rose-50' }}">
                Failed Transactions
            </button>
            <button wire:click="$set('status', 'success')"
                class="px-4 py-2 rounded-2xl text-sm font-semibold transition {{ $status == 'success' ? 'bg-green-600 text-white shadow-md' : 'bg-white border border-[#e2e6f3] text-slate-600 hover:bg-green-50' }}">
                Success Transactions
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="searchColumn"
                class="border border-[#dde2ef] rounded-2xl px-3 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="account_holder">Beneficiary Name</option>
                <option value="utr">UTR Number</option>
                <option value="transaction_id">Transfer ID</option>
                <option value="mobile">Mobile</option>
                <option value="bank_name">Bank Name</option>
            </select>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    placeholder="Search...">
            </div>

            {{-- Date Range Picker --}}
            <div x-data="payoutRangePicker()" x-on:payout-filters-cleared.window="clearRange()" class="relative">
                <div class="flex items-center gap-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="text" x-model="display" @click="open = true" readonly
                            class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 cursor-pointer w-56 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            placeholder="Select Date Range">
                    </div>
                    <button x-show="display" @click.stop="clearRange()" class="text-slate-400 hover:text-slate-600 text-base leading-none" title="Clear">&#x2715;</button>
                </div>

                <div x-show="open" @click.outside="open = false" x-cloak
                    class="absolute z-50 mt-2 bg-white shadow-2xl rounded-2xl p-4 flex gap-4"
                    style="min-width: 640px; left: 0;">
                    <div class="w-[140px] shrink-0 space-y-1 border-r border-gray-100 pr-4">
                        <template x-for="preset in presets" :key="preset.label">
                            <button @click="applyPreset(preset)"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs hover:bg-indigo-50 text-gray-700"
                                x-text="preset.label"></button>
                        </template>
                    </div>
                    <div class="flex gap-6 flex-1">
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-2">
                                <button @click="prevMonth()" class="p-1 hover:bg-gray-100 rounded text-xs">&#9664;</button>
                                <span class="text-xs font-semibold text-gray-700" x-text="monthYear(currentMonth)"></span>
                                <div class="w-6"></div>
                            </div>
                            <div class="grid grid-cols-7 text-[10px] text-gray-400 text-center mb-1">
                                <template x-for="d in daysShort" :key="d"><div x-text="d"></div></template>
                            </div>
                            <div class="grid grid-cols-7 gap-px">
                                <template x-for="(blank, i) in Array.from({length: blanks(currentMonth)})" :key="'lb'+i"><div></div></template>
                                <template x-for="day in days(currentMonth)" :key="'ld'+day">
                                    <div @click="select(day, currentMonth)" :class="dayClass(day, currentMonth)"
                                        class="p-1 text-center text-[11px] rounded cursor-pointer transition-colors" x-text="day"></div>
                                </template>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-2">
                                <div class="w-6"></div>
                                <span class="text-xs font-semibold text-gray-700" x-text="monthYear(nextMonthObj())"></span>
                                <button @click="nextMonth()" class="p-1 hover:bg-gray-100 rounded text-xs">&#9654;</button>
                            </div>
                            <div class="grid grid-cols-7 text-[10px] text-gray-400 text-center mb-1">
                                <template x-for="d in daysShort" :key="d"><div x-text="d"></div></template>
                            </div>
                            <div class="grid grid-cols-7 gap-px">
                                <template x-for="(blank, i) in Array.from({length: blanks(nextMonthObj())})" :key="'rb'+i"><div></div></template>
                                <template x-for="day in days(nextMonthObj())" :key="'rd'+day">
                                    <div @click="select(day, nextMonthObj())" :class="dayClass(day, nextMonthObj())"
                                        class="p-1 text-center text-[11px] rounded cursor-pointer transition-colors" x-text="day"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
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
        <div class="flex items-center gap-2.5 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-2.5 text-sm text-indigo-700">
            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
            </svg>
            Click on <span class="font-mono text-xs bg-white border border-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded mx-1">Transfer ID</span> to view transfer details.
        </div>
    </div>

    @push('scripts')
    <script>
    function payoutRangePicker() {
        return {
            open: false,
            start: null,
            end: null,
            display: '',
            currentMonth: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            daysShort: ['Su','Mo','Tu','We','Th','Fr','Sa'],
            presets: [
                { label: 'Today',          days: 0 },
                { label: 'Yesterday',      days: 1 },
                { label: 'Last 7 Days',    days: 7 },
                { label: 'Last 30 Days',   days: 30 },
                { label: 'This Month',     type: 'month' },
                { label: 'Previous Month', type: 'prev-month' },
            ],
            monthYear(date) {
                return date.toLocaleString('default', { month: 'long', year: 'numeric' });
            },
            nextMonthObj() {
                let d = new Date(this.currentMonth);
                d.setMonth(d.getMonth() + 1);
                return d;
            },
            days(date) {
                return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            },
            blanks(date) {
                return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
            },
            select(day, baseDate) {
                let selected = new Date(baseDate.getFullYear(), baseDate.getMonth(), day);
                if (!this.start || this.end) {
                    this.start = selected;
                    this.end = null;
                } else {
                    if (selected < this.start) {
                        this.end = this.start;
                        this.start = selected;
                    } else {
                        this.end = selected;
                    }
                    this.apply();
                }
            },
            dayClass(day, baseDate) {
                let d = new Date(baseDate.getFullYear(), baseDate.getMonth(), day);
                if (this.start && d.getTime() === this.start.getTime()) return 'bg-[#2b3990] text-white';
                if (this.end   && d.getTime() === this.end.getTime())   return 'bg-[#2b3990] text-white';
                if (this.start && this.end && d > this.start && d < this.end) return 'bg-indigo-100 text-indigo-800';
                return 'hover:bg-gray-100 text-gray-700';
            },
            applyPreset(p) {
                let today = new Date(); today.setHours(0,0,0,0);
                if (p.days !== undefined) {
                    this.end = new Date(today);
                    let s = new Date(today);
                    s.setDate(today.getDate() - p.days);
                    this.start = s;
                } else if (p.type === 'month') {
                    this.start = new Date(today.getFullYear(), today.getMonth(), 1);
                    this.end   = new Date(today);
                } else if (p.type === 'prev-month') {
                    this.start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    this.end   = new Date(today.getFullYear(), today.getMonth(), 0);
                }
                this.apply();
            },
            prevMonth() {
                let d = new Date(this.currentMonth);
                d.setMonth(d.getMonth() - 1);
                this.currentMonth = d;
            },
            nextMonth() {
                let d = new Date(this.currentMonth);
                d.setMonth(d.getMonth() + 1);
                this.currentMonth = d;
            },
            apply() {
                this.display = this.fmt(this.start) + ' → ' + this.fmt(this.end);
                this.$wire.set('dateFrom', this.fmtYMD(this.start));
                this.$wire.set('dateTo',   this.fmtYMD(this.end));
                this.open = false;
            },
            clearRange() {
                this.start = null; this.end = null; this.display = '';
                this.$wire.set('dateFrom', '');
                this.$wire.set('dateTo',   '');
            },
            fmt(date) {
                return date.toLocaleDateString('en-GB');
            },
            fmtYMD(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            },
        };
    }
    </script>
    @endpush

    {{-- Payout Table --}}
    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h10.5M8.25 12h10.5m-10.5 5.25h10.5M3.75 6.75h.008v.008H3.75V6.75zm0 5.25h.008v.008H3.75V12zm0 5.25h.008v.008H3.75v-.008z"/>
            </svg>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">Payout History</h3>
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
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">{{ $payout->account_holder }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $payout->account_number }}</div>
                        </td>
                        <td class="px-4 py-3">
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
            {{ $payouts->links() }}
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
