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
                    {{-- Success Message --}}
                    @if(Session::has('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(Session::has('error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800">
                            {{ Session::get('error') }}
                        </div>
                    @endif

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
        </div>
    </div>
</div>