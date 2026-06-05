<footer class="relative overflow-hidden bg-[#070b18] text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_12%,rgba(168,85,247,0.28),transparent_34%),radial-gradient(circle_at_70%_26%,rgba(59,130,246,0.24),transparent_36%),radial-gradient(circle_at_90%_92%,rgba(16,185,129,0.24),transparent_30%)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: linear-gradient(to right, rgba(255,255,255,0.10) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.10) 1px, transparent 1px); background-size: 44px 44px;"></div>

    <div class="relative z-10 px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl text-center">
            <h2 class="text-3xl font-black leading-tight text-white sm:text-5xl">Scale Your Payout Operations with Confidence</h2>
            <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-indigo-100 sm:text-lg">
                MakeMyPayment gives your business one reliable platform for collections, API payouts, and real-time transaction visibility.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="/register" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 via-cyan-500 to-emerald-500 px-7 py-3 text-sm font-semibold text-white transition hover:brightness-110 sm:text-base">Open Business Account</a>
                <a href="{{ route('site.contact') }}" class="inline-flex items-center justify-center rounded-full border border-white/30 bg-white/10 px-7 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20 sm:text-base">Talk To Sales</a>
            </div>
        </div>

        <div class="mx-auto mt-16 max-w-7xl border-t border-white/20 pt-10">
            <div class="grid gap-8 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-4">
                    <img src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment Payment Gateway Logo" class="mb-4 h-16 w-auto brightness-0 invert">
                    <p class="max-w-sm text-sm leading-7 text-indigo-100/90">
                        MakeMyPayment helps businesses run collections, payouts, and reconciliation with operational clarity and secure transaction controls.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="rounded-full border border-indigo-300/35 bg-white/10 px-3 py-1 text-xs font-semibold text-indigo-100">Payout API</span>
                        <span class="rounded-full border border-cyan-300/35 bg-white/10 px-3 py-1 text-xs font-semibold text-cyan-100">Real-time Status</span>
                        <span class="rounded-full border border-emerald-300/35 bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-100">Secure Operations</span>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-indigo-100/90">Company</h3>
                    <nav class="space-y-2.5 text-sm">
                        <a href="{{ route('site.about') }}" class="block text-indigo-100/85 transition hover:text-white">About Us</a>
                        <a href="{{ route('site.service') }}" class="block text-indigo-100/85 transition hover:text-white">Services</a>
                        <a href="{{ route('site.contact') }}" class="block text-indigo-100/85 transition hover:text-white">Contact</a>
                    </nav>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-indigo-100/90">Legal</h3>
                    <nav class="space-y-2.5 text-sm">
                        <a href="{{ route('site.terms') }}" class="block text-indigo-100/85 transition hover:text-white">Terms</a>
                        <a href="{{ route('site.privacy') }}" class="block text-indigo-100/85 transition hover:text-white">Privacy</a>
                        <a href="{{ route('site.refund') }}" class="block text-indigo-100/85 transition hover:text-white">Refund Policy</a>
                    </nav>
                </div>

                <div class="lg:col-span-4">
                    <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-indigo-100/90">Support</h3>
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm">
                        <a href="mailto:support@makemypayment.in" class="block text-sm font-semibold text-white hover:text-cyan-200">support@makemypayment.in</a>
                        <a href="tel:+916354409951" class="mt-1 block text-sm font-semibold text-white hover:text-cyan-200">+91 6354409951</a>
                        <p class="mt-2 text-xs text-indigo-100/80">Mon to Sat, 10:00 AM to 7:00 PM</p>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <div class="flex h-9 w-12 items-center justify-center rounded-lg border border-white/20 bg-white/95">
                            <img src="{{ asset('assets/images/visa-logo.svg') }}" alt="Visa Accepted by MakeMyPayment" class="h-6 w-auto">
                        </div>
                        <div class="flex h-9 w-12 items-center justify-center rounded-lg border border-white/20 bg-white/95">
                            <img src="{{ asset('assets/images/master-card.png') }}" alt="Mastercard Accepted by MakeMyPayment" class="h-6 w-auto">
                        </div>
                        <div class="flex h-9 w-12 items-center justify-center rounded-lg border border-white/20 bg-white/95">
                            <img src="{{ asset('assets/images/rupay-logo.png') }}" alt="RuPay Supported by MakeMyPayment" class="h-6 w-auto">
                        </div>
                        <div class="flex h-9 w-12 items-center justify-center rounded-lg border border-white/20 bg-white/95">
                            <img src="{{ asset('assets/images/upi-logo.png') }}" alt="UPI Payments with MakeMyPayment" class="h-6 w-auto">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-white/20 pt-6 text-xs text-indigo-100/85 sm:flex-row">
                <p>&copy; {{ date('Y') }} MakeMyPayment. All rights reserved.</p>
                <p>Built for secure merchant payment operations.</p>
            </div>
        </div>
    </div>
</footer>
