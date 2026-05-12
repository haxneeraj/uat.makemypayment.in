<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;

use DirectoryTree\Authorization\Role;
use DirectoryTree\Authorization\Permission;

use Illuminate\Support\Facades\DB;

class UpdateOrCreateStaffComponent extends Component
{
    public $staffId;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role;
    public $permissions = [];

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:10|unique:users,phone',
        'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        'role' => 'required|exists:roles,id',
        'permissions' => 'required|array|min:1',
    ];

    protected $messages = [
        'first_name.required' => 'First name is required.',
        'first_name.max' => 'First name cannot exceed 255 characters.',
        'last_name.required' => 'Last name is required.',
        'last_name.max' => 'Last name cannot exceed 255 characters.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please provide a valid email address.',
        'email.unique' => 'This email is already taken.',
        'phone.required' => 'Phone number is required.',
        'phone.max' => 'Phone number cannot exceed 20 characters.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, and one number.',
        'role.required' => 'Please select a role.',
        'role.exists' => 'Selected role is invalid.',
        'permissions.required' => 'You must select at least one permission.',
        'permissions.min' => 'Please select at least one permission.',
    ];

    public function mount($staff_id = null)
    {
        if ($staff_id) {
            $this->staffId = $staff_id;
            $staff = User::findOrFail($staff_id);
            $this->first_name = $staff->first_name;
            $this->last_name = $staff->last_name;
            $this->email = $staff->email;
            $this->phone = $staff->phone;
            $this->role = $staff->roles->first()?->id;
            $this->permissions = $staff->permissions()->pluck('id')->toArray();
        }
    }

    protected function getPermissionsForRole($role_id)
    {
        $role = Role::with('permissions')->find($role_id);

        # return empty collection if role not found
        if(!$role) {
            return collect();
        }

        return Permission::whereIn('id', $role->permissions->pluck('id'))
        ->orderBy('group_name')
        ->orderBy('name')
        ->get()
        ->groupBy('group_name');
    }

    public function save()
    {
        # Dynamic Validation for Update and create
        $this->rules['email'] = blank($this->staffId) ? 'required|email|unique:users,email' : 'required|email|unique:users,email,' . $this->staffId;
        $this->rules['phone'] = blank($this->staffId) ? 'required|string|max:10|unique:users,phone' : 'required|string|max:10|unique:users,phone,' . $this->staffId;
        $this->rules['password'] = blank($this->staffId) ? 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/' : 'nullable|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/';

        # Validate
        $this->validate();

        # DB Transaction Begin
        DB::beginTransaction();

        try
        {
            $user = $this->staffId ? User::find($this->staffId) : new User;
            
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->full_name = $this->first_name . ' ' . $this->last_name;
            $user->merchant_id = rand(100000, 999999);
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->role = $this->role;
            
            if (!blank($this->password)) {
                $user->password = bcrypt($this->password);
            }
            
            $user->save();

            # get the role
            $role = Role::findOrFail($this->role);

            # assign role and permissions
            $user->roles()->sync($role->id);
            $user->update(['role' => $role->name]);
            $user->permissions()->sync($this->permissions);

            # DB Transaction Commit
            DB::commit();

            # dispatch success event
            $this->dispatch('swal:success', [
                'title' => $this->staffId ? 'Staff Updated' : 'Staff Created',
                'text' => $this->staffId ? 'Staff updated successfully.' : 'Staff created successfully.',
                'icon' => $this->staffId ? 'success' : 'info',
            ]);

            # Reset form if creating new
            if(blank($this->staffId))
            {
                $this->resetForm();
            }
        }
        catch(\Exception $e)
        {
            # DB Transaction Rollback
            DB::rollBack();

            # Log the error
            \Log::error([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            # dispatch error event
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'An error occurred while saving the staff. Please try again.',
                'icon' => 'error',
            ]);
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'first_name',
            'last_name',
            'email',
            'phone',
            'password',
            'password_confirmation',
            'role',
            'permissions',
        ]);
    }

    public function render()
    {
        return view('admin.update-or-create-staff-component', [
            'roles' => Role::whereNotIn('name', ['merchant', 'super-admin'])->get(),
            'group_permissions' => $this->getPermissionsForRole($this->role)
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'staffs',
            'pageTitle' => 'Staff Management',
            'metaTitle' => 'Staff Management - MMP Fintech',
            'metaDescription' => 'View and manage all staff members in the MMP Fintech admin dashboard.',
        ]);
    }
}
