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
                            {{ $businessAnalytics['initiated_payouts'] }},
                            {{ $businessAnalytics['success_payouts'] }},
                            {{ $businessAnalytics['pending_payouts'] }},
                            {{ $businessAnalytics['failed_payouts'] }},
                            {{ $businessAnalytics['processed_payouts'] }},
                            {{ $businessAnalytics['send_to_bank_payouts'] }}
                        ],
                        backgroundColor: [
                            '#93c5fd', // initiated
                            '#86efac', // success
                            '#fde68a', // pending
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

<div class="relative overflow-hidden min-w-0 max-w-full rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6">
    <!-- Admin Header Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#2b3990] via-[#3347ac] to-[#5164c4] border border-[#3c4fae] shadow-sm p-5 sm:p-7">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-400 text-white flex items-center justify-center shadow-md shadow-sky-200/60">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-100 font-semibold">Administrator Panel</p>
                    <h2 class="text-xl sm:text-2xl font-black text-white">Platform Dashboard</h2>
                    <p class="text-sm text-indigo-100/90">Monitor system health, merchants, and transactions in real-time.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Settlement Account Balance Section -->
    <div class="rounded-3xl p-5 sm:p-6 bg-[#FFEA63] text-gray-900 border border-[#FFEA63] shadow-sm">
        <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-[#6d4ca2]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="13" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="14" r="2" fill="currentColor"/>
            </svg>
            <span>Main Settlement Account</span>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <div>
                <div class="text-xs text-[#6d4ca2] mb-1">Available Balance</div>
                <div class="text-2xl sm:text-3xl font-black"><livewire:components.admin.admin-balance-component wire:init="fetchBalance" /></div>
            </div>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/90 text-[#6d4ca2] text-xs font-semibold border border-[#d8c0ff]">Primary account</div>
        </div>
    </div>

    <!-- Total VAN Balance Section -->
    <div class="rounded-3xl p-5 sm:p-6 bg-[#e0f2fe] text-gray-900 border border-[#bae6fd] shadow-sm">
        <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-[#0284c7]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="13" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="14" r="2" fill="currentColor"/>
            </svg>
            <span>Total VAN Balance</span>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <div>
                <div class="text-xs text-[#0284c7] mb-1">Combined balance across all VANs</div>
                <div class="text-2xl sm:text-3xl font-black"><livewire:components.admin.van-balance-component wire:init="fetchVanBalance" /></div>
            </div>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/90 text-[#0284c7] text-xs font-semibold border border-[#bae6fd]">Virtual accounts</div>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Total Merchants Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#0ea5e9] to-[#0369a1] border border-[#0284c7] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Total Merchants</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $businessAnalytics['total_merchants'] }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">Active on platform</div>
        </div>

        <!-- Active Merchants Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#10b981] to-[#047857] border border-[#059669] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Active Merchants</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $businessAnalytics['active_merchants'] }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">Currently active</div>
        </div>

        <!-- Rejected Merchants Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#ef4444] to-[#dc2626] border border-[#b91c1c] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Rejected Merchants</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $businessAnalytics['rejected_merchants'] }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">Rejected</div>
        </div>

        <!-- Waiting for Approval Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#f59e0b] to-[#d97706] border border-[#b45309] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Waiting Approval</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $businessAnalytics['waiting_merchants'] }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">Pending review</div>
        </div>

        <!-- Today's Registered Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#a855f7] to-[#7c3aed] border border-[#6d28d9] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Today's Registered</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $businessAnalytics['today_registered'] }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">New registrations</div>
        </div>

        <!-- Total Transactions Card -->
        <div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-[#6366f1] to-[#4f46e5] border border-[#4338ca] shadow-sm">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M8 8h8M8 12h8M8 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Total Transactions</span>
            </div>
            <div class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ number_format($businessAnalytics['total_transactions']) }}</div>
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold border border-white/30">All time total</div>
        </div>
    </div>

    <!-- Payout Status Section -->
    <div class="relative grid grid-cols-1 xl:grid-cols-12 gap-5">
        <!-- Payout Status Chart -->
        <div class="xl:col-span-7 rounded-3xl bg-[#eef1f6] border border-[#e4e8ef] shadow-sm p-5 sm:p-6">
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
                            <p class="text-sm font-bold text-slate-900">₹{{ number_format($businessAnalytics['transaction_status_total'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-[#eff6ff] border border-[#dbeafe] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2.25"/></svg>
                            <span class="text-slate-700">Initiated</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['initiated_payouts'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#eef9f1] border border-[#d4eedc] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-slate-700">Success</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['success_payouts'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#fff8df] border border-[#f6e7b8] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2.25"/></svg>
                            <span class="text-slate-700">Pending</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['pending_payouts'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#ffeef0] border border-[#f7d5da] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="text-slate-700">Failed</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['failed_payouts'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#eef2ff] border border-[#dbe3ff] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-slate-700">Processed</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['processed_payouts'], 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#f3e8ff] border border-[#e9d5ff] px-3 py-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                            <span class="text-slate-700">Send To Bank</span>
                        </div>
                        <span class="font-semibold text-slate-900">₹{{ number_format($businessAnalytics['send_to_bank_payouts'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics Card -->
        <div class="xl:col-span-5 rounded-3xl bg-gradient-to-br from-blue-50 via-white to-indigo-50/40 border border-[#e0e7ff] shadow-sm p-5 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2 mb-5">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Performance Metrics</span>
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-white border border-[#dbeafe] p-4">
                    <div class="text-xs text-slate-600 font-semibold uppercase">Success Rate</div>
                    <div class="text-2xl font-bold text-green-600 mt-2">{{ $businessAnalytics['success_rate'] }}%</div>
                    <div class="text-xs text-slate-400 mt-2">Of all transactions</div>
                </div>
                <div class="rounded-2xl bg-white border border-[#e0e7ff] p-4">
                    <div class="text-xs text-slate-600 font-semibold uppercase">Avg Transaction</div>
                    <div class="text-2xl font-bold text-blue-600 mt-2">₹{{ number_format($businessAnalytics['avg_transaction'], 0) }}</div>
                    <div class="text-xs text-slate-400 mt-2">Average amount</div>
                </div>
                <div class="rounded-2xl bg-white border border-[#f3e8ff] p-4">
                    <div class="text-xs text-slate-600 font-semibold uppercase">Total Volume</div>
                    <div class="text-2xl font-bold text-purple-600 mt-2">₹{{ number_format($businessAnalytics['total_volume'] / 100000, 1) }}L</div>
                    <div class="text-xs text-slate-400 mt-2">Total processed</div>
                </div>
                <div class="rounded-2xl bg-white border border-[#fef08a] p-4">
                    <div class="text-xs text-slate-600 font-semibold uppercase">Active Partners</div>
                    <div class="text-2xl font-bold text-amber-600 mt-2">{{ $businessAnalytics['active_merchants'] }}</div>
                    <div class="text-xs text-slate-400 mt-2">Currently active</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Merchants Table -->
    <div class="overflow-hidden rounded-3xl bg-[#f9fbff] border border-[#e8edf5] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 19H9a6 6 0 016-6v0a6 6 0 016 6v0"/>
                </svg>
                <span>Today's Registered Merchants</span>
            </h3>
            <div class="flex items-center gap-3">
                <div class="text-sm text-slate-500">Total: {{ count($todayMerchants) }}</div>
                <a href="{{ route('admin.merchants') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-white text-left">
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Merchant Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Merchant ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Mobile</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Registered At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($todayMerchants as $merchant)
                        <tr class="hover:bg-white/90 transition cursor-pointer">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ $merchant['name'] }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $merchant['business_name'] }}</div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-indigo-600 whitespace-nowrap font-semibold">{{ $merchant['id'] ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-700 whitespace-nowrap text-xs">{{ $merchant['email'] }}</td>
                            <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $merchant['mobile'] }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusStyle = match($merchant['status']) {
                                        'Active' => ['bg-emerald-100', 'text-emerald-700'],
                                        'Waiting' => ['bg-amber-100', 'text-amber-700'],
                                        default => ['bg-rose-100', 'text-rose-700'],
                                    };
                                @endphp
                                <span class="{{ $statusStyle[0] }} {{ $statusStyle[1] }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ $merchant['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">{{ $merchant['registered_at'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                No merchants registered today
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Payouts Table -->
    <div class="overflow-hidden rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-3.5-3.5M12 15l3.5-3.5"/>
                </svg>
                <span>Recent Payouts</span>
            </h3>
            <div class="flex items-center gap-3">
                <div class="text-sm text-slate-500">Today's Count: {{ count($todayPayouts) }}</div>
                <a href="{{ route('admin.reports') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
        </div>
        @if(count($todayPayouts))
            <div class="overflow-x-auto">
                <table class="min-w-full max-w-[1100px] text-sm">
                    <thead>
                        <tr class="bg-white text-left">
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Initiated At</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Transfer ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Merchant</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Beneficiary</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Bank Details</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">UTR</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Fee</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Total Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Mode</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($todayPayouts as $payout)
                        <tr class="hover:bg-white/90 transition cursor-pointer">
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs font-bold">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">
                                {{ !blank($payout->initiated_at) ? \Carbon\Carbon::parse($payout->initiated_at)->format('d M, Y h:i A') : 'N/A' }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <span wire:click="$dispatch('openTransferDetailModal', '{{ $payout->transaction_id }}')" class="font-mono text-xs font-semibold text-indigo-600 cursor-pointer">
                                    {{ $payout->transaction_id }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">
                                    {{ $payout->user?->full_name ?? 'N/A' }}
                                </div>

                                <div class="text-xs text-slate-500 mt-0.5">
                                    @if($payout->user?->phone)
                                        {{ $payout->user->phone }}
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">
                                    {{ $payout->account_holder }}
                                </div>

                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $payout->account_number }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="text-slate-700">
                                    {{ $payout->bank_name }}
                                </div>

                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $payout->ifsc_code }}
                                </div>
                            </td>

                            <td class="px-4 py-3 font-mono text-xs text-slate-700 whitespace-nowrap">
                                {{ $payout->utr ?: 'N/A' }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">
                                ₹{{ number_format($payout->amount, 2) }}
                            </td>

                            <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                ₹{{ number_format($payout->fee ?? 0, 2) }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">
                                ₹{{ number_format($payout->total_amount ?: ($payout->amount + ($payout->fee ?? 0)), 2) }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded text-xs font-semibold uppercase">
                                    {{ $payout->mode }}
                                </span>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">

                                @php
                                    $statusClass = match($payout->status) {
                                        'success' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'processed' => 'bg-sky-100 text-sky-700',
                                        'send_to_bank' => 'bg-indigo-100 text-indigo-700',
                                        default => 'bg-rose-100 text-rose-700',
                                    };
                                @endphp

                                <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ ucfirst($payout->status) }} {{ $payout->refund?->status ? ' (Refunded)' : '' }}
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
                No payouts found today
            </div>
        @endif
    </div>

    <!-- Inward Funds Table -->
    <div class="relative rounded-3xl bg-[#f9fbff] border border-[#e8edf5] shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0 0l-4.5-4.5M12 21l4.5-4.5"/>
                </svg>
                <span>Inward Funds</span>
            </h3>
            <div class="flex items-center gap-3">
                <div class="text-sm text-slate-500">Recent: {{ count($recentInwardFunds) }}</div>
                <a href="{{ route('admin.reports') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full  max-w-[1100px] text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Date & Time</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Merchant</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Alert Sequence No</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Virtual Account</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Amount</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Description</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Mnemonic Code</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentInwardFunds as $deposit)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                            <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">{{ $deposit->transaction_date ? \Carbon\Carbon::parse($deposit->transaction_date)->format('d M, Y h:i A') : 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ $deposit->user?->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $deposit->user?->phone ?? 'N/A' }}</div>
                            </td>
                            <td wire:click="$dispatch('openInwardDetailModal', '{{ $deposit->alert_sequence_no }}')" class="px-4 py-3 font-mono text-xs text-indigo-600 whitespace-nowrap font-semibold">{{ $deposit->alert_sequence_no ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-700 whitespace-nowrap">{{ $deposit->virtual_account ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">{{ $deposit->amount ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $deposit->description ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $deposit->mnemonic_code ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $fundStatusClass = match($deposit->processing_status) {
                                        'success' => 'bg-emerald-100 text-emerald-700',
                                        'duplicate' => 'bg-amber-100 text-amber-700',
                                        'technical_reject' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="{{ $fundStatusClass }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ ucfirst(str_replace('_', ' ', $deposit->processing_status ?? 'unknown')) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                                </svg>
                                No inward funds found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <livewire:components.admin.inward-detail-component />
    <livewire:components.admin.transfer-detail-component />
</div>