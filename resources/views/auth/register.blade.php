<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fbff] via-[#f0f5ff] to-[#e8f0ff] flex items-center justify-center px-4 py-8">
    <!-- Decorative Gradient Blobs -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-[#eef2ff]/40 to-[#dfe8ff]/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-[#6c3ec5]/10 to-[#a855f7]/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-gradient-to-bl from-[#4f63cb]/5 to-transparent rounded-full blur-3xl"></div>

    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
        <!-- Left: Branding & Info Section -->
        <div class="hidden lg:flex flex-col h-full py-12">
            <div class="mb-8">
                <div class="mb-8">
                    <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" class="h-32 w-auto">
                    <h1 class="text-5xl font-black text-slate-900 leading-tight mb-4">
                        Join <span class="bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] bg-clip-text text-transparent">1000+</span> Growing Merchants
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-md">
                        Start processing payouts instantly. No setup fees, transparent pricing, and dedicated support.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-[#2b3990] to-[#4a63ca]">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Zero Hidden Fees</h3>
                            <p class="text-sm text-slate-600 mt-1">Transparent pricing with no setup or hidden charges</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Fast Settlements</h3>
                            <p class="text-sm text-slate-600 mt-1">Get funds in real-time via IMPS/NEFT</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-[#4f63cb] to-[#6c3ec5]">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7.5-3.5a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Enterprise Security</h3>
                            <p class="text-sm text-slate-600 mt-1">Bank-grade encryption & compliance ready</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-xs text-slate-500">
                <p>✓ Trusted by merchants across India</p>
                <p>✓ Processing ₹50M+ daily volume</p>
                <p>✓ 24/7 dedicated support team</p>
            </div>
        </div>

        <!-- Right: Registration Card Section -->
        <div class="flex flex-col items-center">
            <div class="w-full">
                <!-- Main Registration Card -->
                <div class="rounded-3xl border border-white/60 bg-white/95 shadow-[0_20px_60px_rgba(43,57,144,0.15)] backdrop-blur-xl p-8" x-data="{ step: @entangle('step') }">
                    
                    <!-- Step 1 Header -->
                    <div x-show="step === 1" x-cloak>
                        <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-[#eef2ff] to-[#eef9f1] border border-[#dbe3ff] mb-6">
                            <span class="text-[11px] font-semibold uppercase tracking-[0.16em] bg-gradient-to-r from-[#2b3990] to-emerald-600 bg-clip-text text-transparent">🚀 Get Started</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Create Account</h2>
                        <p class="text-slate-600 mb-8">Start processing payouts in minutes</p>
                    </div>

                    <!-- Step 2 Header -->
                    <div x-show="step === 2" x-cloak>
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#4f63cb] to-[#6c3ec5] mb-6">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Verify Email</h2>
                        <p class="text-slate-600 mb-8">Confirm your email address</p>
                    </div>

                    <!-- Step 3 Header -->
                    <div x-show="step === 3" x-cloak>
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 mb-6">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Verify Mobile</h2>
                        <p class="text-slate-600 mb-8">Confirm your phone number</p>
                    </div>

                    <!-- Session Status & Validation Errors -->
                    <x-auth-session-status class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />
                    <x-auth-validation-errors class="mb-4 rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm text-rose-700" :errors="$errors" />

                    <!-- Forms Container -->
                    <div x-data="{ showVerify: @entangle('step'), showPassword: false, showPasswordConfirm: false, resendTimer: @entangle('resendTimer'), resendInterval: null, passwordValue: @entangle('password').defer, passwordConfirmValue: @entangle('password_confirmation').defer, passwordFocused: false, passwordConfirmFocused: false, 
                        startTimer() {
                            if(this.resendInterval) clearInterval(this.resendInterval);
                            if(this.resendTimer > 0) {
                                this.resendInterval = setInterval(() => {
                                    if(this.resendTimer > 0) { this.resendTimer--; $wire.set('resendTimer', this.resendTimer); }
                                    if(this.resendTimer <= 0 && this.resendInterval) clearInterval(this.resendInterval);
                                }, 1000);
                            }
                        },
                        passwordChecks() {
                            return {
                                length: this.passwordValue.length >= 8,
                                lowercase: /[a-z]/.test(this.passwordValue),
                                uppercase: /[A-Z]/.test(this.passwordValue),
                                number: /[0-9]/.test(this.passwordValue),
                                symbol: /[^A-Za-z0-9]/.test(this.passwordValue),
                            };
                        },
                        isPasswordStrong() {
                            return Object.values(this.passwordChecks()).every(Boolean);
                        },
                        passwordsMatch() {
                            return this.passwordConfirmValue.length > 0 && this.passwordValue === this.passwordConfirmValue;
                        },
                        showPasswordHelper() {
                            return this.passwordFocused || this.passwordValue.length > 0;
                        },
                        showPasswordConfirmHelper() {
                            return this.passwordConfirmFocused || this.passwordConfirmValue.length > 0;
                        }
                    }"
                    x-init="$watch('showVerify', value => { if(value === 2 || value === 3) startTimer(); }); if(showVerify === 2 || showVerify === 3) startTimer();">

                        <!-- Step 1: Registration Form -->
                        <form x-show="step === 1" wire:submit.prevent="submitRegistration" class="space-y-5" autocomplete="off" x-cloak>
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">First Name <span class="text-rose-500">*</span></label>
                                    <input id="first_name" name="first_name" type="text" wire:model.defer="first_name" required placeholder="John"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">                                    
                                </div>
                                <div>
                                    <label for="last_name" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Last Name <span class="text-rose-500">*</span></label>
                                    <input id="last_name" name="last_name" type="text" wire:model.defer="last_name" required placeholder="Doe"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">                                    
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="phone" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Phone <span class="text-rose-500">*</span></label>
                                    <input id="phone" name="phone" type="text" wire:model.live.debounce.500ms="phone" required placeholder="98XXXXXXXXXX"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">                                    
                                </div>

                                <div>
                                    <label for="email" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Email <span class="text-rose-500">*</span></label>
                                    <input id="email" name="email" type="email" wire:model.live.debounce.500ms="email" required placeholder="merchant@company.com"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">                                    
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Password <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" id="password" wire:model.defer="password" x-model="passwordValue" @focus="passwordFocused = true" @blur="passwordFocused = false" minlength="8" autocomplete="new-password" required placeholder="Enter strong password"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base pr-11 text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <template x-if="!showPassword">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>
                                        <template x-if="showPassword">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m2.59-2.41A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                                <div x-show="showPasswordHelper()" x-cloak class="mt-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600 mb-2">Password requirements</p>
                                    <div class="space-y-1.5 text-xs">
                                        <div class="flex items-center gap-2" :class="passwordChecks().length ? 'text-emerald-600' : 'text-slate-500'">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().length ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200'">✓</span>
                                            <span>At least 8 characters</span>
                                        </div>
                                        <div class="flex items-center gap-2" :class="passwordChecks().lowercase ? 'text-emerald-600' : 'text-slate-500'">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().lowercase ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200'">✓</span>
                                            <span>One lowercase (a-z)</span>
                                        </div>
                                        <div class="flex items-center gap-2" :class="passwordChecks().uppercase ? 'text-emerald-600' : 'text-slate-500'">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().uppercase ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200'">✓</span>
                                            <span>One uppercase (A-Z)</span>
                                        </div>
                                        <div class="flex items-center gap-2" :class="passwordChecks().number ? 'text-emerald-600' : 'text-slate-500'">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().number ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200'">✓</span>
                                            <span>One number (0-9)</span>
                                        </div>
                                        <div class="flex items-center gap-2" :class="passwordChecks().symbol ? 'text-emerald-600' : 'text-slate-500'">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().symbol ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200'">✓</span>
                                            <span>One special char (!@#$)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Confirm Password <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" wire:model.defer="password_confirmation" x-model="passwordConfirmValue" @focus="passwordConfirmFocused = true" @blur="passwordConfirmFocused = false" minlength="8" autocomplete="new-password" required placeholder="Confirm password"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base pr-11 text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">
                                    <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <template x-if="!showPasswordConfirm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>
                                        <template x-if="showPasswordConfirm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m2.59-2.41A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                                <p x-show="showPasswordConfirmHelper() && passwordConfirmValue.length > 0" x-cloak class="mt-2 flex items-center gap-2 text-xs font-medium" :class="passwordsMatch() ? 'text-emerald-600' : 'text-amber-600'">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full text-xs font-bold" :class="passwordsMatch() ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'" x-text="passwordsMatch() ? '✓' : '!'"></span>
                                    <span x-text="passwordsMatch() ? 'Passwords match' : 'Passwords do not match'"></span>
                                </p>
                            </div>

                            <button type="submit"
                                :disabled="!isPasswordStrong() || !passwordsMatch()"
                                wire:loading.class="opacity-70 cursor-not-allowed"
                                class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] disabled:from-slate-300 disabled:via-slate-300 disabled:to-slate-300 disabled:cursor-not-allowed text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] disabled:shadow-none transition-all duration-200 text-base flex items-center justify-center gap-2">
                                <svg wire:loading wire:target="submitRegistration" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Create Account</span>
                            </button>
                        </form>

                        <!-- Step 2: Email OTP Verification -->
                        <form x-show="step === 2" wire:submit.prevent="verifyEmailOtp" class="space-y-6" autocomplete="off" x-cloak>
                            <div>
                                <label for="email_otp" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Email OTP</label>
                                <input id="email_otp" name="email_otp" type="text" wire:model.defer="email_otp" maxlength="6" required placeholder="000000"
                                    class="w-full px-4 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-center font-black text-2xl text-slate-900 placeholder:text-slate-300 shadow-sm hover:border-slate-300 tracking-[0.35em]">
                                @if($errors->has('email_otp'))
                                    <p class="mt-2 flex items-start gap-2 text-xs font-medium text-rose-600">
                                        <svg class="mt-0.5 h-3 w-3 flex-none" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        <span>{{ $errors->first('email_otp') }}</span>
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col gap-4">
                                <button type="submit"
                                    wire:loading.class="opacity-70 cursor-not-allowed"
                                    wire:target="verifyEmailOtp"
                                    class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] transition-all duration-200 text-base flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="verifyEmailOtp" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Verify Email</span>
                                </button>

                                <template x-if="resendTimer > 0">
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                            Resend in <span class="ml-2 font-black" x-text="resendTimer"></span>s
                                        </span>
                                    </div>
                                </template>

                                <template x-if="resendTimer <= 0">
                                    <button type="button" wire:click="resendEmailOtp" class="text-sm font-semibold text-[#4f63cb] hover:text-[#3347ac] transition text-center py-2">
                                        Didn't receive? Resend OTP
                                    </button>
                                </template>
                            </div>
                        </form>

                        <!-- Step 3: Mobile OTP Verification -->
                        <form x-show="step === 3" wire:submit.prevent="verifyMobileOtp" class="space-y-6" autocomplete="off" x-cloak>
                            <div>
                                <label for="mobile_otp" class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Mobile OTP</label>
                                <input id="mobile_otp" name="mobile_otp" type="text" wire:model.defer="mobile_otp" maxlength="6" required placeholder="000000"
                                    class="w-full px-4 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-center font-black text-2xl text-slate-900 placeholder:text-slate-300 shadow-sm hover:border-slate-300 tracking-[0.35em]">
                            </div>

                            <div class="flex flex-col gap-4">
                                <button type="submit"
                                    wire:loading.class="opacity-70 cursor-not-allowed"
                                    wire:target="verifyMobileOtp"
                                    class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] transition-all duration-200 text-base flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="verifyMobileOtp" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Verify Mobile</span>
                                </button>

                                <template x-if="resendTimer > 0">
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                            Resend in <span class="ml-2 font-black" x-text="resendTimer"></span>s
                                        </span>
                                    </div>
                                </template>

                                <template x-if="resendTimer <= 0">
                                    <button type="button" wire:click="resendMobileOtp" class="text-sm font-semibold text-[#4f63cb] hover:text-[#3347ac] transition text-center py-2">
                                        Didn't receive? Resend OTP
                                    </button>
                                </template>
                            </div>
                        </form>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-8 pt-6 border-t border-slate-200 text-center text-sm text-slate-600">
                        Already have an account?
                        <a href="{{ route('login', [], false) }}" class="font-bold text-[#4f63cb] hover:text-[#3347ac] transition">Sign In</a>
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
