<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-12 gap-6">
        <!-- Left Sidebar with Tabs -->
        <div class="col-span-12 md:col-span-3">
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 space-y-2">
                    <button wire:click="setTab(1)" 
                        class="w-full text-left px-4 py-2 rounded-lg {{ $activeTab === 1 ? 'bg-appPrimary text-white' : 'hover:bg-gray-100' }}">
                        Change Password
                    </button>
                    <button wire:click="setTab(2)" 
                        class="w-full text-left px-4 py-2 rounded-lg {{ $activeTab === 2 ? 'bg-appPrimary text-white' : 'hover:bg-gray-100' }}">
                        IP and Webhook
                    </button>
                    <button wire:click="setTab(3
                    )" 
                        class="w-full text-left px-4 py-2 rounded-lg {{ $activeTab === 3 ? 'bg-appPrimary text-white' : 'hover:bg-gray-100' }}">
                        API Credentials
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="col-span-12 md:col-span-9">
            <!-- Change Password -->
            @if($activeTab == 1)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h2>

                    {{-- Change Password Form --}}
                    <div class="rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="ml-3 w-full">
                                <form wire:submit.prevent="updatePassword" class="mt-4 space-y-4">
                                    {{-- Current Password --}}
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" id="current_password" wire:model.defer="current_password"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm">
                                        @error('current_password')
                                            <span class="text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- New Password --}}
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" id="password" wire:model.defer="password"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm">
                                        @error('password')
                                            <span class="text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                        <input type="password" id="password_confirmation" wire:model.defer="password_confirmation"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm">
                                        @error('password_confirmation')
                                            <span class="text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="mt-6">
                                        <button type="submit"
                                            wire:target="updatePassword"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-70 cursor-not-allowed"
                                            class="flex items-center justify-center gap-2 bg-appPrimary hover:opacity-90 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15c1.657 0 3-1.343 3-3V8a3 3 0 10-6 0v4c0 1.657 1.343 3 3 3z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 12h14v9H5z" />
                                            </svg>
                                            Update Password
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

            <!-- IP and Webhook Settings -->
            @if($activeTab == 2)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">IP and Webhook Configuration</h2>

                    @if(!$kyc_status)
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.873-1.037 2.157-1.037 3.03 0l6.28 7.45c.873 1.037.63 1.882-.54 1.882h-14.51c-1.17 0-1.413-.845-.54-1.882l6.28-7.45z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        API Credentials Not Available
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>To generate API credentials, please ensure:</p>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>Your KYC is verified</li>
                                            <li>Virtual Account is active</li>
                                        </ul>
                                    </div>
                                    <div class="mt-6">
                                        <a href="{{ route('merchant.kyc') }}" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg text-sm transition">
                                            Complete your KYC
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else

                        <!-- Status Badge -->
                        <div class="mb-6">
                            <span class="text-sm font-medium mr-2">Status:</span>
                            @if($callback_and_ip_status === 'verified')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($callback_and_ip_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending Approval
                                </span>
                            @elseif($callback_and_ip_status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Not Configured
                                </span>
                            @endif
                        </div>

                        <form wire:submit.prevent="$set('showConfirmModal', true)" class="space-y-4">
                            <div>
                                <label for="ip" class="block text-sm font-medium text-gray-700">IP Address</label>
                                <input type="text" id="ip" wire:model="ip" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter your IP address">
                                @error('ip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="webhook_url" class="block text-sm font-medium text-gray-700">Webhook URL</label>
                                <input type="url" id="webhook_url" wire:model="webhook_url" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="https://your-domain.com/webhook">
                                @error('webhook_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="webhook_secret" class="block text-sm font-medium text-gray-700">Webhook Secret</label>
                                <input type="text" id="webhook_secret" wire:model="webhook_secret" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter webhook secret">
                                @error('webhook_secret') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                    wire:target="saveWebhookSettings"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="inline-flex items-center justify-center gap-1.5 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-appPrimary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- API Credentials -->
            @if($activeTab == 3)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">API Credentials</h2>

                    @if(!$kyc_status || $callback_and_ip_status != 'verified')
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.873-1.037 2.157-1.037 3.03 0l6.28 7.45c.873 1.037.63 1.882-.54 1.882h-14.51c-1.17 0-1.413-.845-.54-1.882l6.28-7.45z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        API Credentials Not Available
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>To generate API credentials, please ensure:</p>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>Your KYC is verified</li>
                                            <li>Virtual Account is active</li>
                                            <li>IP and Webhook settings are approved</li>
                                        </ul>
                                    </div>
                                    @if(!$kyc_status)
                                    <div class="mt-6">
                                        <a href="{{ route('merchant.kyc') }}" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg text-sm transition">
                                            Complete your KYC
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">API Key</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" readonly value="{{ $api_key }}" id="apiKeyInput"
                                        class="flex-1 block w-full rounded-md border-gray-300 bg-gray-50">
                                    <button type="button" onclick="copyToClipboard('apiKeyInput', this)" 
                                        class="ml-2 inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                        </svg>
                                        <span>Copy</span>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">API Secret</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" readonly value="{{ $api_secret }}" id="apiSecretInput"
                                        class="flex-1 block w-full rounded-md border-gray-300 bg-gray-50">
                                    <button type="button" onclick="copyToClipboard('apiSecretInput', this)"
                                        class="ml-2 inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                        </svg>
                                        <span>Copy</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                    wire:click ="generateAPICredentials"
                                    wire:target="generateAPICredentials"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="inline-flex items-center justify-center gap-1.5 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-appPrimary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m14.836 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.17 15m13.248 0H15"/>
                                    </svg>
                                    Regenerate Credentials
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75">
        <div class="bg-red-100 rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Update</h3>
            <p class="text-sm text-gray-600 mb-6">Your IP and Webhook settings are currently verified. Updating them will require re-verification from admin. During this time, your current API will stop working and no transactions will be processed until admin approval. Do you want to continue?</p>
            
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showConfirmModal', false)" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </button>
                <button wire:click="saveWebhookSettings" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-appPrimary rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Send OTP & Continue
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showAPIpopup)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75">
        <div class="bg-red-100 rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Regenerate API Credentials</h3>
            <p class="text-sm text-gray-600 mb-6">Your current API credentials will be regenerated. OTP verification is required to continue.</p>

            <div class="flex justify-end gap-3">
                <button wire:click="$set('showAPIpopup', false)" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </button>
                <button wire:click="generateAPICredentials" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-appPrimary rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Send OTP & Continue
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- OTP Verification Modal -->
    @if($showOTPModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 px-4" x-data="{
        resendTimer: @entangle('resendTimer'),
        resendInterval: null,
        startTimer() {
            if (this.resendInterval) clearInterval(this.resendInterval);
            if (this.resendTimer > 0) {
                this.resendInterval = setInterval(() => {
                    if (this.resendTimer > 0) this.resendTimer--;
                    if (this.resendTimer <= 0 && this.resendInterval) {
                        clearInterval(this.resendInterval);
                        this.resendInterval = null;
                    }
                }, 1000);
            }
        }
    }" x-init="startTimer(); $watch('resendTimer', (value) => {
        if (value > 0 && !resendInterval) startTimer();
        if (value <= 0 && resendInterval) {
            clearInterval(resendInterval);
            resendInterval = null;
        }
    })">
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-indigo-100 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.20)]">
            <div class="bg-gradient-to-r from-indigo-600 to-cyan-600 px-6 py-4 text-white">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11V7m0 0l-3 3m3-3l3 3m6 3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold leading-tight">OTP Verification</h3>
                        <p class="text-xs text-indigo-100">Enter your secure 6-digit code</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <p class="mb-4 text-sm text-slate-600">We have sent an OTP to your registered mobile number. Verify to continue this sensitive action.</p>

                <div>
                    <label for="otp" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">One-Time Password</label>
                    <input
                        type="text"
                        id="otp"
                        wire:model.defer="otp"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-center text-2xl font-black tracking-[0.35em] text-slate-900 placeholder:tracking-normal placeholder:font-medium placeholder:text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        placeholder="000000"
                    >
                    @error('otp') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4 flex items-center justify-between rounded-xl border border-indigo-100 bg-indigo-50 px-3 py-2">
                    <span class="text-xs font-medium text-indigo-700">Did not receive OTP?</span>
                    <template x-if="resendTimer > 0">
                        <span class="text-xs font-semibold text-indigo-700">Resend in <span x-text="resendTimer"></span>s</span>
                    </template>
                    <template x-if="resendTimer <= 0">
                        <button type="button" wire:click="resendActionOtp" class="text-xs font-semibold text-indigo-700 hover:text-indigo-900">
                            Resend OTP
                        </button>
                    </template>
                </div>

                <div class="mt-6 flex flex-wrap justify-end gap-2">
                    <button wire:click="cancelOtpVerification" class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button wire:click="verifyActionOtp" class="inline-flex items-center gap-1.5 rounded-lg bg-appPrimary px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Verify & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function copyToClipboard(inputId, button) {
        const input = document.getElementById(inputId);
        const originalText = button.innerHTML;
        
        // Select the text
        input.select();
        input.setSelectionRange(0, 99999); // For mobile devices
        
        // Copy the text
        navigator.clipboard.writeText(input.value).then(() => {
            // Update button text temporarily
            button.innerHTML = '<span class="text-green-600">Copied!</span>';
            setTimeout(() => {
                button.innerHTML = originalText;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            button.innerHTML = '<span class="text-red-600">Failed to copy</span>';
            setTimeout(() => {
                button.innerHTML = originalText;
            }, 2000);
        });
    }
</script>
@endpush
