<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto">
        <!-- Logo -->
        <div class="flex items-center justify-start mb-8">
            <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" class="h-24 w-auto">
        </div>
        <!-- Header -->
        <div class="mb-8" x-data="{ showVerify: @entangle('showVerify') }" x-show="!showVerify">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome to Make My Payment!</h2>
            <p class="text-gray-500">Enter your login details to access your dashboard</p>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <!-- Login Form -->
        <div x-data="{ showVerify: @entangle('showVerify'), showPassword: false, resendTimer: @entangle('resendTimer'), resendInterval: null }"
             x-init="
                $watch('showVerify', value => {
                    if(value) startTimer();
                });
                function startTimer() {
                    if(resendInterval) clearInterval(resendInterval);
                    if(resendTimer > 0) {
                        resendInterval = setInterval(() => {
                            if(resendTimer > 0) { resendTimer--; }
                            if(resendTimer <= 0 && resendInterval) clearInterval(resendInterval);
                        }, 1000);
                    }
                }
                if(showVerify) startTimer();
             ">
            <form x-show="!showVerify" wire:submit.prevent="login" class="space-y-6" autocomplete="off">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" wire:model.defer="email" required autofocus placeholder="Enter the email"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" wire:model.defer="password" required placeholder="Enter the password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer select-none"
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
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" wire:model.defer="remember"
                            class="h-4 w-4 text-[#1563ff] focus:ring-[#1563ff] border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request', [], false) }}"
                        class="text-sm text-[#1563ff] hover:underline transition">
                        Forgot password?
                    </a>
                </div>
                <div>
                    <button type="submit"
                        wire:loading.class="disabled cursor-not-allowed"
                        class="w-full py-3 px-4 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="login" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Login</span>
                    </button>
                </div>
            </form>
            <!-- OTP Verification Form -->
            <form x-show="showVerify" wire:submit.prevent="verifyOtp" x-on:submit="if (resendInterval) clearInterval(resendInterval)" class="space-y-6" autocomplete="off">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">OTP Verification</h3>
                    <p class="text-gray-500">Enter the OTP sent to your registered mobile number.</p>
                </div>
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">OTP</label>
                    <input id="otp" name="otp" type="text" wire:model.defer="otp" maxlength="6" required placeholder="Enter OTP"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base tracking-widest text-center font-bold text-lg">
                </div>
                <div class="flex items-center justify-between">
                    <template x-if="resendTimer > 0">
                        <span class="text-sm text-gray-400 select-none">Resend OTP in <span x-text="resendTimer"></span>s</span>
                    </template>
                    <template x-if="resendTimer <= 0">
                        <button type="button" wire:click="resendOtp" class="text-sm text-[#1563ff] hover:underline transition">Resend OTP</button>
                    </template>
                    <button type="submit"
                        wire:loading.class="disabled cursor-not-allowed"
                        wire:target="verifyOtp"
                        class="py-2 px-6 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="verifyOtp" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verify OTP
                    </button>
                </div>
            </form>
        </div>
        <!-- Register Link -->
        <div class="mt-6 text-center">
            <span class="text-sm text-gray-500">Don't have an account?</span>
            <a href="{{ route('register', [], false) }}" class="text-sm text-[#1563ff] font-medium hover:underline ml-1">Register</a>
        </div>
        
        <!-- Footer Links -->
        <div class="mt-16 text-center text-xs text-gray-400">
            <a href="#" class="hover:underline">Terms and Conditions</a>
            <span class="mx-2">|</span>
            <a href="#" class="hover:underline">Privacy Policy</a>
        </div>
    </div>
</div>
