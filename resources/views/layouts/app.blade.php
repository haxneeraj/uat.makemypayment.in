@php
    $active = isset($active) ? $active : 'dashboard';
    $pageTitle = isset($pageTitle) ? $pageTitle : 'Dashboard';
    $user = auth()->user();
    $van = $user->merchantVirtualAccount?->van;
    $van = blank($van) ? 'N/A' : $van;
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? config('app.name', 'MakeMyPayment') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }        
    </style>
    
</head>
<body class="font-sans antialiased bg-[#fafbfc] text-gray-900">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 lg:hidden" 
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <!-- Logo and Close Button -->
            <div class="mb-4 px-4 pt-4 flex items-center justify-between">
                <img class="h-10 w-auto mx-auto" src="{{ asset('makemypayment-logo.png') }}" alt="Logo">
                <!-- Close button for mobile -->
                <button @click="sidebarOpen = false" 
                        class="lg:hidden p-2 rounded-md text-red-400 hover:text-red-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="pt-4 px-4 overflow-y-auto pb-20">                
                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'dashboard' ? 'bg-appPrimary text-white' : 'hover:bg-gray-100' }}">
                        <!-- Home SVG icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V13h6v8" />
                        </svg>
                        Dashboard
                    </a>

                    <br />
                    <div class="text-xs text-gray-400 font-semibold px-3">MERCHANT</div>
                    <a href="{{ route('merchant.payouts') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'payouts' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="8" rx="2" stroke="currentColor" stroke-width="2" fill="none"/><path d="M7 8V6a5 5 0 0110 0v2" stroke="currentColor" stroke-width="2"/></svg>
                        Payouts
                    </a>

                    <a href="{{ route('merchant.wallet') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'wallet' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none"/><path d="M2 10h20" stroke="currentColor" stroke-width="2"/></svg>
                        VAN
                    </a>

                    <!-- Deposits menu item -->
                    {{-- <a href="{{ route('merchant.deposits') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'deposits' ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Deposits
                    </a> --}}
                    <!-- Reports menu item -->
                    <a href="{{ route('merchant.reports') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'reports' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M8 8h8M8 12h8M8 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Reports
                    </a>
                    <a href="{{ route('merchant.invoices') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'invoices' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Invoices
                    </a>

                    <br />
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Profile Details</div>
                    <a href="{{ route('merchant.organization') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'organization' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2" fill="none"/><path d="M16 3v4M8 3v4" stroke="currentColor" stroke-width="2"/></svg>
                        Organization
                    </a>

                    <!-- API Docs moved here -->
                    <br />
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Developer Tools</div>
                    <a target="_blank" href="https://developer.makemypayment.in" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'API' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M8 8h8M8 12h8M8 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        API Docs
                    </a>
                </nav>

                <br />
                <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">System</div>
                <!-- Setting Button -->
                <a href="{{ route('merchant.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  w-full mb-2 {{ $active == 'settings' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                    Settings
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ url('logout') }}" class="mt-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 hover:bg-red-100 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0 max-w-full overflow-x-hidden flex flex-col min-h-screen lg:ml-0">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 flex items-center px-4 lg:px-8 h-16 justify-between">
                <div class="flex items-center gap-4 lg:gap-6">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" 
                            class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex min-w-0 items-center gap-3 lg:gap-4">
                        <h1 class="text-lg lg:text-xl font-semibold text-gray-900 truncate">{{ $pageTitle }}</h1>
                        <span class="hidden sm:inline text-xs text-gray-400 whitespace-nowrap">VAN - {{ $van }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 lg:gap-4">
                    <div class="hidden xl:flex items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $kycStatusClasses }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            KYC {{ $user->kyc_status }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $vanStatusClasses }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            VAN {{ $user->van_status }}
                        </span>
                    </div>
                    <a href="{{ route('merchant.payouts') }}" class="bg-appPrimary text-white px-3 lg:px-5 py-2 rounded-lg font-semibold flex items-center gap-2 hover:opacity-80 transition text-xs lg:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">Create Payout</span>
                        <span class="sm:hidden">+</span>
                    </a>
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-9 h-9 rounded-full bg-appPrimary flex items-center justify-center text-xs font-bold text-white focus:outline-none">
                            {{ strtoupper(substr(auth()->user()->full_name ?? 'SB', 0, 2)) }}
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                            <a href="{{ route('merchant.organization') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <!-- Organization SVG -->
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <path d="M16 3v4M8 3v4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Organization
                            </a>
                            <a href="{{ route('merchant.settings') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <!-- Settings SVG -->
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                                </svg>
                                Settings
                            </a>
                            <form method="POST" action="{{ url('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <!-- Logout SVG -->
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Page Content -->
            <main class="flex-1 min-w-0 max-w-full overflow-y-auto overflow-x-hidden p-4 lg:p-6">
                @if(($user->kyc_status === 'pending' || $user->kyc_status === 'rejected') && request()->route()->getName() !== 'merchant.kyc')
                {{-- Pending KYC Section --}}
                <x-merchant.pending-kyc-card />
                {{-- End Pending KYC Section --}}
                @endif

                @if($user->kyc_status === 'submitted' && request()->route()->getName() !== 'merchant.kyc' && request()->route()->getName() !== 'merchant.kyc.status')
                {{-- Submitted KYC Section --}}
                <x-merchant.waiting-kyc-card />
                {{-- End Submitted KYC Section --}}
                @endif

                {{ $slot }}
            </main>
            <div class="w-full py-4 flex justify-center items-center bg-white border-t border-gray-200">
                <p class="text-xs lg:text-sm text-gray-500 text-center px-4">&copy; {{ date('Y') }} MMP Fintech Payment Solutions Pvt. Ltd. All rights reserved.</p>
            </div>
        </div>
    </div>
    

    <!-- Toaster -->
    <x-toaster.toaster />
    
    <!-- Page Specific Modals -->
    @stack('modals')

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>