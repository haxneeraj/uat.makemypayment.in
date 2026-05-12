<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-bold text-[#1a237e]">Pending KYC Applications</h1>
        <div class="flex">
            <input type="text" wire:model.live.debounce.500ms="search" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full md:w-auto" placeholder="Search by name, email, merchant ID...">
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-2 md:p-6 mt-2">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="hidden md:table-header-group">
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Merchant ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Mobile</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">KYC Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 md:divide-y">
                    @forelse($merchants as $merchant)
                        <tr class="block md:table-row mb-4 md:mb-0 border-b md:border-b-0 rounded-lg md:rounded-none bg-white md:bg-transparent shadow-md md:shadow-none">
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell font-mono text-xs">
                                <span class="font-semibold md:hidden mr-2">#</span>
                                <span>{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell font-mono text-xs">
                                <span class="font-semibold md:hidden mr-2">Merchant ID</span>
                                <span>{{ $merchant->merchant_id }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Name</span>
                                <span class="text-right">{{ $merchant->full_name }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Email</span>
                                <span class="text-right">{{ $merchant->email }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Mobile</span>
                                <span>{{ $merchant->phone }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">KYC Status</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    Submitted
                                </span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Actions</span>
                                <div class="flex gap-2 flex-wrap justify-end">
                                    <!-- View Button -->
                                    <a target="_blank" href="{{ route('admin.view-kyc', $merchant->merchant_id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
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
                {{ $merchants->links() }}
            </div>
        </div>
    </div>
</div>
