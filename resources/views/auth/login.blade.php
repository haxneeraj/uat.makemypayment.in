<div
    class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fbff] via-[#f0f5ff] to-[#e8f0ff] flex items-center justify-center px-4 py-8">
    <!-- Decorative Gradient Blobs -->
    <div
        class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-[#eef2ff]/40 to-[#dfe8ff]/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
    </div>
    <div
        class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-[#6c3ec5]/10 to-[#a855f7]/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2">
    </div>
    <div
        class="absolute top-1/3 right-1/4 w-64 h-64 bg-gradient-to-bl from-[#4f63cb]/5 to-transparent rounded-full blur-3xl">
    </div>

    <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
        <!-- Left: Branding & Info Section -->
        <div class="hidden lg:flex flex-col justify-between h-full py-12">
            <div>
                <div class="mb-8">
                    <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" class="h-32 w-auto mb-1">
                    <h1 class="text-5xl font-black text-slate-900 leading-tight mb-4">
                        Smarter <span
                            class="bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] bg-clip-text text-transparent">Payouts.</span>
                        Faster <span
                            class="bg-gradient-to-r from-[#6c3ec5] to-[#a855f7] bg-clip-text text-transparent">Settlements.</span>
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-md">
                        Manage transfers, track liquidity, and keep payouts under control—all from one powerful
                        dashboard.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-[#2b3990] to-[#4a63ca]">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Instant Settlements</h3>
                            <p class="text-sm text-slate-600 mt-1">24/7 automated fund transfers via IMPS/NEFT</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Bank-Grade Security</h3>
                            <p class="text-sm text-slate-600 mt-1">OTP & session controls with real-time monitoring
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-[#fffa81]/80 to-amber-500">
                                <svg class="h-6 w-6 text-slate-900" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Smart Limits</h3>
                            <p class="text-sm text-slate-600 mt-1">Set & track daily transfer limits effortlessly
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-xs text-slate-500 mt-8">
                <p>✓ Already trusted by 1000+ merchants</p>
                <p>✓ Processing 50M+ in daily volume</p>
                <p>✓ Zero hidden fees or charges</p>
            </div>
        </div>

        <!-- Right: Login Card Section -->
        <div class="flex flex-col items-center">
            <div class="w-full max-w-md">
                <!-- Main Login Card -->
                <div class="rounded-3xl border border-white/60 bg-white/95 shadow-[0_20px_60px_rgba(43,57,144,0.15)] backdrop-blur-xl p-8"
                    x-data="{ showVerify: @entangle('showVerify') }">

                    <!-- Login Header -->
                    <div x-show="!showVerify" x-cloak>
                        <div
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-[#eef2ff] to-[#eef9f1] border border-[#dbe3ff] mb-6">
                            <span
                                class="text-[11px] font-semibold uppercase tracking-[0.16em] bg-gradient-to-r from-[#2b3990] to-emerald-600 bg-clip-text text-transparent">🔐
                                Secure Access</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Welcome Back</h2>
                        <p class="text-slate-600 mb-8">Sign in to manage your payouts and settlements</p>
                    </div>

                    <!-- OTP Header -->
                    <div x-show="showVerify" x-cloak>
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#4f63cb] to-[#6c3ec5] mb-6">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Verify Your Identity</h2>
                        <p class="text-slate-600 mb-8">Enter the 6-digit code sent to your mobile</p>
                    </div>

                    <!-- Session Status & Validation Errors -->
                    <x-auth-session-status
                        class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-700"
                        :status="session('status')" />
                    <x-auth-validation-errors
                        class="mb-4 rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm text-rose-700"
                        :errors="$errors" />

                    <!-- Forms Container -->
                    <div x-data="{ showVerify: @entangle('showVerify'), showPassword: false, resendTimer: @entangle('resendTimer'), resendInterval: null }" x-init="$watch('showVerify', value => {
                        if (value) startTimer();
                    });
                    
                    function startTimer() {
                        if (resendInterval) clearInterval(resendInterval);
                        if (resendTimer > 0) {
                            resendInterval = setInterval(() => {
                                if (resendTimer > 0) { resendTimer--; }
                                if (resendTimer <= 0 && resendInterval) clearInterval(resendInterval);
                            }, 1000);
                        }
                    }
                    if (showVerify) startTimer();">

                        <!-- Login Form -->
                        <form x-show="!showVerify" wire:submit.prevent="login" class="space-y-5" autocomplete="off"
                            x-cloak>
                            @csrf
                            <div>
                                <label for="email"
                                    class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Email
                                    Address</label>
                                <input id="email" name="email" type="email" wire:model.defer="email" required
                                tabindex="1"
                                    autofocus placeholder="merchant@company.com"
                                    class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <label for="password"
                                        class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700">Password</label>
                                    <a href="{{ route('password.request', [], false) }}"
                                        class="text-xs font-semibold text-[#4f63cb] hover:text-[#3347ac] transition">
                                        Forgot?
                                    </a>
                                </div>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                        wire:model.defer="password" required placeholder="Enter password"
                                        tabindex="2"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base pr-11 text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <template x-if="!showPassword">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>
                                        <template x-if="showPassword">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m2.59-2.41A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                            </div>

                            <label for="remember" class="inline-flex items-center gap-2.5 cursor-pointer group">
                                <input id="remember" name="remember" type="checkbox" wire:model.defer="remember"
                                    class="w-5 h-5 text-[#4f63cb] bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#4f63cb] transition">
                                <span class="text-sm text-slate-700 group-hover:text-slate-900">Keep me signed in
                                    on this device</span>
                            </label>

                            <button tabindex="3" type="submit" wire:loading.class="opacity-70 cursor-not-allowed"
                                class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] transition-all duration-200 text-base flex items-center justify-center gap-2">
                                <svg wire:loading wire:target="login" class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Sign In to Dashboard</span>
                            </button>
                        </form>

                        <!-- OTP Verification Form -->
                        <form x-show="showVerify" wire:submit.prevent="verifyOtp"
                            x-on:submit="if (resendInterval) clearInterval(resendInterval)" class="space-y-6"
                            autocomplete="off" x-cloak>
                            <div>
                                <label for="otp"
                                    class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">One-Time
                                    Password</label>
                                <input id="otp" name="otp" type="text" wire:model.defer="otp"
                                    maxlength="6" required placeholder="000000"
                                    class="w-full px-4 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-center font-black text-2xl text-slate-900 placeholder:text-slate-300 shadow-sm hover:border-slate-300 tracking-[0.35em]">
                            </div>

                            <div class="flex flex-col gap-4">
                                <button type="submit" wire:loading.class="opacity-70 cursor-not-allowed"
                                    wire:target="verifyOtp"
                                    class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] transition-all duration-200 text-base flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="verifyOtp" class="animate-spin h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span>Verify & Login</span>
                                </button>

                                <template x-if="resendTimer > 0">
                                    <div class="flex items-center justify-center">
                                        <span
                                            class="inline-flex items-center px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                            Resend OTP in <span class="ml-2 font-black" x-text="resendTimer"></span>s
                                        </span>
                                    </div>
                                </template>

                                <template x-if="resendTimer <= 0">
                                    <button type="button" wire:click="resendOtp"
                                        class="text-sm font-semibold text-[#4f63cb] hover:text-[#3347ac] transition text-center py-2">
                                        Didn't receive? Resend OTP
                                    </button>
                                </template>
                            </div>
                        </form>
                    </div>

                    <!-- Signup Link -->
                    <div class="mt-8 pt-6 border-t border-slate-200 text-center text-sm text-slate-600">
                        New to MakeMyPayment?
                        <a href="{{ route('register', [], false) }}"
                            class="font-bold text-[#4f63cb] hover:text-[#3347ac] transition">Create Account</a>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 flex items-center justify-center gap-3 text-xs text-slate-500">
                    <a href="#" class="hover:text-slate-700 transition font-medium">Terms</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="hover:text-slate-700 transition font-medium">Privacy</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="hover:text-slate-700 transition font-medium">Support</a>
                </div>
            </div>
        </div>
    </div>
</div>
