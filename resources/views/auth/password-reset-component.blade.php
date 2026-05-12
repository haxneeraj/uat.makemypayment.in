<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md mx-auto p-8 md:p-10">
        <!-- Logo -->
        <div class="flex items-center mb-8">
            <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" class="h-24 w-auto">
        </div>
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Reset your password</h2>
            <p class="text-gray-500">Enter your new password below to reset your account password.</p>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6" x-data="{ showPassword: false, showPasswordConfirm: false }">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" name="email" type="email" required placeholder="Enter your email"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base"
                    value="{{ old('email', $email ?? request()->email) }}" readonly>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autofocus placeholder="New password"
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
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required placeholder="Confirm new password"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer select-none"
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
            </div>
            <div>
                <button type="submit"
                    class="w-full py-3 px-4 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base">
                    Reset Password
                </button>
            </div>
        </form>
        <div class="mt-6 text-center text-sm">
            Remember your password?
            <a href="{{ route('login') }}" class="text-[#1563ff] font-semibold hover:underline">Login here</a>
        </div>
        <!-- Footer Links -->
        <div class="mt-12 text-center text-xs text-gray-400">
            <a href="#" class="hover:underline">Terms and Conditions</a>
            <span class="mx-2">|</span>
            <a href="#" class="hover:underline">Privacy Policy</a>
        </div>
    </div>
</div>
