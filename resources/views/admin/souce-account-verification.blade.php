<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-bold text-[#1a237e]">Source Account Verification</h1>
        <div class="flex w-full md:w-auto">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full md:w-80"
                placeholder="Search by merchant, account, IFSC..."
            >
        </div>
    </div>

    @if(session()->has('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-2 md:p-6 mt-2">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="hidden md:table-header-group">
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Merchant</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Account Details</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Document</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Submitted On</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 md:divide-y">
                    @forelse($accounts as $account)
                        <tr class="block md:table-row mb-4 md:mb-0 border-b md:border-b-0 rounded-lg md:rounded-none bg-white md:bg-transparent shadow-md md:shadow-none">
                            <td class="px-4 py-3 flex justify-between items-center md:table-cell font-mono text-xs">
                                <span class="font-semibold md:hidden mr-2">#</span>
                                <span>{{ $accounts->firstItem() + $loop->index }}</span>
                            </td>

                            <td class="px-4 py-3 md:table-cell">
                                <div class="flex justify-between items-start md:block w-full">
                                    <span class="font-semibold md:hidden mr-2">Merchant</span>
                                    <div class="text-right md:text-left">
                                        <div class="font-semibold text-gray-800">{{ $account->user?->full_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $account->user?->merchant_id ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $account->user?->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 md:table-cell">
                                <div class="flex justify-between items-start md:block w-full">
                                    <span class="font-semibold md:hidden mr-2">Account</span>
                                    <div class="text-right md:text-left">
                                        <div class="font-semibold text-gray-800">{{ $account->account_holder_name }}</div>
                                        <div class="text-xs text-gray-600">A/C: {{ $account->account_number }}</div>
                                        <div class="text-xs text-gray-600">IFSC: {{ $account->ifsc_code }}</div>
                                        <div class="text-xs text-gray-600">Bank: {{ $account->bank_name }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 md:table-cell">
                                <div class="flex justify-between items-center md:block w-full">
                                    <span class="font-semibold md:hidden mr-2">Document</span>
                                    <div class="text-right md:text-left">
                                        <div class="text-xs text-gray-600 mb-1">
                                            {{ $account->document_type === 'statement' ? 'Bank Statement' : 'Cancelled Cheque' }}
                                        </div>
                                        @if($account->document)
                                            <a
                                                href="{{ asset('storage/' . $account->document) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1 text-xs font-semibold text-appPrimary hover:underline"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                View File
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">No document</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 md:table-cell">
                                <div class="flex justify-between items-center md:block w-full">
                                    <span class="font-semibold md:hidden mr-2">Submitted</span>
                                    <span>{{ $account->created_at?->format('d M Y, h:i A') }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-3 md:table-cell">
                                <div class="flex justify-between items-center md:block w-full">
                                    <span class="font-semibold md:hidden mr-2">Actions</span>
                                    <div class="flex gap-2 justify-end md:justify-start">
                                        <button
                                            wire:click="approveAccount({{ $account->id }})"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-70 cursor-not-allowed"
                                            wire:target="approveAccount({{ $account->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-600 hover:bg-green-100 text-xs font-semibold transition"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                        <button
                                            wire:click="openRejectModal({{ $account->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">No source accounts pending for verification.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-4">
            {{ $accounts->links() }}
        </div>
    </div>

    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" wire:key="reject-source-account-modal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reject Source Account</h3>
                <p class="text-sm text-gray-500 mb-4">Please provide a remark for rejection. This helps merchant understand the issue.</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Remark</label>
                    <textarea
                        wire:model.defer="rejectRemark"
                        rows="4"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-appPrimary focus:ring-appPrimary text-sm @error('rejectRemark') border-red-400 @enderror"
                        placeholder="Enter remark for rejection"
                    ></textarea>
                    @error('rejectRemark')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 mt-5">
                    <button
                        type="button"
                        wire:click="closeRejectModal"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-70 cursor-not-allowed"
                        wire:target="closeRejectModal,rejectAccount"
                        class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="rejectAccount"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-70 cursor-not-allowed"
                        wire:target="rejectAccount"
                        class="flex-1 bg-red-600 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-red-700 transition"
                    >
                        <span wire:loading.remove wire:target="rejectAccount">Reject Account</span>
                        <span wire:loading wire:target="rejectAccount">Rejecting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
