<div class="relative overflow-hidden rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6">
    @push('scripts')
    <script>
    function reportRangePicker() {
        return {
            open: false,
            start: null,
            end: null,
            display: '',
            currentMonth: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            daysShort: ['Su','Mo','Tu','We','Th','Fr','Sa'],
            dropdownStyle: 'position:fixed;z-index:9999;top:-9999px;left:-9999px;width:644px;',
            presets: [
                { label: 'Today', days: 0 },
                { label: 'Yesterday', days: 1 },
                { label: 'Last 7 Days', days: 7 },
                { label: 'Last 30 Days', days: 30 },
                { label: 'This Month', type: 'month' },
                { label: 'Previous Month', type: 'prev-month' },
            ],
            init() {
                const from = this.$wire.dateFrom;
                const to = this.$wire.dateTo;
                if (from && to) {
                    this.start = new Date(from);
                    this.end = new Date(to);
                    this.display = this.fmt(this.start) + ' -> ' + this.fmt(this.end);
                }
                window.addEventListener('resize', () => {
                    if (this.open) this.calcPosition();
                });
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') this.open = false;
                });
            },
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
                const d = new Date(baseDate.getFullYear(), baseDate.getMonth(), day);
                const isStart = this.start && d.getTime() === this.start.getTime();
                const isEnd   = this.end   && d.getTime() === this.end.getTime();
                const inRange = this.start && this.end && d > this.start && d < this.end;
                const today   = this.isToday(day, baseDate);
                if (isStart || isEnd)  return 'bg-gradient-to-br from-[#2b3990] to-[#4158c0] text-white font-bold rounded-full shadow shadow-indigo-300/40';
                if (inRange)           return 'bg-indigo-100 text-indigo-700 font-medium rounded-md';
                if (today)             return 'text-[#2b3990] font-extrabold ring-2 ring-[#2b3990]/20 rounded-full bg-indigo-50/70';
                return 'hover:bg-slate-100 text-slate-600 rounded-full';
            },
            isToday(day, baseDate) {
                const t = new Date();
                return t.getDate() === day && t.getMonth() === baseDate.getMonth() && t.getFullYear() === baseDate.getFullYear();
            },
            applyPreset(p) {
                let today = new Date();
                today.setHours(0, 0, 0, 0);
                if (p.days !== undefined) {
                    this.end = new Date(today);
                    let s = new Date(today);
                    s.setDate(today.getDate() - p.days);
                    this.start = s;
                } else if (p.type === 'month') {
                    this.start = new Date(today.getFullYear(), today.getMonth(), 1);
                    this.end = new Date(today);
                } else if (p.type === 'prev-month') {
                    this.start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    this.end = new Date(today.getFullYear(), today.getMonth(), 0);
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
                this.display = this.fmt(this.start) + ' -> ' + this.fmt(this.end);
                this.$wire.set('dateFrom', this.fmtYMD(this.start));
                this.$wire.set('dateTo', this.fmtYMD(this.end));
                this.open = false;
            },
            clearRange() {
                this.start = null;
                this.end = null;
                this.display = '';
                this.$wire.set('dateFrom', '');
                this.$wire.set('dateTo', '');
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
            calcPosition(triggerEl) {
                // Synchronous — trigger is always in DOM, no $nextTick needed
                const trigger = triggerEl || this.$el.querySelector('input[readonly]');
                if (!trigger) return;
                const rect = trigger.getBoundingClientRect();
                const vw = window.innerWidth;
                const vh = window.innerHeight;
                const isMob = vw < 640;
                const dropW = isMob ? Math.min(vw - 16, 340) : 644;
                const dropH = isMob ? 380 : 310;
                const gap = 4;
                let top = rect.bottom + gap;
                if (top + dropH > vh - 8) top = Math.max(8, rect.top - dropH - gap);
                let left = rect.left;
                if (left + dropW > vw - 8) left = vw - dropW - 8;
                if (left < 8) left = 8;
                this.dropdownStyle = `position:fixed;z-index:9999;top:${top}px;left:${left}px;width:${dropW}px;`;
            },
            positionDropdown(event) {
                // CRITICAL: set position style FIRST, then open — prevents positional flash
                const trigger = event ? (event.currentTarget || event.target) : null;
                this.calcPosition(trigger);
                this.open = true;
            },
        };
    }
    </script>
    @endpush

    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gray-900 text-white border border-gray-900 shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a2 2 0 100 4 2 2 0 000-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12h-5"/>
                </svg>
                <span>Total Report Volume</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black">&#8377;{{ number_format((float) ($summary->total_volume ?? 0), 2) }}</div>
            <div class="mt-4 flex flex-wrap gap-2">
                <button wire:click="downloadCsv" type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#6c3ec5] text-white text-xs font-bold shadow hover:bg-[#5a33a8] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l4-4m-4 4l-4-4M4 17v1a2 2 0 002 2h12a2 2 0 002-2v-1"/>
                    </svg>
                    Download CSV
                </button>
                <button wire:click="downloadPdf" type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/90 border border-[#d8c0ff] text-[#6d4ca2] text-xs font-bold shadow-sm hover:bg-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h6l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 3v5h5"/>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-[#6c3ec5] border border-[#6c3ec5] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-4-8h8M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/>
                </svg>
                <span>Payout Reports</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">&#8377;{{ number_format((float) ($summary->payout_volume ?? 0), 2) }}</div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-xs text-white">Payout transactions</span>
                <span class="text-xs bg-white text-[#6d4ca2] px-3 py-1 rounded-full font-bold border border-[#e6e872]">{{ number_format((int) ($summary->payout_transactions ?? 0)) }}</span>
            </div>
            <div class="mt-4">
                <a href="{{ route('merchant.payouts') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/90 border border-white/60 text-[#6d4ca2] text-xs font-bold shadow-sm hover:bg-white transition">
                    Open Payout Page
                </a>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#2b3990] via-[#3347ac] to-[#5164c4] border border-[#3c4fae] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-indigo-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12c1.11 0 2.08.402 2.8 1.07M12 8V5m0 3v0m0 6v0m0 3v3m-2.8-4.07A3.987 3.987 0 019 17"/>
                </svg>
                <span>Inward Funds</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">&#8377;{{ number_format((float) ($summary->inward_volume ?? 0), 2) }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/15 border border-white/25 text-indigo-100 text-xs font-semibold">
                {{ number_format((int) ($summary->inward_transactions ?? 0)) }} inward transactions
            </div>
            <div class="mt-3 h-1.5 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full rounded-full bg-white/70" style="width: {{ $successRate }}%"></div>
            </div>
            <div class="mt-3">
                <a href="{{ route('merchant.wallet') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/10 border border-white/30 text-white text-xs font-bold hover:bg-white/20 transition">
                    Open Inward Funds Page
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6 space-y-4">

        {{-- Tabs --}}
        <div class="relative overflow-hidden rounded-2xl border border-[#e5eaf6] bg-gradient-to-r from-white via-[#f8fbff] to-white p-3">
            <div class="mb-2 flex items-center justify-between px-1">
                <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Report Type</p>
                <span class="text-[10px] sm:text-xs text-slate-400">Swipe on mobile</span>
            </div>
            <div class="-mx-1 overflow-x-auto pb-1">
                <div class="inline-flex min-w-max items-center gap-2 px-1 snap-x snap-mandatory">
                    <button wire:click="$set('entryType', 'payout')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none {{ $entryType === 'payout' ? 'bg-violet-100 text-violet-700 border-violet-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-violet-50 hover:border-violet-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $entryType === 'payout' ? 'bg-violet-500' : 'bg-violet-300' }}"></span>
                        Payout
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $entryType === 'payout' ? 'bg-violet-200 text-violet-800' : 'bg-slate-100 text-slate-500' }}">{{ number_format((int) ($summary->payout_transactions ?? 0)) }}</span>
                    </button>
                    <button wire:click="$set('entryType', 'deposit')"
                        class="snap-start shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-semibold border transition duration-200 focus:outline-none {{ $entryType === 'deposit' ? 'bg-sky-100 text-sky-700 border-sky-200 shadow-sm' : 'bg-white text-slate-600 border-[#e2e6f3] hover:bg-sky-50 hover:border-sky-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $entryType === 'deposit' ? 'bg-sky-500' : 'bg-sky-300' }}"></span>
                        Inward Funds
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $entryType === 'deposit' ? 'bg-sky-200 text-sky-800' : 'bg-slate-100 text-slate-500' }}">{{ number_format((int) ($summary->inward_transactions ?? 0)) }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="searchBy" class="cursor-pointer border border-[#dde2ef] rounded-2xl px-8 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
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
                    class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 min-w-[220px]"
                    placeholder="Search reports...">
            </div>

            <select wire:model.live="status" class="cursor-pointer border border-[#dde2ef] rounded-2xl px-4 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="">All Status</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
                @if($entryType !== 'deposit')
                <option value="initiated">Initiated</option>
                <option value="processed">Processed</option>
                <option value="send_to_bank">Send To Bank</option>
                @endif
                @if($entryType !== 'payout')
                <option value="duplicate">Duplicate</option>
                <option value="technical_reject">Technical Reject</option>
                @endif
            </select>

            <div x-data="reportRangePicker()" x-on:report-filters-cleared.window="clearRange()">
                <div class="flex items-center gap-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="text" x-model="display" @click.stop="positionDropdown($event)" readonly
                            class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 cursor-pointer w-48 sm:w-56 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            placeholder="Date Range">
                    </div>
                    <button x-show="display" @click.stop="clearRange()" class="text-slate-400 hover:text-slate-600 text-base leading-none" title="Clear">&#x2715;</button>
                </div>

                {{-- Backdrop: catches outside clicks. position:fixed escapes overflow:hidden on ancestors --}}
                <div x-show="open" x-cloak @click="open = false"
                    style="position:fixed;inset:0;z-index:9998;background:transparent;"
                    aria-hidden="true"></div>

                {{-- Calendar panel: position:fixed so it is never clipped by parent overflow --}}
                <div x-show="open" x-cloak :style="dropdownStyle"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl shadow-slate-300/40 select-none overflow-hidden">

                        <div class="flex flex-col sm:flex-row">

                            {{-- Presets sidebar (desktop) --}}
                            <div class="hidden sm:flex flex-col w-40 shrink-0 border-r border-slate-100 bg-gradient-to-b from-slate-50/80 to-white p-3 gap-0.5">
                                <p class="text-[9px] uppercase tracking-[0.2em] text-slate-400 font-bold px-2 pt-1 pb-2">Quick Select</p>
                                <template x-for="preset in presets" :key="preset.label">
                                    <button @click="applyPreset(preset)"
                                        class="w-full text-left px-3 py-2 rounded-xl text-[11px] font-semibold hover:bg-white hover:text-[#2b3990] hover:shadow-sm text-slate-500 transition-all duration-150 border border-transparent hover:border-indigo-100"
                                        x-text="preset.label"></button>
                                </template>
                            </div>

                            {{-- Calendar column --}}
                            <div class="flex flex-col min-w-0 flex-1">

                                {{-- Preset chips (mobile only) --}}
                                <div class="sm:hidden flex flex-wrap gap-1.5 px-3 pt-3 pb-2.5 border-b border-slate-100 bg-slate-50/60">
                                    <template x-for="preset in presets" :key="preset.label">
                                        <button @click="applyPreset(preset)"
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white text-slate-600 hover:bg-indigo-600 hover:text-white border border-slate-200 hover:border-indigo-600 transition-all duration-150 shadow-sm"
                                            x-text="preset.label"></button>
                                    </template>
                                </div>

                                {{-- Months grid --}}
                                <div class="flex flex-col sm:flex-row">

                                    {{-- Month 1 --}}
                                    <div class="flex-1 p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <button @click="prevMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-400 hover:text-[#2b3990] transition-all text-[10px]">&#9664;</button>
                                            <span class="text-sm font-extrabold text-[#2b3990] tracking-tight" x-text="monthYear(currentMonth)"></span>
                                            <button @click="nextMonth()" class="sm:hidden w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-400 hover:text-[#2b3990] transition-all text-[10px]">&#9654;</button>
                                            <div class="hidden sm:block w-8"></div>
                                        </div>
                                        <div class="grid grid-cols-7 mb-1.5 bg-slate-50 rounded-xl px-0.5 py-1">
                                            <template x-for="d in daysShort" :key="d">
                                                <div class="text-[9px] text-slate-400 text-center font-bold tracking-wide" x-text="d"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-y-1">
                                            <template x-for="(blank, i) in Array.from({length: blanks(currentMonth)})" :key="'lb'+i"><div class="h-8"></div></template>
                                            <template x-for="day in days(currentMonth)" :key="'ld'+day">
                                                <div class="flex items-center justify-center h-8">
                                                    <div @click="select(day, currentMonth)" :class="dayClass(day, currentMonth)"
                                                        class="w-8 h-8 flex items-center justify-center text-[11px] cursor-pointer transition-all duration-100" x-text="day"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Divider --}}
                                    <div class="hidden sm:block w-px bg-gradient-to-b from-transparent via-slate-200 to-transparent my-4"></div>

                                    {{-- Month 2 (desktop only) --}}
                                    <div class="hidden sm:block flex-1 p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-8"></div>
                                            <span class="text-sm font-extrabold text-[#2b3990] tracking-tight" x-text="monthYear(nextMonthObj())"></span>
                                            <button @click="nextMonth()"
                                                class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-400 hover:text-[#2b3990] transition-all text-[10px]">&#9654;</button>
                                        </div>
                                        <div class="grid grid-cols-7 mb-1.5 bg-slate-50 rounded-xl px-0.5 py-1">
                                            <template x-for="d in daysShort" :key="d">
                                                <div class="text-[9px] text-slate-400 text-center font-bold tracking-wide" x-text="d"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-y-1">
                                            <template x-for="(blank, i) in Array.from({length: blanks(nextMonthObj())})" :key="'rb'+i"><div class="h-8"></div></template>
                                            <template x-for="day in days(nextMonthObj())" :key="'rd'+day">
                                                <div class="flex items-center justify-center h-8">
                                                    <div @click="select(day, nextMonthObj())" :class="dayClass(day, nextMonthObj())"
                                                        class="w-8 h-8 flex items-center justify-center text-[11px] cursor-pointer transition-all duration-100" x-text="day"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                </div>

                                {{-- Footer hint --}}
                                <div x-show="start && !end"
                                    class="mx-3 mb-3 px-4 py-2 rounded-xl bg-indigo-50 border border-indigo-100 text-[11px] text-center text-indigo-600 font-semibold">
                                    &#8594; Now select an end date
                                </div>

                            </div>
                        </div>
                    </div>

            </div>

            @if($search || $dateFrom || $dateTo || $status || $searchBy !== 'reference' || $entryType)
            <button wire:click="clearFilters"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-rose-200 bg-rose-50 text-rose-600 text-sm font-semibold hover:bg-rose-100 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear Filters
            </button>
            @endif
        </div>

        <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3 text-sm text-indigo-700">
            <svg class="w-4 h-4 mt-0.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
            </svg>
            <p class="leading-relaxed">
                @if($entryType === 'deposit')
                    Showing <span class="font-semibold text-sky-700">Inward Funds</span> — current month{{ $dateFrom ? '' : ' (default)' }}. Switch tab to view Payouts.
                @else
                    Showing <span class="font-semibold text-violet-700">Payout</span> transactions — current month{{ $dateFrom ? '' : ' (default)' }}. Switch tab to view Inward Funds.
                @endif
            </p>
        </div>
    </div>

    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-2 mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h10.5M8.25 12h10.5m-10.5 5.25h10.5M3.75 6.75h.008v.008H3.75V6.75zm0 5.25h.008v.008H3.75V12zm0 5.25h.008v.008H3.75v-.008z"/>
                </svg>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">
                    @if($entryType === 'payout') Payout Report History
                    @elseif($entryType === 'deposit') Inward Funds Report History
                    @else Complete Report History
                    @endif
                </h3>
            </div>
            <select wire:model.live="perPage" class="cursor-pointer border border-[#dde2ef] rounded-2xl px-5 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="15">15 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>

        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-white text-left">
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Reference No</th>
                    @if(!$entryType)
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Type</th>
                    @endif
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Bank</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Account</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Amount</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Charges</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Total</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Date</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reports as $row)
                <tr class="hover:bg-white/90 transition">
                    <td class="px-4 py-3 font-mono text-xs text-indigo-600 whitespace-nowrap font-semibold">{{ $row->reference_no }}</td>
                    @if(!$entryType)
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($row->entry_type === 'payout')
                            <span class="bg-violet-100 text-violet-700 px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">Payout</span>
                        @else
                            <span class="bg-sky-100 text-sky-700 px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">Inward</span>
                        @endif
                    </td>
                    @endif
                    <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $row->party_name ?: 'N/A' }}</td>
                    <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $row->bank_name ?: 'N/A' }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-700 whitespace-nowrap">{{ $row->account_no ?: 'N/A' }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">&#8377;{{ number_format((float) $row->amount, 2) }}</td>
                    <td class="px-4 py-3 text-slate-700 whitespace-nowrap">&#8377;{{ number_format((float) $row->charges, 2) }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">&#8377;{{ number_format((float) $row->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">{{ $row->txn_at ? \Carbon\Carbon::parse($row->txn_at)->format('d M Y, h:i A') : 'N/A' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @php
                            $statusClass = match($row->source_status) {
                                'success'          => 'bg-emerald-100 text-emerald-700',
                                'failed'           => 'bg-rose-100 text-rose-700',
                                'pending'          => 'bg-amber-100 text-amber-700',
                                'initiated'        => 'bg-sky-100 text-sky-700',
                                'processed'        => 'bg-indigo-100 text-indigo-700',
                                'send_to_bank'     => 'bg-violet-100 text-violet-700',
                                'duplicate'        => 'bg-yellow-100 text-yellow-700',
                                'technical_reject' => 'bg-red-100 text-red-700',
                                default            => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' ', $row->source_status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-10 text-center text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                        </svg>
                        No records found for selected filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $reports->links() }}
        </div>
    </div>
</div>
