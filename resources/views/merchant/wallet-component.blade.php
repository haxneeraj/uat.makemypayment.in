<div class="relative overflow-hidden rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6">
    <!-- Balance & VAN Card -->
    <div class="relative overflow-hidden rounded-3xl mb-6 bg-gradient-to-br from-[#1a2870] via-[#2b3990] to-[#4158c0] border border-[#3c4fae] shadow-lg">

        {{-- Decorative blobs --}}
        <div class="pointer-events-none absolute -top-10 -right-10 w-52 h-52 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 w-36 h-36 rounded-full bg-white/5 -translate-x-1/2 translate-y-1/2"></div>

        <div class="relative p-5 sm:p-8">

            {{-- Top: Balance + Source Accounts --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-indigo-200 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Total Available Balance</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black text-white">
                        <livewire:components.merchant-balance-component />
                    </div>
                </div>
                <a href="{{ route('merchant.source-accounts') }}"
                    class="self-start inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 border border-white/25 text-white text-sm font-semibold hover:bg-white/25 transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 10v11m4-11v11m4-11v11"/>
                    </svg>
                    Source Accounts
                </a>
            </div>

            {{-- Notice --}}
            <div class="flex items-start gap-3 rounded-2xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 mb-6">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-7.938 4h15.876c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L2.33 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-200">Important Notice</p>
                    <p class="mt-1 text-xs leading-relaxed text-amber-100/80">Only payments received from your registered source account(s) will be credited to your Virtual Account Number (VAN). Funds sent from any other account may not be credited.</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-white/15 mb-6"></div>

            {{-- VAN Section --}}
            @if($van)
            <div class="mb-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Virtual Account Details</span>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-400/20 border border-emerald-400/40 px-3 py-1 text-xs font-semibold text-emerald-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Verified
                    </span>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-indigo-200">Bank</p>
                        <p class="mt-1 text-sm font-bold text-white">HDFC Bank</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-indigo-200">Account Holder</p>
                        <p class="mt-1 text-sm font-bold text-white">{{ $van->account_holder }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm p-4 sm:col-span-2">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-indigo-200">Virtual Account Number</p>
                        <p class="mt-1.5 font-mono text-base sm:text-lg font-black tracking-widest text-white break-all">{{ $van->van }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm p-4 sm:col-span-2">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-indigo-200">IFSC</p>
                        <p class="mt-1 font-mono text-sm font-bold tracking-widest text-white">{{ $van->ifsc }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="flex items-start gap-3 rounded-2xl border border-yellow-400/30 bg-yellow-400/10 p-4">
                <svg class="w-5 h-5 text-yellow-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-200">Virtual Account Not Assigned</p>
                    <p class="text-xs text-yellow-100/80 mt-0.5 leading-relaxed">Your virtual account is pending activation. Please complete your KYC verification — once approved, a virtual account will be assigned to your profile automatically.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Inward Funds Table --}}
    <div class="rounded-3xl bg-[#f8f9fc] border border-[#eceef3] shadow-sm p-5 sm:p-6">

        <div class="flex items-center justify-between gap-2 mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0 0l-4.5-4.5M12 21l4.5-4.5"/>
                </svg>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Inward Funds History</h3>
            </div>
            <select wire:model.live="perPage"
                class="cursor-pointer border border-[#dde2ef] rounded-2xl px-5 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>

        
        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-3 my-4">
            <select wire:model.live="filterBy" class="cursor-pointer border border-[#dde2ef] rounded-2xl px-6 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <option value="alert_sequence_no">Transaction Id</option>
                <option value="virtual_account">Virtual Account</option>
            </select>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="border border-[#dde2ef] rounded-2xl pl-9 pr-3 py-2 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    placeholder="Search...">

            </div>

            @if($search || $filterBy !== 'alert_sequence_no')
            <button wire:click="clearFilters"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl border border-rose-200 bg-rose-50 text-rose-600 text-sm font-semibold hover:bg-rose-100 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear Filters
            </button>
            @endif
        </div>

        {{-- Note --}}
        <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3 mb-3 text-sm text-indigo-700">
            <svg class="w-4 h-4 mt-0.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
            </svg>
            <p class="leading-relaxed">
                Click on <span class="inline-block font-mono text-xs bg-white border border-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded mx-0.5 align-middle">Transaction ID</span> to view deposit details.
                Showing current month's transactions only. To view all transactions, visit the
                <a href="{{ route('merchant.reports') }}" class="font-semibold text-indigo-600 hover:underline">Report Page</a>.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-white text-left border-b border-slate-100">
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Transaction ID</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Virtual Account</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Amount</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Transaction Date</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Description</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($deposits as $deposit)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-xs text-indigo-600 font-semibold cursor-pointer hover:underline"
                                    wire:click="$dispatch('openDepositDetailModal', { alertSequenceNo: '{{ $deposit->alert_sequence_no }}' })">{{ $deposit->alert_sequence_no }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-mono text-xs text-slate-700">{{ $deposit->virtual_account ?? $deposit->account_number }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-semibold text-slate-900">₹{{ number_format($deposit->amount, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-xs text-slate-700">{{ \Carbon\Carbon::parse($deposit->transaction_date)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($deposit->transaction_date)->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-3 max-w-xs truncate">
                                <span class="text-xs text-slate-500">{{ $deposit->transaction_description ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $sc = match($deposit->processing_status) {
                                        'success'          => 'bg-emerald-100 text-emerald-700',
                                        'duplicate'        => 'bg-amber-100 text-amber-700',
                                        'technical_reject' => 'bg-rose-100 text-rose-700',
                                        default            => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="{{ $sc }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ ucfirst(str_replace('_', ' ', $deposit->processing_status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-400">No deposits found</p>
                                <p class="text-xs text-slate-300 mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pt-4 border-t border-slate-100 mt-4">
            @if($deposits->hasPages())
                {{ $deposits->links() }}
            @else
                <div class="flex items-center justify-center gap-2 py-1 text-sm text-slate-500">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0 0l-4.5-4.5M12 21l4.5-4.5"/>
                    </svg>
                    Showing all
                    <span class="inline-flex items-center justify-center bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-xs px-2.5 py-0.5 rounded-full">
                        {{ $deposits->total() }}
                    </span>
                    {{ $deposits->total() === 1 ? 'deposit' : 'deposits' }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- Add Account Modal -->
    @if($showAddAccountModal)
    <div class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Add Source Account</h3>
                <button wire:click="$set('showAddAccountModal', false)" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="addSourceAccount" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Account Holder Name</label>
                    <input type="text" wire:model="holder_name" class="w-full rounded-2xl border border-[#dde2ef] px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="Enter account holder name">
                    @error('holder_name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Account Number</label>
                    <input type="text" wire:model="account_number" class="w-full rounded-2xl border border-[#dde2ef] px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="Enter account number">
                    @error('account_number') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                    <input type="text" wire:model="ifsc_code" class="w-full rounded-2xl border border-[#dde2ef] px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="Enter IFSC code">
                    @error('ifsc_code') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                    <input type="text" wire:model="bank" class="w-full rounded-2xl border border-[#dde2ef] px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="Enter bank name">
                    @error('bank') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" wire:click="$set('showAddAccountModal', false)"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-2xl hover:bg-slate-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit"
                        wire:loading.class="opacity-70 cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="addSourceAccount"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-[#2b3990] rounded-2xl hover:bg-[#1a2870] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Account
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    <livewire:components.deposit-detail-component />
</div>
