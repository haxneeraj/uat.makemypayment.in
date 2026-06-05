<section class="relative overflow-hidden border-y border-indigo-100 bg-gradient-to-b from-white via-slate-50 to-indigo-50/40 py-16 sm:py-20">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_12%_8%,rgba(99,102,241,0.12),transparent_30%),radial-gradient(circle_at_88%_12%,rgba(6,182,212,0.10),transparent_28%)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-25" style="background-image: linear-gradient(to right, rgba(148,163,184,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(148,163,184,0.14) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-7 lg:grid-cols-12">
            <div class="lg:col-span-5 rounded-3xl border border-indigo-200/80 bg-gradient-to-br from-indigo-600 via-indigo-600 to-cyan-600 p-6 text-white shadow-[0_24px_60px_rgba(37,99,235,0.28)] sm:p-8">
                <div class="inline-flex rounded-full border border-white/30 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wider uppercase text-white backdrop-blur-sm">
                    Contact
                </div>
                <h2 class="mt-4 text-3xl font-black text-white">Talk To A Payments Specialist</h2>
                <p class="mt-3 text-indigo-100">Get onboarding help, API guidance, payout operations support, and partnership assistance from the MakeMyPayment team.</p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="rounded-full border border-white/35 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-white">API Support</span>
                    <span class="rounded-full border border-white/35 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-white">Fast Onboarding</span>
                    <span class="rounded-full border border-white/35 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-white">24x7 Ops</span>
                </div>

                <div class="mt-7 space-y-3">
                    <div class="rounded-xl border border-white/25 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-wider text-indigo-100">Email</p>
                        <p class="mt-1 text-sm font-semibold text-white">support@makemypayment.in</p>
                    </div>
                    <div class="rounded-xl border border-white/25 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-wider text-indigo-100">Phone</p>
                        <p class="mt-1 text-sm font-semibold text-white">+91 6354409951</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 rounded-3xl border border-indigo-100 bg-white/90 p-6 shadow-[0_24px_70px_rgba(30,41,59,0.08)] backdrop-blur sm:p-8">
                <h3 class="mb-1 text-xl font-black text-slate-900">Send Your Requirement</h3>
                <p class="mb-5 text-sm text-slate-500">Share your business use case and our team will reach out with the right payout solution.</p>
                <form class="space-y-5" wire:submit.prevent="submit">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="business_name">Business Name</label>
                            <input type="text" id="business_name" wire:model.defer="business_name" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30" placeholder="Your business">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="full_name">Full Name</label>
                            <input type="text" id="full_name" wire:model.defer="full_name" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30" placeholder="Your full name">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="email">Email</label>
                            <input type="email" id="email" wire:model.defer="email" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30" placeholder="name@company.com">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="phone">Phone</label>
                            <input type="text" id="phone" wire:model.defer="phone" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30" placeholder="Phone number">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="type">Inquiry Type</label>
                        <select id="type" wire:model.defer="type" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30">
                            <option value="" selected>Select inquiry type</option>
                            <option value="support_request">Support Request</option>
                            <option value="onboarding_request">Onboarding Request</option>
                            <option value="merchant_inquiry">Merchant Inquiry</option>
                            <option value="registration_request">Registration Request</option>
                            <option value="partnership_request">Partnership Request</option>
                            <option value="feedback_suggestion">Feedback/Suggestion</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2" for="message">Message</label>
                        <textarea id="message" wire:model.defer="message" rows="4" required class="w-full rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/30" placeholder="Tell us your requirement"></textarea>
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 py-3 font-semibold text-white transition hover:from-indigo-500 hover:to-cyan-500">
                        Submit Request
                    </button>
                </form>

                <x-auth-session-status class="my-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('success')" />
                <x-auth-validation-errors class="my-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" :errors="$errors" />
            </div>
        </div>
    </div>
</section>
