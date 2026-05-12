<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto">
        <!-- Logo -->
        <div class="flex items-center justify-start mb-8">
            <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" class="h-24 w-auto">
        </div>
        <!-- Header -->
        <div class="mb-8" x-data="{ step: @entangle('step') }" x-show="step === 1">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Create your account</h2>
            <p class="text-gray-500">Fill in your details to get started</p>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div x-data="{
                step: @entangle('step'),
                showPassword: false,
                showPasswordConfirm: false,
                passwordValue: @entangle('password').defer,
                passwordConfirmValue: @entangle('password_confirmation').defer,
                passwordFocused: false,
                passwordConfirmFocused: false,
                resendTimer: @entangle('resendTimer'),
                resendInterval: null,
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
            x-init="$watch('step', value => { if(value === 2 || value === 3) startTimer(); }); if(step === 2 || step === 3) startTimer();"
        >
            <!-- Step 1: Registration Form -->
            <form x-show="step === 1" wire:submit.prevent="submitRegistration" class="space-y-6" autocomplete="off">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="mb-1 block text-sm font-medium {{ $errors->has('first_name') ? 'text-red-600' : 'text-gray-700' }}">First Name <small class="text-red-500">*</small></label>
                        <input id="first_name" name="first_name" type="text" wire:model.defer="first_name" required placeholder="First Name"
                            aria-invalid="{{ $errors->has('first_name') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-base transition placeholder:text-gray-400 {{ $errors->has('first_name') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                        @if($errors->has('first_name'))
                            <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                                <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $errors->first('first_name') }}</span>
                            </p>
                        @endif
                    </div>
                    <div>
                        <label for="last_name" class="mb-1 block text-sm font-medium {{ $errors->has('last_name') ? 'text-red-600' : 'text-gray-700' }}">Last Name <small class="text-red-500">*</small></label>
                        <input id="last_name" name="last_name" type="text" wire:model.defer="last_name" required placeholder="Last Name"
                            aria-invalid="{{ $errors->has('last_name') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-base transition placeholder:text-gray-400 {{ $errors->has('last_name') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                        @if($errors->has('last_name'))
                            <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                                <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $errors->first('last_name') }}</span>
                            </p>
                        @endif
                    </div>
                </div>
                <div>
                    <label for="phone" class="mb-1 block text-sm font-medium {{ $errors->has('phone') ? 'text-red-600' : 'text-gray-700' }}">Phone <small class="text-red-500">*</small></label>
                    <input id="phone" name="phone" type="text" wire:model.live.debounce.500ms="phone" required placeholder="Mobile Number"
                        aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                        class="w-full rounded-xl border bg-white px-4 py-3 text-base transition placeholder:text-gray-400 {{ $errors->has('phone') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                    @if($errors->has('phone'))
                        <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                            <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first('phone') }}</span>
                        </p>
                    @endif
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium {{ $errors->has('email') ? 'text-red-600' : 'text-gray-700' }}">Email <small class="text-red-500">*</small></label>
                    <input id="email" name="email" type="email" wire:model.live.debounce.500ms="email" required placeholder="Email"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        class="w-full rounded-xl border bg-white px-4 py-3 text-base transition placeholder:text-gray-400 {{ $errors->has('email') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                    @if($errors->has('email'))
                        <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                            <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first('email') }}</span>
                        </p>
                    @endif
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium {{ $errors->has('password') ? 'text-red-600' : 'text-gray-700' }}">Password <small class="text-red-500">*</small></label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" wire:model.defer="password" x-model="passwordValue" @focus="passwordFocused = true" @blur="passwordFocused = false" minlength="8" autocomplete="new-password" required placeholder="Password"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-white px-4 py-3 pr-10 text-base transition placeholder:text-gray-400 {{ $errors->has('password') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                        <span class="absolute inset-y-0 right-3 flex items-center {{ $errors->has('password') ? 'text-red-400' : 'text-gray-400' }} cursor-pointer select-none"
                              @click="showPassword = !showPassword">
                            <template x-if="!showPassword">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </template>
                            <template x-if="showPassword">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m2.59-2.41A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3l18 18" />
                                </svg>
                            </template>
                        </span>
                    </div>
                    <div x-show="showPasswordHelper()" x-cloak class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Password requirements</p>
                        <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2 text-sm">
                            <div class="flex items-center gap-2" :class="passwordChecks().length ? 'text-emerald-600' : 'text-gray-500'">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().length ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500'">&#10003;</span>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="flex items-center gap-2" :class="passwordChecks().lowercase ? 'text-emerald-600' : 'text-gray-500'">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().lowercase ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500'">&#10003;</span>
                                <span>One lowercase letter</span>
                            </div>
                            <div class="flex items-center gap-2" :class="passwordChecks().uppercase ? 'text-emerald-600' : 'text-gray-500'">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().uppercase ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500'">&#10003;</span>
                                <span>One uppercase letter</span>
                            </div>
                            <div class="flex items-center gap-2" :class="passwordChecks().number ? 'text-emerald-600' : 'text-gray-500'">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().number ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500'">&#10003;</span>
                                <span>One number</span>
                            </div>
                            <div class="flex items-center gap-2 sm:col-span-2" :class="passwordChecks().symbol ? 'text-emerald-600' : 'text-gray-500'">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordChecks().symbol ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500'">&#10003;</span>
                                <span>One special character</span>
                            </div>
                        </div>
                    </div>
                    @if($errors->has('password'))
                        <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                            <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first('password') }}</span>
                        </p>
                    @endif
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium {{ $errors->has('password_confirmation') ? 'text-red-600' : 'text-gray-700' }}">Confirm Password <small class="text-red-500">*</small></label>
                    <div class="relative">
                        <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" wire:model.defer="password_confirmation" x-model="passwordConfirmValue" @focus="passwordConfirmFocused = true" @blur="passwordConfirmFocused = false" minlength="8" autocomplete="new-password" required placeholder="Confirm Password"
                            aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-white px-4 py-3 pr-10 text-base transition placeholder:text-gray-400 {{ $errors->has('password_confirmation') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                        <span class="absolute inset-y-0 right-3 flex items-center {{ $errors->has('password_confirmation') ? 'text-red-400' : 'text-gray-400' }} cursor-pointer select-none"
                              @click="showPasswordConfirm = !showPasswordConfirm">
                            <template x-if="!showPasswordConfirm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </template>
                            <template x-if="showPasswordConfirm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m2.59-2.41A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3l18 18" />
                                </svg>
                            </template>
                        </span>
                    </div>
                    <p x-show="showPasswordConfirmHelper() && passwordConfirmValue.length > 0" x-cloak class="mt-2 flex items-center gap-2 text-sm font-medium" :class="passwordsMatch() ? 'text-emerald-600' : 'text-amber-600'">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold" :class="passwordsMatch() ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'" x-text="passwordsMatch() ? '✓' : '!' "></span>
                        <span x-text="passwordsMatch() ? 'Passwords match' : 'Passwords must match exactly'"></span>
                    </p>
                    @if($errors->has('password_confirmation'))
                        <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                            <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first('password_confirmation') }}</span>
                        </p>
                    @endif
                </div>
                <div>
                    <button type="submit"
                        :disabled="!isPasswordStrong() || !passwordsMatch()"
                        wire:loading.class="disabled cursor-not-allowed"
                        class="w-full py-3 px-4 bg-[#1563ff] hover:bg-[#1746a2] disabled:bg-[#1563ff]/60 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="submitRegistration" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Register</span>
                    </button>
                </div>

                <!-- Already have account? Login link -->
                <div class="mt-6 text-center">
                    <span class="text-sm text-gray-500">Already have an account?</span>
                    <a href="{{ route('login', [], false) }}" class="text-sm text-[#1563ff] font-medium hover:underline ml-1">Login</a>
                </div>
            </form>

            <!-- Step 2: Email OTP Verification -->
            <form x-show="step === 2" wire:submit.prevent="verifyEmailOtp" class="space-y-6" autocomplete="off">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Email Verification</h3>
                    <p class="text-gray-500">Enter the OTP sent to your email address.</p>
                </div>
                <div>
                    <label for="email_otp" class="mb-1 block text-sm font-medium {{ $errors->has('email_otp') ? 'text-red-600' : 'text-gray-700' }}">Email OTP</label>
                    <input id="email_otp" name="email_otp" type="text" wire:model.defer="email_otp" maxlength="6" required placeholder="Enter Email OTP"
                        aria-invalid="{{ $errors->has('email_otp') ? 'true' : 'false' }}"
                        class="w-full rounded-xl border bg-white px-4 py-3 text-base tracking-widest text-center font-bold text-lg transition {{ $errors->has('email_otp') ? 'border-red-400 text-gray-900 focus:border-red-500 focus:ring-4 focus:ring-red-100 focus:outline-none' : 'border-gray-200 text-gray-900 focus:border-[#1563ff] focus:ring-4 focus:ring-[#1563ff]/10 focus:outline-none' }}">
                    @if($errors->has('email_otp'))
                        <p class="mt-2 flex items-start gap-2 text-sm font-medium text-red-600">
                            <svg class="mt-0.5 h-4 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zm.75 7.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first('email_otp') }}</span>
                        </p>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <template x-if="resendTimer > 0">
                        <span class="text-sm text-gray-400 select-none">Resend OTP in <span x-text="resendTimer"></span>s</span>
                    </template>
                    <template x-if="resendTimer <= 0">
                        <button type="button" wire:click="resendEmailOtp" class="text-sm text-[#1563ff] hover:underline transition">Resend OTP</button>
                    </template>
                    <button type="submit"
                        wire:loading.class="disabled cursor-not-allowed"
                        wire:target="verifyEmailOtp"
                        class="py-2 px-6 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="verifyEmailOtp" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verify Email OTP
                    </button>
                </div>
            </form>
            <!-- Step 3: Mobile OTP Verification -->
            <form x-show="step === 3" wire:submit.prevent="verifyMobileOtp" class="space-y-6" autocomplete="off">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Mobile Verification</h3>
                    <p class="text-gray-500">Enter the OTP sent to your mobile number.</p>
                </div>
                <div>
                    <label for="mobile_otp" class="block text-sm font-medium text-gray-700 mb-1">Mobile OTP</label>
                    <input id="mobile_otp" name="mobile_otp" type="text" wire:model.defer="mobile_otp" maxlength="6" required placeholder="Enter Mobile OTP"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base tracking-widest text-center font-bold text-lg">
                </div>
                <div class="flex items-center justify-between">
                    <template x-if="resendTimer > 0">
                        <span class="text-sm text-gray-400 select-none">Resend OTP in <span x-text="resendTimer"></span>s</span>
                    </template>
                    <template x-if="resendTimer <= 0">
                        <button type="button" wire:click="resendMobileOtp" class="text-sm text-[#1563ff] hover:underline transition">Resend OTP</button>
                    </template>
                    <button type="submit"
                        wire:loading.class="disabled cursor-not-allowed"
                        wire:target="verifyMobileOtp"
                        class="py-2 px-6 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="verifyMobileOtp" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verify Mobile OTP
                    </button>
                </div>
            </form>
        </div>
        <!-- Footer Links -->
        <div class="mt-16 text-center text-xs text-gray-400">
            <a href="#" class="hover:underline">Terms and Conditions</a>
            <span class="mx-2">|</span>
            <a href="#" class="hover:underline">Privacy Policy</a>
        </div>
    </div>
</div>
