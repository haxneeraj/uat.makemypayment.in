
{{-- Pending KYC Section --}}
<div class="mb-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <div>
                <span class="font-semibold text-yellow-800">Pending KYC</span>
                <span class="ml-2 text-sm text-yellow-700">Your account is not fully verified. Please complete your KYC to unlock all features.</span>
            </div>
        </div>
        <a href="{{ route('merchant.kyc') }}" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg text-sm transition">
            Complete your KYC
        </a>
    </div>
</div>
{{-- End Pending KYC Section --}}