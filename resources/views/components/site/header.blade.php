<nav x-data="{ open: false }" class="bg-slate-900/95 backdrop-blur-lg border-b border-slate-800 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('site.home') }}">
                    <img class="h-24 w-auto hover:scale-105 hover:brightness-125 transition-all duration-300 ease-out" src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment Logo">
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="/" class="text-slate-100 hover:text-indigo-400 font-medium transition-colors text-sm">Home</a>
                <a href="{{ route('site.about') }}" class="text-slate-100 hover:text-indigo-400 font-medium transition-colors text-sm">About Us</a>
                <a href="{{ route('site.service') }}" class="text-slate-100 hover:text-indigo-400 font-medium transition-colors text-sm">Services</a>
                <a href="{{ route('site.contact') }}" class="text-slate-100 hover:text-indigo-400 font-medium transition-colors text-sm">Contact</a>
                <a href="/login" class="text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm border-l border-slate-700 pl-6 ml-2 flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.75 6C15.75 8.07107 14.0711 9.75 12 9.75C9.92893 9.75 8.25 8.07107 8.25 6C8.25 3.92893 9.92893 2.25 12 2.25C14.0711 2.25 15.75 3.92893 15.75 6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4.5 20.25C4.5 16.5266 7.52665 13.5 11.25 13.5H12.75C16.4734 13.5 19.5 16.5266 19.5 20.25V21.75H4.5V20.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Login
                </a>
                <a href="/register" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg text-sm hover:bg-indigo-500 transition-colors gap-2">
                    Sign Up Free
                    <svg class="w-4 h-4 ml-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <!-- Mobile menu button -->
            <button
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-100 hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                @click="open = !open" 
                :aria-expanded="open.toString()" 
                aria-controls="mobile-menu">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div 
            id="mobile-menu"
            class="lg:hidden overflow-hidden transition-all duration-300 ease-in-out"
            :class="open ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-1"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-1">
            
            <div class="px-2 pt-2 pb-3 space-y-1 bg-slate-900 border-t border-slate-800 shadow-lg">
                <a href="/" 
                   class="block px-3 py-2 text-base font-medium text-slate-100 hover:text-indigo-400 hover:bg-slate-800 rounded-md transition-colors"
                   @click="open = false">Home</a>
                
                <a href="{{ route('site.about') }}" 
                   class="block px-3 py-2 text-base font-medium text-slate-100 hover:text-indigo-400 hover:bg-slate-800 rounded-md transition-colors"
                   @click="open = false">About Us</a>
                
                <a href="{{ route('site.service') }}" 
                   class="block px-3 py-2 text-base font-medium text-slate-100 hover:text-indigo-400 hover:bg-slate-800 rounded-md transition-colors"
                   @click="open = false">Services</a>
                
                <a href="{{ route('site.contact') }}" 
                   class="block px-3 py-2 text-base font-medium text-slate-100 hover:text-indigo-400 hover:bg-slate-800 rounded-md transition-colors"
                   @click="open = false">Contact</a>
                
                <hr class="my-2 border-slate-700">
                
                <a href="/login" 
                   class="flex items-center px-3 py-2 text-base font-semibold text-indigo-400 hover:bg-slate-800 rounded-md transition-colors gap-2"
                   @click="open = false">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.75 6C15.75 8.07107 14.0711 9.75 12 9.75C9.92893 9.75 8.25 8.07107 8.25 6C8.25 3.92893 9.92893 2.25 12 2.25C14.0711 2.25 15.75 3.92893 15.75 6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4.5 20.25C4.5 16.5266 7.52665 13.5 11.25 13.5H12.75C16.4734 13.5 19.5 16.5266 19.5 20.25V21.75H4.5V20.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Login
                </a>
                
                <a href="/register" 
                   class="flex items-center justify-center mx-3 my-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-500 transition-colors gap-2"
                   @click="open = false">
                    Sign Up Free
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>