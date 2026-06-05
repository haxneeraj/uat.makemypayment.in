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
                        Recover Access.
                        <span
                            class="bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] bg-clip-text text-transparent">Stay Secure.</span>
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-md">
                        Reset your password in minutes and get back to managing payouts with confidence.
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
                                        d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v2h1a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2h1v-2a6 6 0 1112 0" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Secure Recovery</h3>
                            <p class="text-sm text-slate-600 mt-1">Password reset links are encrypted and time-bound
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Quick Access Restore</h3>
                            <p class="text-sm text-slate-600 mt-1">Receive reset instructions instantly on your email
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
                                        d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Always Available</h3>
                            <p class="text-sm text-slate-600 mt-1">Recover your account anytime without support delays
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-xs text-slate-500 mt-8">
                <p>✓ Enterprise-grade account security controls</p>
                <p>✓ Trusted by 1000+ merchants nationwide</p>
                <p>✓ Fast and frictionless password recovery</p>
            </div>
        </div>

        <!-- Right: Forgot Password Card Section -->
        <div class="flex flex-col items-center">
            <div class="w-full max-w-md">
                <div class="rounded-3xl border border-white/60 bg-white/95 shadow-[0_20px_60px_rgba(43,57,144,0.15)] backdrop-blur-xl p-8"
                    x-data="{ loading: false }">

                    <div>
                        <div
                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-[#eef2ff] to-[#eef9f1] border border-[#dbe3ff] mb-6">
                            <span
                                class="text-[11px] font-semibold uppercase tracking-[0.16em] bg-gradient-to-r from-[#2b3990] to-emerald-600 bg-clip-text text-transparent">Account
                                Recovery</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Forgot Password?</h2>
                        <p class="text-slate-600 mb-8">Enter your email and we will send a secure reset link.</p>
                    </div>

                    <!-- Session Status & Validation Errors -->
                    <x-auth-session-status
                        class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-700"
                        :status="session('status')" />
                    <x-auth-validation-errors
                        class="mb-4 rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm text-rose-700"
                        :errors="$errors" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" autocomplete="off"
                        x-on:submit="loading = true">
                        @csrf

                        <div>
                            <label for="email"
                                class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-700 mb-2.5">Email
                                Address</label>
                            <input id="email" name="email" type="email" required autofocus
                                placeholder="merchant@company.com"
                                class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-2 focus:ring-[#4f63cb] focus:border-transparent focus:bg-white transition-all text-base text-slate-900 placeholder:text-slate-400 shadow-sm hover:border-slate-300">
                        </div>

                        <button type="submit" :disabled="loading"
                            class="w-full py-4 px-4 bg-gradient-to-r from-[#2b3990] via-[#4f63cb] to-[#6c3ec5] hover:from-[#263c9f] hover:via-[#4255b8] hover:to-[#5a33a8] disabled:opacity-70 disabled:cursor-not-allowed text-white font-bold rounded-2xl shadow-lg shadow-[rgba(67,90,190,0.35)] hover:shadow-xl hover:shadow-[rgba(67,90,190,0.45)] transition-all duration-200 text-base flex items-center justify-center gap-2">
                            <svg x-show="loading" x-cloak class="animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="loading ? 'Sending Reset Link...' : 'Email Password Reset Link'"></span>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-8 pt-6 border-t border-slate-200 text-center text-sm text-slate-600">
                        Remembered your password?
                        <a href="{{ route('login', [], false) }}"
                            class="font-bold text-[#4f63cb] hover:text-[#3347ac] transition">Sign In</a>
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
