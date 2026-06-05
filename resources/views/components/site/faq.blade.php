<section class="relative overflow-hidden border-y border-indigo-500/40 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-700 py-16 sm:py-20">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.20),transparent_38%),radial-gradient(circle_at_88%_16%,rgba(34,211,238,0.20),transparent_30%)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-30" style="background-image: linear-gradient(to right, rgba(255,255,255,0.15) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 42px 42px;"></div>
    <div class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-cyan-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 right-[-4rem] h-80 w-80 rounded-full bg-violet-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(115deg,rgba(255,255,255,0.16),transparent_35%,transparent_65%,rgba(255,255,255,0.12))]"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-x-4 -top-6 h-20 rounded-full bg-white/15 blur-2xl sm:inset-x-10"></div>
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex rounded-full border border-white/35 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wider uppercase text-white backdrop-blur-sm">
                MakeMyPayment FAQs
            </div>
            <h2 class="mt-4 text-3xl sm:text-4xl font-black text-white">Fintech Payout Questions, Answered</h2>
            <p class="mt-3 text-indigo-100">Everything you need to know about MakeMyPayment onboarding, API payouts, settlement flow, and support.</p>
        </div>

        <div x-data="{ open: 1 }" class="mt-10 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div :class="open === 1 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 1 ? null : 1" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">How quickly can we go live with MakeMyPayment payouts?</span>
                    <span class="text-lg font-bold" x-text="open === 1 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 1" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">Most businesses go live quickly after KYC and API checks are completed. Our onboarding team helps you move from sandbox to production in a smooth rollout.</div>
            </div>

            <div :class="open === 2 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 2 ? null : 2" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">Which payout rails are supported?</span>
                    <span class="text-lg font-bold" x-text="open === 2 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 2" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">MakeMyPayment supports UPI, IMPS, and bank transfer payouts so you can route transactions as per speed, amount, and beneficiary requirements.</div>
            </div>

            <div :class="open === 3 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 3 ? null : 3" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">Do you provide payout API and webhook support?</span>
                    <span class="text-lg font-bold" x-text="open === 3 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 3" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">Yes. You get developer-friendly payout APIs, webhook callbacks for status updates, and structured response formats for easy reconciliation.</div>
            </div>

            <div :class="open === 4 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 4 ? null : 4" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">Can we do bulk payouts with a single upload or API trigger?</span>
                    <span class="text-lg font-bold" x-text="open === 4 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 4" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">Absolutely. MakeMyPayment supports high-volume bulk disbursals with status tracking, retries, and operational visibility from one dashboard.</div>
            </div>

            <div :class="open === 5 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 5 ? null : 5" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">How secure are transactions on MakeMyPayment?</span>
                    <span class="text-lg font-bold" x-text="open === 5 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 5" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">Security controls are built into every layer with merchant verification, controlled access, and monitored transaction events to protect payout operations.</div>
            </div>

            <div :class="open === 6 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 6 ? null : 6" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">How do settlement tracking and reconciliation work?</span>
                    <span class="text-lg font-bold" x-text="open === 6 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 6" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">You can track initiated, processed, success, pending, and failed states in real time, then download or match records for accounting and finance reconciliation.</div>
            </div>

            <div :class="open === 7 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 7 ? null : 7" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">Do you provide daily payout limits and control settings?</span>
                    <span class="text-lg font-bold" x-text="open === 7 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 7" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">Yes, each merchant account can run with configured daily, minimum, and maximum payout limits to maintain risk and liquidity discipline.</div>
            </div>

            <div :class="open === 8 ? 'bg-indigo-600 border-indigo-300/70 text-white shadow-indigo-900/40' : 'bg-white/95 border-indigo-100 text-slate-900'" class="rounded-2xl border shadow-sm transition-all duration-200">
                <button @click="open = open === 8 ? null : 8" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold">How can we contact MakeMyPayment support?</span>
                    <span class="text-lg font-bold" x-text="open === 8 ? '-' : '+'"></span>
                </button>
                <div x-show="open === 8" x-transition class="px-5 pb-5 text-sm leading-6 text-white/90">For onboarding and payout support, email support@makemypayment.in or call +91 6354409951. Our team assists with technical and operational queries.</div>
            </div>
        </div>
    </div>
</section>
