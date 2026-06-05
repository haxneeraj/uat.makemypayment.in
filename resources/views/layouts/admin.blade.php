@php
    $active = isset($active) ? $active : 'dashboard';
    $pageTitle = isset($pageTitle) ? $pageTitle : 'Dashboard';
    $user = auth()->user();
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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles

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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'dashboard' ? 'bg-appPrimary text-white' : 'hover:bg-gray-100' }}">
                        <!-- Home SVG icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V13h6v8" />
                        </svg>
                        Dashboard
                    </a>
                    <br />
                    @can('read-pending-kyc')
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">New Onboardings</div>
                    <a href="{{ route('admin.pending-kyc') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'pending-kyc' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Pending KYC
                    </a>
                    @endcan

                    @can('read-ip-and-webhook-verifications')
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">IP & Webhook Verification</div>
                    <a href="{{ route('admin.ip-and-callback-requests') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'ip-and-callback-requests' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                        </svg>
                        Approval Requests
                    </a>
                    @endcan

                    @can('read-pending-source-account-verifications')
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Source Accounts</div>
                    <a href="{{ route('admin.source-accounts') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'pending-source-account-verifications' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Source Accounts
                    </a>
                    @endcan

                    @can('read-merchants')
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">MERCHANT</div>
                    <a href="{{ route('admin.merchants') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 {{ $active == 'merchants' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <!-- Updated Merchant SVG icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Merchants
                    </a>
                    @endcan

                    @can('read-reports')
                    <!-- Reports menu item -->
                    <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Reports</div>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'reports' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M8 8h8M8 12h8M8 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Reports
                    </a>
                    @endcan
                </nav>

                @canany(['read-categories', 'read-sub-categories'])
                <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Categories</div>
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'categories' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M7 7a2 2 0 110-4 2 2 0 010 4zM17 7a2 2 0 110-4 2 2 0 010 4z" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path d="M7 17a2 2 0 110-4 2 2 0 010 4zM17 17a2 2 0 110-4 2 2 0 010 4z" stroke="currentColor" stroke-width="2" fill="none"/>
                    </svg>
                    Categories
                </a>

                <a href="{{ route('admin.sub-categories') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900  {{ $active == 'sub-categories' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h6"/>
                    </svg>
                    Sub Categories
                </a>
                @endcanany


                @canany(['read-roles', 'read-staff'])
                <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">Accounts & Permissions</div>
                @endcanany
                @can('read-roles')
                <a href="{{ route('admin.roles') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 w-full mb-2 {{ $active == 'roles' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Roles
                </a>
                @endcan

                @can('read-staff')
                <a href="{{ route('admin.staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 w-full mb-2 {{ $active == 'staffs' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197v0"/>
                    </svg>
                    Staffs
                </a>
                @endcan


                <div class="mt-6 mb-2 text-xs text-gray-400 font-semibold px-3">System</div>
                @can('read-settings')
                <!-- Setting Button -->
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-gray-900 w-full mb-2 {{ $active == 'settings' ? 'bg-appPrimary text-white' : 'hover:bg-appPrimary hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                    Settings
                </a>
                @endcan

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
                    <h1 class="text-lg lg:text-xl font-semibold text-gray-900">{{ $pageTitle }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-9 h-9 rounded-full bg-appPrimary flex items-center justify-center text-xs font-bold text-white focus:outline-none">
                            {{ strtoupper(substr(auth()->user()->full_name ?? 'SB', 0, 2)) }}
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                            
                            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
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
                {{ $slot }}
            </main>
            <div class="w-full py-4 flex justify-center items-center bg-white border-t border-gray-200">
                <p class="text-xs lg:text-sm text-gray-500 text-center px-4">&copy; {{ date('Y') }} MMP Fintech Payment Solutions Pvt. Ltd. All rights reserved.</p>
            </div>
        </div>
    </div>
    @stack('modals')

    <!-- Toaster -->
    <x-toaster.toaster />

    <!-- Livewire Scripts -->
    @livewireScripts
    
    @stack('scripts')
    
</body>
</html>