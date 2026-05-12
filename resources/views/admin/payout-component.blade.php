<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Today's Stats -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Today's Payouts</h3>
                <span class="text-xs text-gray-500">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-blue-600">₹{{ number_format($stats['today_volume'], 2) }}</div>
                <div class="text-sm text-gray-600">Count: {{ number_format($stats['today_count']) }}</div>
            </div>
        </div>

        <!-- Success Stats -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Successful</h3>
                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Success</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-green-600">₹{{ number_format($stats['success_volume'], 2) }}</div>
                <div class="text-sm text-gray-600">Count: {{ number_format($stats['success_count']) }}</div>
            </div>
        </div>

        <!-- Pending Stats -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Pending</h3>
                <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">Pending</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-yellow-600">₹{{ number_format($stats['pending_volume'], 2) }}</div>
                <div class="text-sm text-gray-600">Count: {{ number_format($stats['pending_count']) }}</div>
            </div>
        </div>

        <!-- Failed Stats -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Failed</h3>
                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">Failed</span>
            </div>
            <div class="space-y-2">
                <div class="text-2xl font-bold text-red-600">₹{{ number_format($stats['failed_volume'], 2) }}</div>
                <div class="text-sm text-gray-600">Count: {{ number_format($stats['failed_count']) }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-wrap items-center gap-4">
            <select wire:model.live="searchColumn" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="utr">UTR Number</option>
                <option value="transaction_id">Transaction ID</option>
                <option value="payee_id">Payee ID</option>
            </select>
            
            <input type="text" wire:model.live.debounce.300ms="search" 
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1" 
                placeholder="Search...">
            
            <input type="date" wire:model.live="date" 
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            
            <select wire:model.live="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="success">Success</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>

            <select wire:model.live="perPage" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merchant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beneficiary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">UTR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payout->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $payout->user->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $payout->user->merchant_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $payout->payee->account_holder }}</div>
                            <div class="text-xs text-gray-500">{{ $payout->payee->account_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($payout->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                            {{ $payout->utr }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ strtoupper($payout->mode) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $payout->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($payout->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button class="text-blue-600 hover:text-blue-900">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No payouts found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="border-t border-gray-200 px-4 py-3">
            {{ $payouts->links() }}
        </div>
    </div>
</div>
