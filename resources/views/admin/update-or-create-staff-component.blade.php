<div class="py-10">
    <div class="bg-white shadow rounded-lg">
        <form wire:submit="save" class="space-y-6 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $staffId ? 'Edit Staff Member' : 'Add New Staff Member' }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <small class="text-red-500">*</small></label>
                    <input type="text" wire:model="first_name" id="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <small class="text-red-500">*</small></label>
                    <input type="text" wire:model="last_name" id="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <small class="text-red-500">*</small></label>
                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone <small class="text-red-500">*</small></label>
                    <input type="text" wire:model="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>               

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password @if(!$staffId)<small class="text-red-500">*</small> @endif</label>
                    <input type="password" wire:model="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Password Confirmation @if(!$staffId)<small class="text-red-500">*</small> @endif</label>
                    <input type="password" wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">                    
                    <p class="text-sm text-white mt-2 bg-red-400 p-2 rounded"><strong>Note: </strong>Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</p>
                </div>

                <!-- Role -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select wire:model.live="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Permissions -->
                <div class="md:col-span-2" x-data="{ open: {{ $role ? 'true' : 'false' }} }" x-show="open">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm text-lg font-bold text-gray-700">Permissions</label>
                    </div>

                    @if ($role)
                        @forelse ($group_permissions as $group_name => $group_permission)
                            <div class="w-full mt-4 mb-2">{{ $group_name }}</div>
                            <hr />
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-64 overflow-y-auto border border-gray-200 p-4 rounded-md">
                                @foreach ($group_permission as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model.defer="permissions"
                                            value="{{ $permission->id }}"
                                            id="permission-{{ $permission->name }}"
                                            class="permission h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            checked />
                                        <label for="permission-{{ $permission->name }}" role="button"
                                            class="ml-2 text-sm text-gray-700">{{ $permission->label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-gray-500">No permissions found for this role.</p>
                        @endforelse
                        @error('permissions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.staffs') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel
                </a>
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $staffId ? 'Update' : 'Create' }} Staff Member
                </button>
            </div>
        </form>
    </div>
</div>
