@php
    $user = auth()->user();
    $virtualAccount = $user->merchantVirtualAccount;
    $businessName = $kyc->business_name ?: 'Organization';
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $businessName), 0, 3));
    $website = blank($kyc->website_url) ? ($kyc->app_url ?? 'N/A') : $kyc->website_url;
    $kycStatusClasses = match ($user->kyc_status) {
        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
        default => 'bg-amber-50 text-amber-700 border-amber-200',
    };
    $vanStatusClasses = match ($user->van_status) {
        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
        default => 'bg-amber-50 text-amber-700 border-amber-200',
    };
@endphp

<div class="relative overflow-hidden rounded-3xl bg-white p-4 sm:p-6 lg:p-8 shadow-inner space-y-6" x-data="{
    tab: 'overview',
    showCopiedToast: false,
    copiedMessage: '',
    copyToClipboard(text, field) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                this.copiedMessage = field === 'van' ? 'Account number copied!' : 'IFSC code copied!';
                this.showCopiedToast = true;
                setTimeout(() => this.showCopiedToast = false, 2000);
            }).catch(() => this.fallbackCopy(text, field));
        } else {
            this.fallbackCopy(text, field);
        }
    },
    fallbackCopy(text, field) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            this.copiedMessage = field === 'van' ? 'Account number copied!' : 'IFSC code copied!';
            this.showCopiedToast = true;
            setTimeout(() => this.showCopiedToast = false, 2000);
        } catch (err) {
            console.error('Copy failed', err);
        }
        document.body.removeChild(textarea);
    }
}">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#2b3990] via-[#3347ac] to-[#5164c4] border border-[#3c4fae] shadow-sm p-5 sm:p-7 text-white">
        <div class="grid gap-8 lg:grid-cols-[1.5fr,1fr]">
            <div class="min-w-0">
                <div class="mb-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-100 backdrop-blur-sm">
                        Merchant Profile
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white/95 backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        KYC {{ $user->kyc_status }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white/95 backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        VAN {{ $user->van_status }}
                    </span>
                </div>

                <div class="flex flex-col gap-5 md:flex-row md:items-start">
                    <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-500 to-indigo-400 text-2xl font-bold tracking-[0.18em] text-white ring-1 ring-white/40">
                        {{ $initials ?: 'ORG' }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 class="text-3xl font-black tracking-tight text-white sm:text-4xl">{{ $businessName }}</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-indigo-100/95">
                            Manage your organization profile, registered source accounts, callback configuration, and virtual account details from one place.
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3 text-sm">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 border border-white/30 px-3 py-1.5 text-indigo-50 backdrop-blur-sm">
                                <svg class="h-4 w-4 text-indigo-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5"/>
                                </svg>
                                {{ $kyc->email ?: 'Email not available' }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/15 border border-white/30 px-3 py-1.5 text-indigo-50 backdrop-blur-sm">
                                <svg class="h-4 w-4 text-indigo-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.21l-2.26 1.13a11.04 11.04 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                                </svg>
                                {{ $kyc->mobile ?: 'Mobile not available' }}
                            </span>
                            <a href="{{ $website !== 'N/A' ? $website : '#' }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-white/15 border border-white/30 px-3 py-1.5 text-indigo-50 backdrop-blur-sm {{ $website === 'N/A' ? 'pointer-events-none opacity-60' : 'hover:bg-white/25' }}">
                                <svg class="h-4 w-4 text-indigo-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3c2.5 2.4 4 5.73 4 9s-1.5 6.6-4 9c-2.5-2.4-4-5.73-4-9s1.5-6.6 4-9z"/>
                                </svg>
                                {{ $website }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-3xl p-5 sm:p-6 bg-[#fffa81] text-slate-900 border border-[#fffa81] shadow-sm">
                    <p class="text-xs uppercase tracking-[0.18em] text-[#6d4ca2] font-semibold">Registered Source Accounts</p>
                    <p class="mt-3 text-3xl font-black">{{ $sourceAccounts->count() }}</p>
                    <p class="mt-2 text-sm text-slate-700">Only registered bank accounts are accepted for inward wallet funding.</p>
                </div>
                <div class="rounded-3xl p-5 sm:p-6 bg-gray-900 border border-gray-900 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.18em] text-white font-semibold">Virtual Account</p>
                    <p class="mt-3 break-all font-mono text-lg font-bold text-white">{{ $virtualAccount?->van ?: 'Not Assigned Yet' }}</p>
                    <p class="mt-2 text-sm text-indigo-100">{{ $virtualAccount?->ifsc ?: 'IFSC will appear here once the VAN is activated.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-[#2b3990] text-white shadow-md' : 'bg-white text-slate-600 border border-[#e2e6f3] hover:bg-indigo-50'" class="inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-sm font-semibold transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V13h6v8"/>
            </svg>
            Overview
        </button>
        <button @click="tab = 'source-accounts'" :class="tab === 'source-accounts' ? 'bg-[#2b3990] text-white shadow-md' : 'bg-white text-slate-600 border border-[#e2e6f3] hover:bg-indigo-50'" class="inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-sm font-semibold transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 10v11m4-11v11m4-11v11"/>
            </svg>
            Source Accounts
        </button>
        <button @click="tab = 'virtual-account'" :class="tab === 'virtual-account' ? 'bg-[#2b3990] text-white shadow-md' : 'bg-white text-slate-600 border border-[#e2e6f3] hover:bg-indigo-50'" class="inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-sm font-semibold transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18"/>
            </svg>
            Virtual Account
        </button>
        <button @click="tab = 'configuration'" :class="tab === 'configuration' ? 'bg-[#2b3990] text-white shadow-md' : 'bg-white text-slate-600 border border-[#e2e6f3] hover:bg-indigo-50'" class="inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-sm font-semibold transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Configuration
        </button>
    </div>

    <div x-show="tab === 'overview'" x-cloak class="grid gap-6 lg:grid-cols-[1.4fr,1fr]">
        <div class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Organization Snapshot</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Business Information</h3>
                </div>
                <span class="rounded-full bg-white border border-[#e3e7f3] px-3 py-1 text-xs font-semibold text-slate-600">{{ ucfirst(str_replace('_', ' ', $kyc->business_type ?: 'merchant')) }}</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Company Name</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $businessName }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Director / Owner</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $kyc->full_name ?: 'N/A' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">GSTIN</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900 break-all">{{ $kyc->gstin ?: 'N/A' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">CIN Number</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900 break-all">{{ $kyc->cin_number ?: 'N/A' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">PAN / TAN</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900 break-all">{{ $kyc->pan ?: 'N/A' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Location</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ collect([$kyc->city, $kyc->state, $kyc->country])->filter()->join(', ') ?: 'N/A' }}</p>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-[#e9edf6] bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Business Address</p>
                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $kyc->business_address ?: 'No business address available.' }}</p>
                <p class="mt-3 text-xs text-slate-500">Pincode: {{ $kyc->pin_code ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Source Account Summary</p>
                <h3 class="mt-2 text-xl font-semibold text-slate-900">Funding Account Status</h3>
                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <span class="text-sm text-slate-600">Total Accounts</span>
                        <span class="text-base font-semibold text-slate-900">{{ $sourceAccounts->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <span class="text-sm text-slate-600">Active Accounts</span>
                        <span class="text-base font-semibold text-emerald-700">{{ $sourceAccounts->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <span class="text-sm text-slate-600">Primary Account</span>
                        <span class="text-base font-semibold text-slate-900">{{ optional($sourceAccounts->firstWhere('is_primary', true))->bank_name ?: 'Not marked' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-[#fff8df] p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Important Notice</p>
                <p class="mt-3 text-sm leading-6 text-amber-900">Only funds received from registered source accounts are eligible for wallet credit. Payments received from any unregistered bank account may not be credited.</p>
            </div>
        </div>
    </div>

    <div x-show="tab === 'source-accounts'" x-cloak class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Registered Funding Accounts</p>
                <h3 class="mt-2 text-xl font-semibold text-slate-900">Source Accounts</h3>
                <p class="mt-2 text-sm text-slate-500">These bank accounts are approved for inward fund collection into your wallet.</p>
            </div>
            <a href="{{ route('merchant.source-accounts') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#2b3990] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#23307a]">
                Manage Accounts
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @forelse($sourceAccounts as $account)
            <div class="mb-4 rounded-3xl border border-[#e9edf6] bg-white p-5 last:mb-0">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-[#2b3990] px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">Account {{ $loop->iteration }}</span>
                            @if($account->is_primary)
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-blue-700 border border-blue-200">Primary</span>
                            @endif
                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $account->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">{{ ucfirst($account->status) }}</span>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Bank Name</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $account->bank_name ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Account Holder</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $account->account_holder_name ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Account Number</p>
                                <p class="mt-2 break-all font-mono text-sm font-semibold text-slate-900">{{ $account->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">IFSC Code</p>
                                <p class="mt-2 break-all font-mono text-sm font-semibold text-slate-900">{{ $account->ifsc_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-[#dfe4ef] bg-white px-6 py-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                    <svg class="h-6 w-6 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 10v11m4-11v11m4-11v11"/>
                    </svg>
                </div>
                <h4 class="mt-4 text-lg font-semibold text-slate-900">No source accounts configured</h4>
                <p class="mt-2 text-sm text-slate-500">Add and verify source accounts to ensure inward wallet funding is mapped correctly.</p>
                <a href="{{ route('merchant.source-accounts') }}" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-[#2b3990] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#23307a]">
                    Add Source Account
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
        @endforelse
    </div>

    <div x-show="tab === 'virtual-account'" x-cloak class="grid gap-6 lg:grid-cols-[1.3fr,0.9fr]">
        <div class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Collection Details</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Virtual Account</h3>
                </div>
                <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $vanStatusClasses }}">{{ $user->van_status }}</span>
            </div>

            @if($virtualAccount)
                <div class="relative overflow-hidden rounded-[30px] border border-[#4a3812] bg-[linear-gradient(135deg,#090703_0%,#161006_35%,#2a1e09_62%,#3b2b0d_100%)] p-6 text-white">
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,214,128,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(255,191,73,0.10),transparent_33%)]"></div>
                    <div class="pointer-events-none absolute -right-10 top-10 h-40 w-40 rounded-full border border-white/8"></div>
                    <div class="pointer-events-none absolute -right-2 top-18 h-24 w-24 rounded-full border border-white/8"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-amber-100/85">Assigned Virtual Account</p>
                                <h4 class="mt-3 text-lg font-semibold text-white">MMP Collection Card</h4>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="hidden rounded-2xl border border-white/15 bg-white/10 px-3 py-2 backdrop-blur-sm sm:flex">
                                    <img src="{{ asset('makemypayment-logo.png') }}" alt="MMP" class="h-8 w-auto brightness-0 invert">
                                </div>
                                <div class="flex items-center gap-2 rounded-full border border-white/15 bg-white/8 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white/90 backdrop-blur-sm">
                                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                                    {{ $user->van_status }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="relative h-11 w-16 rounded-xl border border-[#f5d37d]/80 bg-[linear-gradient(140deg,#ffefb8_0%,#e3bf67_28%,#b8872a_62%,#8f6720_100%)] shadow-[inset_0_1px_0_rgba(255,255,255,0.52),inset_0_-1px_0_rgba(90,58,8,0.45)] overflow-hidden">
                                    <div class="absolute inset-x-1 top-[45%] h-[1px] bg-[#6f4e14]/55"></div>
                                    <div class="absolute left-1.5 right-1.5 top-1.5 bottom-1.5 rounded-md border border-[#fff0c7]/45"></div>
                                    <div class="absolute left-1.5 top-1.5 w-[1px] h-8 bg-[#916622]/60"></div>
                                    <div class="absolute left-4 top-1.5 w-[1px] h-8 bg-[#916622]/60"></div>
                                    <div class="absolute left-6.5 top-1.5 w-[1px] h-8 bg-[#916622]/60"></div>
                                    <div class="absolute left-9 top-1.5 w-[1px] h-8 bg-[#916622]/60"></div>
                                    <div class="absolute left-1.5 top-4 w-11 h-[1px] bg-[#916622]/55"></div>
                                    <div class="absolute left-1.5 top-7 w-11 h-[1px] bg-[#916622]/55"></div>
                                </div>
                                <div class="sm:hidden rounded-xl border border-white/15 bg-white/10 px-2.5 py-1.5 backdrop-blur-sm">
                                    <img src="{{ asset('makemypayment-logo.svg') }}" alt="MMP" class="h-6 w-auto brightness-0 invert">
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[11px] uppercase tracking-[0.24em] text-amber-100/80">Bank Partner</p>
                                <p class="mt-1 text-sm font-semibold text-white">HDFC Bank</p>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center gap-2">
                            <p class="break-all font-mono text-[1.7rem] font-semibold tracking-[0.30em] text-white sm:text-3xl">{{ $virtualAccount->van }}</p>
                            <button @click="copyToClipboard('{{ $virtualAccount->van }}', 'van')" class="shrink-0 rounded p-1.5 transition hover:bg-white/15" title="Copy account number">
                                <svg class="h-5 w-5 text-white/80 hover:text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/12 bg-white/[0.08] p-4 backdrop-blur-md">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-100/80">Account Holder</p>
                                <p class="mt-2 text-sm font-semibold text-white">{{ $virtualAccount->account_holder ?: 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/12 bg-white/[0.08] p-4 backdrop-blur-md">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-100/80">IFSC</p>
                                    @if($virtualAccount->ifsc && $virtualAccount->ifsc !== 'N/A')
                                        <button @click="copyToClipboard('{{ $virtualAccount->ifsc }}', 'ifsc')" class="rounded p-0.5 transition hover:bg-white/10" title="Copy IFSC code">
                                            <svg class="h-3.5 w-3.5 text-slate-300/70 hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <p class="mt-2 font-mono text-sm font-semibold text-white">{{ $virtualAccount->ifsc ?: 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/12 bg-white/[0.08] p-4 backdrop-blur-md">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-100/80">Purpose</p>
                                <p class="mt-2 text-sm font-semibold text-white">{{ $virtualAccount->purpose ?: 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/12 bg-white/[0.08] p-4 backdrop-blur-md">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-100/80">Live Balance</p>
                                <p class="mt-2 text-sm font-semibold text-white">Rs. {{ number_format((float) $virtualAccount->balance, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                        <svg class="h-6 w-6 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="mt-4 text-lg font-semibold text-slate-900">Virtual account not assigned yet</h4>
                    <p class="mt-2 text-sm text-slate-500">Once your onboarding and account verification are complete, your VAN details will appear here.</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Quick Facts</p>
                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Current Status</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ ucfirst($user->van_status) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Validity</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $virtualAccount?->validity ? $virtualAccount->validity . ' days' : 'N/A' }}</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-[#e9edf6] px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Start Date</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $virtualAccount?->start_date ? $virtualAccount->start_date->format('d M Y, h:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="tab === 'configuration'" x-cloak class="rounded-3xl border border-[#eceef3] bg-[#f8f9fc] p-6 shadow-sm">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Developer Configuration</p>
            <h3 class="mt-2 text-xl font-semibold text-slate-900">Whitelist and Callback Settings</h3>
        </div>

        @if($ipandcallback)
            <div class="grid gap-4 lg:grid-cols-3">
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Whitelisted IP</p>
                    <p class="mt-2 break-all font-mono text-sm font-semibold text-slate-900">{{ $ipandcallback->ip ?: 'N/A' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Configuration Status</p>
                    <p class="mt-2 text-sm font-semibold {{ ($ipandcallback->status ?? '') === 'active' ? 'text-emerald-700' : 'text-amber-700' }}">{{ ucfirst($ipandcallback->status ?: 'pending') }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-[#e9edf6] p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Callback URL</p>
                    <p class="mt-2 break-all text-sm font-semibold text-slate-900">{{ $ipandcallback->webhook_url ?: 'N/A' }}</p>
                </div>
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-[#dfe4ef] bg-white px-6 py-10 text-center">
                <h4 class="text-lg font-semibold text-slate-900">No configuration found</h4>
                <p class="mt-2 text-sm text-slate-500">Set your IP whitelist and callback URL to enable secure payout integrations.</p>
                <a href="{{ route('merchant.settings') }}" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-[#2b3990] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#23307a]">
                    Open Settings
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <div x-show="showCopiedToast" x-transition class="fixed top-6 right-6 rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-lg z-50" x-cloak>
        ✓ <span x-text="copiedMessage"></span>
    </div>
</div>
