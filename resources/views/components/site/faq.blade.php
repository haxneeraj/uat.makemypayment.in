<section class="py-24 bg-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-flex items-center rounded-full bg-indigo-500/10 border border-indigo-500/20 px-3 py-1 text-sm font-medium text-indigo-400 mb-4">
                Frequently Asked Questions
            </div>
            <h2 class="text-3xl font-bold text-slate-100 mb-4">MakeMyPayment FAQ: Secure Payment Gateway & Services</h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Discover answers to the most common questions about MakeMyPayment's secure online payment gateway, merchant solutions, and fintech services in India.
            </p>
        </div>
        
        <div x-data="{ open: 1 }" class="space-y-4">
            <!-- FAQ 1 -->
            <div class="border border-slate-800 rounded-xl bg-slate-900 shadow-sm">
                <button @click="open = open === 1 ? null : 1" class="w-full flex justify-between items-center px-6 py-4 focus:outline-none">
                    <span class="font-semibold text-slate-100 text-lg text-left">How do I sign up for MakeMyPayment's payment gateway?</span>
                    <svg :class="open === 1 ? 'rotate-180 text-indigo-400' : 'text-slate-500'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 1" x-transition class="px-6 pb-6 text-slate-400">
                    <p>To register, click on <a href="/register" class="text-indigo-400 underline">Get Started</a>, complete your business and contact details, and submit the required documents. Our onboarding process is quick and secure, enabling you to start accepting payments within minutes after verification.</p>
                </div>
            </div>
            
            <!-- FAQ 2 -->
            <div class="border border-slate-800 rounded-xl bg-slate-900 shadow-sm">
                <button @click="open = open === 2 ? null : 2" class="w-full flex justify-between items-center px-6 py-4 focus:outline-none">
                    <span class="font-semibold text-slate-100 text-lg text-left">Is MakeMyPayment PCI DSS compliant and secure?</span>
                    <svg :class="open === 2 ? 'rotate-180 text-indigo-400' : 'text-slate-500'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 2" x-transition class="px-6 pb-6 text-slate-400">
                    <p>Absolutely. MakeMyPayment is PCI DSS Level 1 certified, ensuring the highest standards of payment security. We use advanced SSL encryption, two-factor authentication, and real-time fraud monitoring to protect your transactions and sensitive data.</p>
                </div>
            </div>
            
            <!-- FAQ 3 -->
            <div class="border border-slate-800 rounded-xl bg-slate-900 shadow-sm">
                <button @click="open = open === 3 ? null : 3" class="w-full flex justify-between items-center px-6 py-4 focus:outline-none">
                    <span class="font-semibold text-slate-100 text-lg text-left">How can I reach MakeMyPayment customer support?</span>
                    <svg :class="open === 3 ? 'rotate-180 text-indigo-400' : 'text-slate-500'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 3" x-transition class="px-6 pb-6 text-slate-400">
                    <p>Contact our 24/7 support team via email at <a href="mailto:support@makemypayment.in" class="text-indigo-400 underline">support@makemypayment.in</a> or call <a href="tel:+916354409951" class="text-indigo-400 underline">+91 6354409951</a>. We're committed to providing fast, reliable assistance for all your payment gateway needs.</p>
                </div>
            </div>
            
            <!-- FAQ 4 -->
            <div class="border border-slate-800 rounded-xl bg-slate-900 shadow-sm">
                <button @click="open = open === 4 ? null : 4" class="w-full flex justify-between items-center px-6 py-4 focus:outline-none">
                    <span class="font-semibold text-slate-100 text-lg text-left">What payment solutions and services does MakeMyPayment offer?</span>
                    <svg :class="open === 4 ? 'rotate-180 text-indigo-400' : 'text-slate-500'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === 4" x-transition class="px-6 pb-6 text-slate-400">
                    <p>We provide a comprehensive suite of fintech solutions including online payment gateway integration, bill payments, white label platforms, secure API services, Aadhaar-enabled banking, and merchant onboarding. Visit our <a href="/services" class="text-indigo-400 underline">Services</a> page for more details.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<!-- Alpine.js for accordion functionality (if not already included globally) -->
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
