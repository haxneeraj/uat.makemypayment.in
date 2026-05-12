<div class="">
    {{-- Role Form --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form wire:submit.prevent="createOrUpdateRole">
            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Role Label</label>
                        <input type="text" wire:model.live.debounce.500ms="label" id="label"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Editor" />
                        @error('label')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name (Slug)</label>
                        <input type="text" wire:model.live="name" id="name"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., editor" />
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Permissions Section --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permissions</label>
                        <button type="button" onclick="togglePermissions()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Toggle All
                        </button>
                    </div>

                    @forelse ($group_permissions as $group_name => $group_permission)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">{{ $group_name }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($group_permission as $permission)
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors duration-200">
                                        <input type="checkbox" wire:model.defer="permissions"
                                            value="{{ $permission->id }}"
                                            class="permission h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                                        <span class="ml-3 text-sm text-gray-700">{{ $permission->label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No permissions found.</p>
                        </div>
                    @endforelse
                    @error('permissions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.roles') }}"
                        class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-2 bg-appPrimary text-white font-medium rounded-lg hover:opacity-90 transition-colors duration-200">
                        <svg wire:loading.remove class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg wire:loading class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ isset($role_id) ? 'Update' : 'Create' }} Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let allChecked = false;

        function togglePermissions() {
            allChecked = !allChecked;
            const checkboxes = document.querySelectorAll('.permission');
            checkboxes.forEach(cb => cb.checked = allChecked);
            
            // Trigger Livewire update
            checkboxes.forEach(cb => {
                cb.dispatchEvent(new Event('change'));
            });
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                // Replace with proper notification system in production
                alert(event.message);
            });
        });
    </script>
@endpush
