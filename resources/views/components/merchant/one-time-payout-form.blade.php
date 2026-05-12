<div>
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-start justify-center py-8">
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 p-8">
            <div class="absolute top-4 right-4">
                <button wire:click="close" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-1">One Time Payout</h3>

            <!-- Step Indicator -->
            <div class="flex items-center gap-2 mb-6">
                <span class="text-xs px-2 py-0.5 rounded-full {{ $formStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1 Details</span>
                <span class="text-gray-300 text-xs">›</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $formStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2 OTP</span>
                <span class="text-gray-300 text-xs">›</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $formStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">3 Confirm</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Wallet Balance</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($currentWalletBalance, 2) }}</p>
                </div>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Daily Limit</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($dailyTransferLimit, 2) }}</p>
                </div>
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Today Transferred</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($todayTransferredAmount, 2) }}</p>
                </div>
                <div class="rounded-xl border border-violet-100 bg-violet-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Min Transfer</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($minTransferLimit, 2) }}</p>
                </div>
                <div class="rounded-xl border border-rose-100 bg-rose-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Max Transfer</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($maxTransferLimit, 2) }}</p>
                </div>
                <div class="rounded-xl border border-orange-100 bg-orange-50 p-3">
                    <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wide">Remaining Limit</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format(max(0, $dailyTransferLimit - $todayTransferredAmount), 2) }}</p>
                </div>
            </div>

            @if ($formStep === 1)
                <form wire:submit.prevent="requestOtp">
                    <div class="space-y-4">

                        {{-- Beneficiary Details --}}
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Beneficiary Details</p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Holder Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="account_holder" placeholder="e.g. Rasik Mehta" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('account_holder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Account Number <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="account_number" placeholder="e.g. 20451185253" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IFSC Code <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="ifsc_code" placeholder="e.g. ICIC0000056" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('ifsc_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="bank_name" placeholder="e.g. ICICI Bank" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Branch Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="branch_name" placeholder="e.g. Karol Bagh" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('branch_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Branch Code <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="branch_code" placeholder="e.g. PYTM0123557" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('branch_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mobile <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="mobile" placeholder="e.g. 9834464262" maxlength="10" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('mobile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" placeholder="e.g. user@example.com" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="city" placeholder="e.g. Delhi" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" wire:model="state" placeholder="e.g. Delhi (letters only)" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pincode</label>
                                <input type="text" wire:model="pincode" placeholder="e.g. 110005" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('pincode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Beneficiary Address <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="beneficiary_address" placeholder="e.g. Delhi" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('beneficiary_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Transaction Details --}}
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider pt-2">Transaction Details</p>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount (₹) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.live.debounce.500ms="amount" placeholder="e.g. 500" min="1" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mode <span class="text-red-500">*</span></label>
                                <select wire:model="mode" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="imps">IMPS</option>
                                    <option value="neft">NEFT</option>
                                    <option value="rtgs">RTGS</option>
                                    <option value="a2a">A2A</option>
                                </select>
                                @error('mode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purpose <span class="text-red-500">*</span></label>
                                <select wire:model="purpose" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="vendorpayment">Vendor Payment</option>
                                    <option value="salary">Salary</option>
                                    <option value="all">All</option>
                                </select>
                                @error('purpose') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="rounded-xl border border-amber-100 bg-amber-50 p-3 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-600">Charges</span>
                                <span class="font-semibold text-gray-900">₹{{ number_format($calculatedChargeAmount, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 mt-1">
                                <span class="text-gray-600">Total Debit (Amount + Charges)</span>
                                <span class="font-bold text-gray-900">₹{{ number_format($totalDebitAmount, 2) }}</span>
                            </div>
                            <p class="mt-1 text-xs text-amber-700">{{ $chargeRuleText }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Remarks</label>
                                <input type="text" wire:model="remarks" placeholder="Optional" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Narration</label>
                                <input type="text" wire:model="narration" placeholder="Optional" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('narration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed" wire:target="requestOtp" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow hover:bg-blue-700 transition disabled:opacity-50 mt-2">
                            <span wire:loading.remove wire:target="requestOtp">Request OTP</span>
                            <span wire:loading wire:target="requestOtp">
                                <svg class="inline w-4 h-4 animate-spin mr-2" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending OTP...
                            </span>
                        </button>
                    </div>
                </form>

            @elseif ($formStep === 2)
                <form wire:submit.prevent="verifyOtp">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">An OTP has been sent to your registered mobile number.</p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Enter OTP</label>
                            <input type="text" wire:model="otp" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" maxlength="6" placeholder="6-digit OTP">
                            @error('otp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @session('error')
                                <span class="text-red-500 text-xs">{{ session('error') }}</span>
                            @endsession
                        </div>
                        <div class="flex gap-3">
                            <button type="button" wire:click="$set('formStep', 1)" class="flex-1 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">Back</button>
                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed" wire:target="verifyOtp" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow hover:bg-blue-700 transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="verifyOtp">Verify OTP</span>
                                <span wire:loading wire:target="verifyOtp">
                                    <svg class="inline w-4 h-4 animate-spin mr-2" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Verifying...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

            @elseif ($formStep === 3)
                <form wire:submit.prevent="submit">
                    <div class="space-y-4">
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                            <p class="text-green-700 text-sm font-semibold mb-3">OTP verified. Review and confirm payout:</p>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm text-gray-700">
                                <div><span class="text-gray-500">Beneficiary:</span> {{ $account_holder }}</div>
                                <div><span class="text-gray-500">Account No:</span> {{ $account_number }}</div>
                                <div><span class="text-gray-500">IFSC:</span> {{ $ifsc_code }}</div>
                                <div><span class="text-gray-500">Bank:</span> {{ $bank_name }}</div>
                                <div><span class="text-gray-500">Branch:</span> {{ $branch_name }}</div>
                                <div><span class="text-gray-500">City:</span> {{ $city }}</div>
                                <div><span class="text-gray-500">Mobile:</span> {{ $mobile }}</div>
                                <div><span class="text-gray-500">Mode:</span> {{ strtoupper($mode) }}</div>
                                <div><span class="text-gray-500">Purpose:</span> {{ ucfirst($purpose) }}</div>
                                <div class="col-span-2 mt-1 text-base font-bold text-gray-900">Amount: ₹{{ number_format($amount, 2) }}</div>
                                <div class="col-span-2 text-sm text-gray-800">Charges: ₹{{ number_format($calculatedChargeAmount, 2) }}</div>
                                <div class="col-span-2 text-base font-bold text-gray-900">Total Debit (Amount + Charges): ₹{{ number_format($totalDebitAmount, 2) }}</div>
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed" wire:target="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow hover:bg-blue-700 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="submit">Confirm & Submit Payout</span>
                            <span wire:loading wire:target="submit">
                                <svg class="inline w-4 h-4 animate-spin mr-2" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif
</div>