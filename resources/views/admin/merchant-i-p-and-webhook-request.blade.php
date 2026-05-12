<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-bold text-[#1a237e]">IP & Webhook Verification Requests</h1>
        <div class="flex">
            <input type="text" wire:model.live.debounce.500ms="search" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full md:w-auto" placeholder="Search by merchant ID, IP...">
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-2 md:p-6 mt-2">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="hidden md:table-header-group">
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Merchant ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">IP Address</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Webhook URL</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 md:divide-y">
                    @forelse($requests as $request)
                        <tr class="block md:table-row mb-4 md:mb-0 border-b md:border-b-0 rounded-lg md:rounded-none bg-white md:bg-transparent shadow-md md:shadow-none">
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell font-mono text-xs">
                                <span class="font-semibold md:hidden mr-2">#</span>
                                <span>{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell font-mono text-xs">
                                <span class="font-semibold md:hidden mr-2">Merchant ID</span>
                                <span>{{ $request->user->merchant_id }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">IP Address</span>
                                <span>{{ $request->ip }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Webhook URL</span>
                                <span class="truncate max-w-xs">{{ $request->webhook_url }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Status</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($request->status === 'verified') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Actions</span>
                                <div class="flex gap-2 flex-wrap justify-end">
                                    @if($request->status === 'pending')
                                    <button wire:click="approveRequest({{ $request->id }})" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-600 hover:bg-green-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                    <button wire:click="rejectRequest({{ $request->id }})" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">No merchants with pending KYC found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="flex justify-end mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
