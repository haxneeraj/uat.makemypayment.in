<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-bold text-[#1a237e]">Merchant Management</h1>
        <div class="flex flex-col md:flex-row gap-2">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm"
                placeholder="Search by name, email, merchant ID, phone">
            <select wire:model.live="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <select wire:model.live="kyc_status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">KYC Status</option>
                @foreach ($kyc_statuses as $kyc)
                    <option value="{{ $kyc }}">{{ ucfirst($kyc) }}</option>
                @endforeach
            </select>
            <select wire:model.live="van_status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">VAN Status</option>
                @foreach ($van_statuses as $van)
                    <option value="{{ $van }}">{{ ucfirst($van) }}</option>
                @endforeach
            </select>
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
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Phone</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">KYC Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">VAN Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 md:divide-y">
                    @forelse($merchants as $merchant)
                        <tr
                            class="block md:table-row mb-4 md:mb-0 border-b md:border-b-0 rounded-lg md:rounded-none bg-white md:bg-transparent shadow-md md:shadow-none">
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
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
                                <span class="font-semibold md:hidden mr-2">Phone</span>
                                <span>{{ $merchant->phone }}</span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Status</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($merchant->status === 'active') bg-green-100 text-green-600
                                    @elseif($merchant->status === 'inactive') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-600 @endif">
                                    {{ ucfirst($merchant->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">KYC Status</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($merchant->kyc_status === 'verified') bg-green-100 text-green-600
                                    @elseif($merchant->kyc_status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-600 @endif">
                                    {{ ucfirst($merchant->kyc_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">VAN Status</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($merchant->van_status === 'verified') bg-green-100 text-green-600
                                    @elseif($merchant->van_status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-600 @endif">
                                    {{ ucfirst($merchant->van_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell">
                                <span class="font-semibold md:hidden mr-2">Actions</span>
                                <div class="flex gap-2 flex-wrap justify-end">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.merchants.view', $merchant->merchant_id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.merchants.edit', $merchant->merchant_id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h4" />
                                        </svg>
                                        Edit
                                    </a>
                                    @if($merchant->status == 'active')
                                    <!-- Suspend Button -->
                                    <button wire:click="confirmSuspend('{{ $merchant->merchant_id }}', 'suspend')"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                        </svg>
                                        Suspend
                                    </button>
                                    @else
                                    <!-- Status Change Button -->
                                    <button wire:click="confirmSuspend('{{ $merchant->merchant_id }}', 'active')" 
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-600 hover:bg-green-100 text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Activate
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-400">No merchants found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="flex justify-end mt-4">
                {{ $merchants->links() }}
            </div>
        </div>
    </div>
    
    <!-- Suspension Confirmation Modal -->
    @if ($showConfirmation)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full {{$modalType == 'active' ? 'bg-green-100' : 'bg-red-100' }} sm:mx-0 sm:h-10 sm:w-10">
                                @if($modalType == 'suspend')
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                @else
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                </svg>
                                @endif
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                    {{ $modalType == 'active' ? 'Activate' : 'Suspend' }} Merchant Account
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to {{ $modalType == 'active' ? 'activate' : 'suspend' }} {{ $selectedMerchant?->full_name }}? This
                                        action will {{ $modalType == 'active' ? 'allow' : 'prevent' }} the merchant from making any transactions.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button wire:click="suspendMerchant" type="button"
                                class="inline-flex w-full justify-center rounded-md {{$modalType == 'active' ? 'bg-green-600 hover:bg-green-500' : 'bg-red-600 hover:bg-red-500' }} px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto">
                                {{ $modalType == 'active' ? 'Activate Account' : 'Suspend Account' }}
                            </button>
                            <button wire:click="cancelSuspend" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>