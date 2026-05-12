@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('transactionChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Processed', 'Pending', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $businessAnalytics['total_payouts'] }},
                            {{ $businessAnalytics['pending_payouts'] }},
                            {{ $businessAnalytics['rejected_payouts'] }}
                        ],
                        backgroundColor: [
                            '#34d399', // green
                            '#fbbf24', // yellow
                            '#ef4444'  // red
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
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

<div class="space-y-6">
    <!-- Main Bank Account Balance Section -->
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-blue-50/40 shadow-sm">
            <div class="pointer-events-none absolute -top-20 -right-16 h-48 w-48 rounded-full bg-blue-100/50 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-20 h-40 w-40 rounded-full bg-cyan-100/50 blur-3xl"></div>
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500"></div>

            <div class="relative p-6 md:p-7">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-center">
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-xl bg-white/90 border border-blue-100 shadow-sm flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="13" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="14" r="2" fill="currentColor"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-slate-500 text-[11px] font-semibold uppercase tracking-[0.14em]">Main Settlement Account</div>
                            <h2 class="mt-1 text-2xl md:text-3xl font-bold text-slate-900">Account Balance</h2>
                            <p class="mt-1 text-sm text-slate-600">Secure real-time balance of your primary bank account.</p>                            
                        </div>
                    </div>

                    <div class="flex justify-start lg:justify-end">
                        <livewire:components.admin.admin-balance-component />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Analytics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Merchants -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Total Merchants</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ $businessAnalytics['total_merchants'] }}</div>
        </div>
        <!-- Active Merchants -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Active Merchants</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ $businessAnalytics['active_merchants'] }}</div>
        </div>
        <!-- Rejected Merchants -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Rejected Merchants</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ $businessAnalytics['rejected_merchants'] }}</div>
        </div>
        <!-- Waiting for Approval -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Waiting Approval</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ $businessAnalytics['waiting_merchants'] }}</div>
        </div>
        <!-- Today's Registered -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Today's Registered</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ $businessAnalytics['today_registered'] }}</div>
        </div>
        <!-- Total Transactions -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-2 relative z-10">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M8 8h8M8 12h8M8 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-xs text-gray-500 font-semibold uppercase">Total Transactions</span>
            </div>
            <div class="text-2xl font-bold text-[#1a237e] relative z-10">{{ number_format($businessAnalytics['total_transactions']) }}</div>
        </div>
    </div>
    <!-- End Business Analytics Section -->

    <!-- Transaction Status Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
        <!-- Transaction Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col min-h-[320px] col-span-1 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div class="text-base font-semibold text-gray-900">Payout Status Overview</div>
                <div>
                    <input type="text"
                        class="border border-gray-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-200"
                        placeholder="Select date">
                </div>
            </div>
            <div class="flex flex-col lg:flex-row gap-8 items-center justify-center flex-1">
                <div class="flex flex-col items-center relative">
                    <canvas id="transactionChart" width="180" height="180"></canvas>
                </div>
                <div class="flex flex-col gap-2 mt-6">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-400"></span>
                        <span class="text-xs text-gray-700">Processed</span>
                        <span class="ml-2 text-xs font-semibold text-gray-900">
                            ₹{{ number_format($businessAnalytics['total_payouts'], 2) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="text-xs text-gray-700">Pending</span>
                        <span class="ml-2 text-xs font-semibold text-gray-900">
                            ₹{{ number_format($businessAnalytics['pending_payouts'], 2) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="text-xs text-gray-700">Rejected</span>
                        <span class="ml-2 text-xs font-semibold text-gray-900">
                            ₹{{ number_format($businessAnalytics['rejected_payouts'], 2) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end justify-end mt-3">
                <div class="text-gray-500 text-xs">Total Transactions</div>
                <div class="text-2xl font-bold text-appPrimary">
                    {{ $businessAnalytics['total_transactions'] }}
                </div>
            </div>
        </div>
        <!-- Quick Stats Card -->
        <div class="bg-gradient-to-tr from-white via-gray-50 to-blue-50 rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="text-base font-semibold text-gray-900 mb-4">Performance Overview</div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Success Rate</div>
                    <div class="text-2xl font-bold text-green-600">{{ $businessAnalytics['success_rate'] }}%</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Avg. Transaction</div>
                    <div class="text-2xl font-bold text-blue-600">₹{{ number_format($businessAnalytics['avg_transaction'], 2) }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Volume</div>
                    <div class="text-2xl font-bold text-purple-600">₹{{ number_format($businessAnalytics['total_volume'], 2) }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Active Merchants</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $businessAnalytics['active_merchants'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Merchants Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="text-base font-semibold text-gray-900">Today's Registered Merchants</div>
            <div class="text-sm text-gray-500">Total: {{ count($todayMerchants) }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Mobile</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Registered At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($todayMerchants as $merchant)
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <div class="font-medium">{{ $merchant['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $merchant['business_name'] }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $merchant['email'] }}</td>
                            <td class="px-4 py-3">{{ $merchant['mobile'] }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-{{ $merchant['status'] === 'Active' ? 'green' : ($merchant['status'] === 'Waiting' ? 'yellow' : 'red') }}-100 
                                            text-{{ $merchant['status'] === 'Active' ? 'green' : ($merchant['status'] === 'Waiting' ? 'yellow' : 'red') }}-600 
                                            px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $merchant['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $merchant['registered_at'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">No merchants registered today</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Payouts Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="text-base font-semibold text-gray-900">Recent Payouts</div>
            <div class="text-sm text-gray-500">Today's Count: {{ count($todayPayouts) }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Beneficiary</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">UTR</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Mode</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($todayPayouts as $payout)
                        <tr>
                            <td class="px-4 py-3">{{ $payout['date'] }}</td>
                            <td class="px-4 py-3">
                                <div>
                                    <div class="font-medium">{{ $payout['beneficiary'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $payout['merchant'] }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $payout['utr'] }}</td>
                            <td class="px-4 py-3">{{ $payout['amount'] }}</td>
                            <td class="px-4 py-3">{{ $payout['mode'] }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-{{ $payout['status'] === 'Success' ? 'green' : ($payout['status'] === 'Pending' ? 'yellow' : 'red') }}-100 
                                            text-{{ $payout['status'] === 'Success' ? 'green' : ($payout['status'] === 'Pending' ? 'yellow' : 'red') }}-600 
                                            px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $payout['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">No payouts found today</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>