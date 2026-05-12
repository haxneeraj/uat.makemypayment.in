<div class="max-w-md w-full mx-auto">
    <!-- Logo -->
    <div class="flex items-center mb-10">
        <img src="{{ asset('makemypayment-logo.svg') }}" alt="Flipopay" class="h-24 w-auto">
    </div>
    <!-- Welcome -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Forgot your password?</h2>
        <p class="text-gray-500">Enter your email and we'll send you a reset link.</p>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    <!-- Login Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" required autofocus placeholder="Enter the email"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#1563ff] focus:border-transparent transition text-base">
        </div>
        <div>
            <button
                type="submit"
                x-data="{ loading: false }"
                x-on:click="
                    loading = true;
                    $el.form.submit();
                    $el.disabled = true;
                    $el.classList.add('cursor-not-allowed', 'opacity-60');
                "
                class="w-full py-3 px-4 bg-[#1563ff] hover:bg-[#1746a2] text-white font-semibold rounded-lg shadow transition text-base flex items-center justify-center gap-2">
                <svg x-show="loading" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Email Password Reset Link
            </button>
        </div>
    </form>
    <!-- Google Login -->
    <div class="mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-[#1563ff]">Login Here</a>
    </div>
    <!-- Footer Links -->
    <div class="mt-16 text-center text-xs text-gray-400">
        <a href="#" class="hover:underline">Terms and Conditions</a>
        <span class="mx-2">|</span>
        <a href="#" class="hover:underline">Privacy Policy</a>
    </div>
</div>
