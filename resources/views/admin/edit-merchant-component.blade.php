<div class="space-y-6">
    <!-- Merchant Profile Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-3xl font-bold text-white">
                    {{ strtoupper(substr($merchant->full_name, 0, 2)) }}
                </div>
            </div>
            <div class="flex-grow">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $merchant->full_name }}</h1>
                <div class="text-gray-500 mb-3">{{ $merchant->merchant_id }}</div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $merchant->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $merchant->phone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form wire:submit.prevent="updateMerchant" class="space-y-6">
        <!-- Transaction Settings Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Transaction Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Daily Transfer Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Transfer Limit</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">₹</span>
                        <input type="number" wire:model="daily_transfer_limit" 
                               class="pl-8 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('daily_transfer_limit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Account Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                    <select wire:model="status" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Below Thousand Charge -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Below ₹1000 Charge (Fixed)</label>
                    <div class="relative">
                        <input type="number" step="0.01" wire:model="below_thousand_charge" 
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute right-3 top-2 text-gray-500">₹</span>
                    </div>
                    @error('below_thousand_charge') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Above Thousand Charge -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Above ₹1000 Charge (Percentage)</label>
                    <div class="relative">
                        <input type="number" step="0.01" wire:model="above_thousand_charge" 
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                    @error('above_thousand_charge') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- KYC Details Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Business & KYC Details</h2>
            
            <!-- Business Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                    <select wire:model="business_type" class="block w-full border-gray-300 rounded-lg">
                        <option value="private_limited">Private Limited</option>
                        <option value="partnership">Partnership</option>
                        <option value="proprietorship">Proprietorship</option>
                        <option value="llp">LLP</option>
                    </select>
                    @error('business_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                    <input type="text" wire:model="business_name" class="block w-full border-gray-300 rounded-lg">
                    @error('business_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">GSTIN</label>
                    <input type="text" wire:model="gstin" class="block w-full border-gray-300 rounded-lg">
                    @error('gstin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Address Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                    <textarea wire:model="business_address" rows="2" class="block w-full border-gray-300 rounded-lg"></textarea>
                    @error('business_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" wire:model="state" class="block w-full border-gray-300 rounded-lg">
                    @error('state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" wire:model="city" class="block w-full border-gray-300 rounded-lg">
                    @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIN Code</label>
                    <input type="text" wire:model="pin_code" class="block w-full border-gray-300 rounded-lg">
                    @error('pin_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Additional Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                    <input type="url" wire:model="website_url" class="block w-full border-gray-300 rounded-lg">
                    @error('website_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PAN</label>
                    <input type="text" wire:model="pan" class="block w-full border-gray-300 rounded-lg">
                    @error('pan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CIN Number</label>
                    <input type="text" wire:model="cin_number" class="block w-full border-gray-300 rounded-lg">
                    @error('cin_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- KYC Status -->
            <div class="mt-6 border-t pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">KYC Status</label>
                <select wire:model="kyc_status" class="block w-full border-gray-300 rounded-lg">
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                    <option value="submitted">Submitted</option>
                </select>
                @error('kyc_status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.merchants.view', $merchant->merchant_id) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save All Changes
            </button>
        </div>
    </form>

    <!-- Current Settings Card -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Current Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-sm text-gray-500">Daily Transfer Limit</div>
                <div class="text-lg font-semibold text-gray-900">₹{{ number_format($merchant->daily_transfer_limit, 2) }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Below ₹1000 Charge</div>
                <div class="text-lg font-semibold text-gray-900">₹{{ number_format($merchant->below_thousand_charge, 2) }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Above ₹1000 Charge</div>
                <div class="text-lg font-semibold text-gray-900">{{ number_format($merchant->above_thousand_charge, 2) }}%</div>
            </div>
        </div>
    </div>
</div>
