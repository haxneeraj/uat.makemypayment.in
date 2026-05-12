<div class="space-y-6">
    <!-- Merchant Profile Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-3xl font-bold text-white">
                    {{ strtoupper(substr($merchant->full_name, 0, 2)) }}
                </div>
            </div>
            <div class="flex-grow">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $merchant->full_name }}</h1>
                <div class="text-gray-500 mb-3">{{ $merchant->merchant_id }}</div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $merchant->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $merchant->phone }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $merchant->status === 'active' ? 'bg-green-100 text-green-800' : 
                       ($merchant->status === 'suspended' ? 'bg-red-100 text-red-800' : 
                       'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($merchant->status) }}
                </span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $merchant->kyc_status === 'verified' ? 'bg-green-100 text-green-800' : 
                       ($merchant->kyc_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                       'bg-yellow-100 text-yellow-800') }}">
                    KYC: {{ ucfirst($merchant->kyc_status) }}
                </span>
                <!-- Edit Button -->
                <a href="{{ route('admin.merchants.edit', $merchant->merchant_id) }}" 
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Transaction Volume -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Transaction Volume</h3>
                <span class="text-xs text-gray-500">All Time</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-blue-600">₹{{ number_format($stats['total_volume'], 2) }}</div>
                <div class="text-sm text-gray-600">Today: ₹{{ number_format($stats['today_volume'], 2) }}</div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Success Rate</h3>
                <span class="text-xs text-gray-500">Transactions</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-green-600">{{ $stats['success_rate'] }}%</div>
                <div class="text-sm text-gray-600">Total Payouts: {{ number_format($stats['payout_count']) }}</div>
            </div>
        </div>

        <!-- Total Deposits -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Total Deposits</h3>
                <span class="text-xs text-gray-500">All Time</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-purple-600">₹{{ number_format($stats['total_deposits'], 2) }}</div>
                <div class="text-sm text-gray-600">Count: {{ number_format($stats['deposit_count']) }}</div>
            </div>
        </div>
    </div>

    <!-- Business Details -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-sm text-gray-500 mb-1">Business Name</div>
                <div class="font-medium">{{ $merchant->merchantKyc->business_name ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">GST Number</div>
                <div class="font-medium">{{ $merchant->merchantKyc->gstin ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Business Type</div>
                <div class="font-medium">{{ ucfirst($merchant->merchantKyc->business_type ?? 'N/A') }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Address</div>
                <div class="font-medium">{{ $merchant->merchantKyc->business_address ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">PAN</div>
                <div class="font-medium">{{ $merchant->merchantKyc->pan ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Website</div>
                <div class="font-medium">
                    <a href="{{ $merchant->merchantKyc->website_url ?? '#' }}" target="_blank" class="text-blue-600 hover:underline">
                        {{ $merchant->merchantKyc->website_url ?? 'N/A' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payouts -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Payouts</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentPayouts as $payout)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ $payout->created_at->format('d M Y') }}</td>
                            <td class="px-3 py-2 text-sm">₹{{ number_format($payout->amount, 2) }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $payout->status === 'success' ? 'bg-green-100 text-green-800' : 
                                       ($payout->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-sm text-center text-gray-500">No recent payouts</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Deposits</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">UTR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentDeposits as $deposit)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ $deposit->created_at->format('d M Y') }}</td>
                            <td class="px-3 py-2 text-sm">₹{{ number_format($deposit->amount, 2) }}</td>
                            <td class="px-3 py-2 text-sm font-mono">{{ $deposit->utr }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-sm text-center text-gray-500">No recent deposits</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
