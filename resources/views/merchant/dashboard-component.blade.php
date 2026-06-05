@push('scripts')
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('transactionChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Initiated', 'Success', 'Pending', 'Failed', 'Processed', 'Send To Bank'],
                    datasets: [{
                        data: [
                            {{ $transactionStatus['initiated'] }},
                            {{ $transactionStatus['success'] }},
                            {{ $transactionStatus['pending'] }},
                            {{ $transactionStatus['failed'] }},
                            {{ $transactionStatus['processed'] }},
                            {{ $transactionStatus['send_to_bank'] }}
                        ],
                        backgroundColor: [
                            '#93c5fd', // initiated
                            '#86efac', // success
                            '#fde68a', // light yellow
                            '#fda4af', // failed
                            '#a5b4fc', // processed
                            '#c4b5fd'  // send_to_bank
                        ],
                        borderWidth: 2,
                        borderColor: '#f8fafc'
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
@endpush

<div class="relative overflow-hidden rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6">

    @php
        $todayUsedAmount = (float) ($todayStats['amount'] ?? 0);
        $dailyLimit = (float) ($user->daily_transfer_limit ?? 0);
        $availableTodayLimit = max($dailyLimit - $todayUsedAmount, 0);
    @endphp

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#2b3990] via-[#3347ac] to-[#5164c4] border border-[#3c4fae] shadow-sm p-5 sm:p-7">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-400 text-white flex items-center justify-center shadow-md shadow-sky-200/60">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75M5.25 10.5V18a1.5 1.5 0 001.5 1.5h10.5a1.5 1.5 0 001.5-1.5v-7.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-100 font-semibold">Merchant Dashboard</p>
                    <h2 class="text-xl sm:text-2xl font-black text-white">Welcome Back, {{ strtoupper($user->full_name ?? '') }}</h2>
                    <p class="text-sm text-indigo-100/90">Manage transfers, watch liquidity, and keep payouts under control.</p>
                </div>
            </div>

            <div class="hidden sm:flex self-end lg:self-auto">
                <a href="{{ route('merchant.wallet') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/15 hover:bg-white/25 border border-white/30 text-white text-sm font-semibold shadow-md shadow-indigo-900/30 backdrop-blur-sm transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-3"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a2 2 0 100 4 2 2 0 000-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12h-5"/>
                    </svg>
                    View VAN
                </a>
            </div>
        </div>
    </div>

    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="rounded-3xl p-5 sm:p-6 bg-[#fffa81] text-slate-900 border border-[#fffa81] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-[#6d4ca2]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h18m-16.5 0l1.5 11.25A1.5 1.5 0 007.5 20h9a1.5 1.5 0 001.5-1.25L19.5 7.5M9 11.25h6"/>
                </svg>
                <span>Current Balance</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black"><livewire:components.merchant-balance-component /></div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/90 text-[#6d4ca2] text-xs font-semibold border border-[#d8c0ff]">Primary merchant VAN</div>
        </div>

        <div class="rounded-3xl p-4 sm:p-5 bg-gray-900 border border-gray-900 shadow-sm">
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-4-8h8M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/>
                </svg>
                <span>Today's Payout</span>
            </div>
            <div class="mt-2.5 text-lg sm:text-xl font-black text-white">₹{{ number_format($todayStats['amount'], 2) }}</div>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-[10px] text-white">Transaction count</span>
                <span class="text-[10px] bg-white text-[#6d4ca2] px-2.5 py-1 rounded-full font-bold border border-[#e6e872]">{{ $todayStats['count'] }}</span>
            </div>
        </div>

        <div class="rounded-3xl p-4 sm:p-5 bg-[#6c3ec5] border border-[#6c3ec5] shadow-sm">
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Total Payout</span>
            </div>
            <div class="mt-2.5 text-lg sm:text-xl font-black text-white">₹{{ number_format($totalPayout, 2) }}</div>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-[10px] text-white">Transaction count</span>
                <span class="text-[10px] bg-white text-[#6d4ca2] px-2.5 py-1 rounded-full font-bold border border-[#e6e872]">{{ $totalTransactionCount }}</span>
            </div>
        </div>

        <div class="rounded-3xl p-4 sm:p-5 bg-[#9069bd] border border-[#9069bd] shadow-sm">
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Transaction Limit <small>(Per Day)</small></span>
            </div>
            <div class="mt-2.5 text-lg sm:text-xl font-black text-white">₹{{ number_format($user->daily_transfer_limit, 2) }}</div>
            <div class="mt-3 text-[10px] text-gray-600 bg-white/90 w-fit px-2.5 py-1 rounded-full font-semibold border border-[#ec5cff]">
                Available Today: ₹{{ number_format($availableTodayLimit, 2) }}
            </div>
        </div>

        <div class="rounded-3xl p-4 sm:p-5 bg-[#c9ffac] border border-[#c9ffac] shadow-sm">
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-slate-500">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Min Transfer Limit</span>
            </div>
            <div class="mt-2.5 text-lg sm:text-xl font-black text-slate-900">₹{{ number_format($user->min_transfer_limit ?? 0, 2) }}</div>
            <div class="mt-3 text-[10px] text-slate-500">Minimum amount per payout transaction</div>
        </div>

        <div class="rounded-3xl p-4 sm:p-5 bg-[#ffefb6] border border-[#ffefb6] shadow-sm">
            <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-slate-500">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Max Transfer Limit</span>
            </div>
            <div class="mt-2.5 text-lg sm:text-xl font-black text-slate-900">₹{{ number_format($user->max_transfer_limit ?? 0, 2) }}</div>
            <div class="mt-3 text-[10px] text-slate-500">Maximum amount per payout transaction</div>
        </div> 
        
        
    </div>

    <div class="relative grid grid-cols-1 xl:grid-cols-12 gap-5">
        <div class="xl:col-span-7 rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h10.5M8.25 12h10.5m-10.5 5.25h10.5M3.75 6.75h.008v.008H3.75V6.75zm0 5.25h.008v.008H3.75V12zm0 5.25h.008v.008H3.75v-.008z"/>
                    </svg>
                    <span>Recent Payouts</span>
                </h3>
                <a href="{{ route('merchant.payouts') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
            @if(count($recentPayouts))
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-white text-left">
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">#</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Initiated At</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Transaction ID</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Beneficiary</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Bank Details</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Mobile</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">UTR Number</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Fee</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Total Amount</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Opening Balance</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Closing Balance</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Mode</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentPayouts as $payout)
                                <tr class="hover:bg-white/90 transition">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-400 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">
                                        {{ \Carbon\Carbon::parse($payout->initiated_at)->format('d M Y, h:i A') }}
                                        @if($payout->processed_at)
                                            <div class="text-slate-400 mt-0.5">Done: {{ \Carbon\Carbon::parse($payout->processed_at)->format('d M Y, h:i A') }}</div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-3 cursor-pointer text-indigo-600 whitespace-nowrap font-mono text-xs font-semibold hover:underline"
                                        wire:click="$dispatch('openTransferDetailModal', { transferId: '{{ $payout->transaction_id }}' })">
                                        {{ $payout->transaction_id }}
                                    </td>

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
                                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">₹{{ number_format($payout->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-slate-700 whitespace-nowrap">₹{{ number_format($payout->fee ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">₹{{ number_format($payout->total_amount ?? ($payout->amount + ($payout->fee ?? 0)), 2) }}</td>
                                    <td class="px-4 py-3 text-slate-700 whitespace-nowrap">₹{{ number_format($payout->opening_balance ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">₹{{ number_format($payout->closing_balance ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-semibold uppercase">{{ $payout->mode }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $statusClass = match($payout->status) {
                                                'success' => 'bg-emerald-100 text-emerald-700',
                                                'failed' => 'bg-rose-100 text-rose-700',
                                                'pending' => 'bg-amber-100 text-amber-700',
                                                'initiated' => 'bg-sky-100 text-sky-700',
                                                'processed' => 'bg-indigo-100 text-indigo-700',
                                                'send_to_bank' => 'bg-violet-100 text-violet-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="min-h-[240px] flex flex-col items-center justify-center rounded-2xl border border-dashed border-[#dfe4ef] bg-white text-sm text-slate-500">
                    <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                    </svg>
                    No recent payouts found
                </div>
            @endif
        </div>

        <div class="xl:col-span-5 rounded-3xl bg-[#eef1f6] border border-[#e4e8ef] shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h9M7.5 12h4.5m-6.75 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    <span>Transaction Status</span>
                </h3>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative">
                    <canvas id="transactionChart" width="180" height="180"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">Total Value</p>
                            <p class="text-sm font-bold text-slate-900">₹{{ number_format($transactionStatus['total'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-[#eff6ff] border border-[#dbeafe] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2.25"/></svg>
                            <span class="text-slate-700">Initiated</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['initiated'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#eef9f1] border border-[#d4eedc] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-slate-700">Success</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['success'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#fff8df] border border-[#f6e7b8] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2.25"/></svg>
                            <span class="text-slate-700">Pending</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['pending'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#ffeef0] border border-[#f7d5da] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="text-slate-700">Failed</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['failed'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#eef2ff] border border-[#dbe3ff] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-slate-700">Processed</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['processed'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#f3e8ff] border border-[#e9d5ff] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                            <span class="text-slate-700">Send To Bank</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($transactionStatus['send_to_bank'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative rounded-3xl bg-[#f9fbff] border border-[#e8edf5] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0 0l-4.5-4.5M12 21l4.5-4.5"/>
                </svg>
                <span>Inward Funds</span>
            </h3>
            <a href="{{ route('merchant.wallet') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Transaction ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Virtual Account</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Amount</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Transaction Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Description</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inwardFunds as $fund)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-appPrimary whitespace-nowrap cursor-pointer hover:underline"
                                wire:click="$dispatch('openDepositDetailModal', { alertSequenceNo: '{{ $fund->alert_sequence_no }}' })">
                                {{ $fund->alert_sequence_no }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-700 whitespace-nowrap">
                                {{ $fund->virtual_account ?? $fund->account_number }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">
                                ₹{{ number_format($fund->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">
                                {{ \Carbon\Carbon::parse($fund->transaction_date)->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                {{ $fund->transaction_description ?? '—' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $sc = match($fund->processing_status) {
                                        'success'          => ['bg-green-100',  'text-green-700'],
                                        'duplicate'        => ['bg-yellow-100', 'text-yellow-700'],
                                        'technical_reject' => ['bg-red-100',    'text-red-700'],
                                        default            => ['bg-gray-100',   'text-gray-700'],
                                    };
                                @endphp
                                <span class="{{ $sc[0] }} {{ $sc[1] }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ ucfirst(str_replace('_', ' ', $fund->processing_status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                                </svg>
                                No deposits found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <livewire:components.transfer-detail-component />
</div>