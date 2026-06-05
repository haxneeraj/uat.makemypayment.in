<section class="relative overflow-hidden border-y border-slate-200 bg-gradient-to-b from-slate-50 via-white to-indigo-50/40 py-16 sm:py-20">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_12%_8%,rgba(99,102,241,0.10),transparent_30%),radial-gradient(circle_at_88%_12%,rgba(6,182,212,0.10),transparent_26%)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-30" style="background-image: linear-gradient(to right, rgba(148,163,184,0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(148,163,184,0.14) 1px, transparent 1px); background-size: 34px 34px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex rounded-full border border-indigo-200 bg-white px-3 py-1 text-xs font-semibold tracking-[0.2em] uppercase text-indigo-700 shadow-sm">
                Client Voice
            </div>
            <h2 class="mt-4 text-3xl sm:text-4xl font-black text-slate-900">Loved by Payout Teams Across India</h2>
            <p class="mt-3 text-slate-600">Real feedback from merchants and operators using MakeMyPayment for instant, bulk, and API-first disbursals.</p>
        </div>

        @php
            $testimonials = [
                ['name' => 'Rohan Malhotra', 'role' => 'Operations Lead, QuickKart', 'message' => 'Same-day settlements and clean status webhooks reduced our payout support tickets significantly.', 'tone' => 'indigo'],
                ['name' => 'Aditi Jain', 'role' => 'Finance Team, EaseLoan', 'message' => 'The dashboard gives us exact visibility on success, pending, and failed payouts in one place.', 'tone' => 'cyan'],
                ['name' => 'Vivek S', 'role' => 'Founder, VendorPay', 'message' => 'Bulk payout execution is fast and stable even during peak cycles at month end.', 'tone' => 'slate'],
                ['name' => 'Neha Arora', 'role' => 'Payments Manager, RetailGrid', 'message' => 'API docs were clear and integration was quick. We moved from testing to live rollout smoothly.', 'tone' => 'indigo'],
                ['name' => 'Nitin Kumar', 'role' => 'Treasury Ops, FinBridge', 'message' => 'The reconciliation flow is practical for accounting, and report exports save a lot of time.', 'tone' => 'cyan'],
                ['name' => 'Pooja Mehta', 'role' => 'Product, DisbursalHub', 'message' => 'Routing across rails improved delivery rates without adding complexity to our team.', 'tone' => 'slate'],
                ['name' => 'Ankit Roy', 'role' => 'CTO, SalarySprint', 'message' => 'Webhook callbacks are reliable and help our systems update payout states instantly.', 'tone' => 'indigo'],
                ['name' => 'Ritika Shah', 'role' => 'Finance Ops, GrowLine', 'message' => 'Limit controls and alert visibility help us run safer payout operations daily.', 'tone' => 'cyan'],
                ['name' => 'Harsh Patel', 'role' => 'Founder, UrbanSupply', 'message' => 'Support team is responsive and understands both technical and operational payout issues.', 'tone' => 'slate'],
                ['name' => 'Meera Iyer', 'role' => 'Controller, B2BBox', 'message' => 'We now track every payout lifecycle stage without relying on manual follow-ups.', 'tone' => 'indigo'],
                ['name' => 'Saurabh Rana', 'role' => 'Head of Payments, LoanWire', 'message' => 'MakeMyPayment helped us scale from low volumes to high-frequency disbursals confidently.', 'tone' => 'cyan'],
                ['name' => 'Karan Desai', 'role' => 'Accounts, SwiftMart', 'message' => 'The overall product feels built for payout-heavy businesses, not generic payment flows.', 'tone' => 'slate'],
            ];
        @endphp

        <div class="mt-10 rounded-3xl border border-indigo-100/80 bg-white/75 p-4 shadow-[0_24px_70px_rgba(30,41,59,0.08)] backdrop-blur sm:p-6">
            <div class="columns-1 gap-4 sm:columns-2 xl:columns-4">
                @foreach($testimonials as $item)
                    @php
                        $tone = $item['tone'];
                        $cardClass = match($tone) {
                            'indigo' => 'border-indigo-200 bg-gradient-to-b from-indigo-50 to-white',
                            'cyan' => 'border-cyan-200 bg-gradient-to-b from-cyan-50 to-white',
                            default => 'border-slate-200 bg-gradient-to-b from-slate-50 to-white',
                        };

                        $dotClass = match($tone) {
                            'indigo' => 'bg-indigo-500',
                            'cyan' => 'bg-cyan-500',
                            default => 'bg-slate-500',
                        };
                    @endphp

                    <article class="mb-4 break-inside-avoid rounded-2xl border {{ $cardClass }} p-4 shadow-sm transition-transform duration-200 hover:-translate-y-0.5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                    {{ strtoupper(substr($item['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item['name'] }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $item['role'] }}</p>
                                </div>
                            </div>
                            <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full {{ $dotClass }}"></span>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $item['message'] }}</p>

                        <div class="mt-3 flex items-center justify-between border-t border-slate-200/80 pt-3">
                            <span class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Verified Client</span>
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">5.0 Rating</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
