<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use DirectoryTree\Authorization\Role;
use DirectoryTree\Authorization\Permission;

class UpdateOrCreateRoleComponent extends Component
{
    public $role_id;
    public $role;
    public $permissions = [];

    public $name;
    public $label;

    protected $pageTitle = 'Create New Role';
    protected $metaDescription = 'Create a new user role and assign permissions.';

    protected $rules = [
        'label' => 'required',
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array|min:1'
    ];

    protected $messages = [
        'label.required' => 'Role label is required.',
        'name.required' => 'Role name is required.',
        'name.unique' => 'This role already exists.',
        'permissions.required' => 'You must select at least one permission.',
        'permissions.min' => 'Please select at least one permission.',
    ];

    public function mount($role_id = null)
    {
        if(!blank($role_id))
        {
            $this->role = Role::find($role_id);
            if($this->role)
            {
                $this->rules['name'] = 'required|unique:roles,name,' . $this->role_id;
                
                $this->role_id = $this->role->id;

                $this->pageTitle = "Edit Role - " . $this->role->name;
                $this->metaDescription = "Edit the role " . $this->role->name;

                $this->name = $this->role->name;
                $this->label = $this->role->label;
                $this->permissions = $this->role->permissions->pluck('id')->toArray();
            }
            else
            {
                // If Role Not Found, Reset the Form
                $this->role_id = null;
                $this->reset(['label', 'name', 'permissions']);
            }
        }
    }

    public function updatedLabel()
    {
        $this->name = \Str::slug($this->label);

        $this->validateOnly('name');
    }

    public function updatedName($value)
    {
        $this->validateOnly('name');
    }

    public function getPermissions()
    {
        return Permission::select('id', 'label', 'name', 'group_name')
        ->orderBy('group_name')
        ->orderBy('name')
        ->get()
        ->groupBy('group_name');
    }

    public function createOrUpdateRole()
    {
        #dynamic rules for name field
        if(!blank($this->role_id))
        {
            $this->rules['name'] = 'required|unique:roles,name,' . $this->role_id;
        }

        # Validate the form data
        $this->validate();

        # Create or Update the Role
        $role = Role::updateOrCreate(
            ['id' => $this->role_id],
            [
                'name' => $this->name,
                'label' => $this->label,
            ]
        );

        // Assign Permissions (if any)
        if ($role && !blank($this->permissions)) {
            $role->revokeAll();

            $permissions = Permission::whereIn('id', $this->permissions)->get();
            if (!$permissions->isEmpty()) {
                $role->grant($permissions);
            }
        }

        // type created or updated
        $type = blank($this->role_id) ? 'created' : 'updated';

        // Reset the form if created
        if ($type === 'created') {
            $this->reset(['label', 'name', 'permissions']);
        }

        // Return the success response
        return session()->flash('success', "Role {$role->name} has been successfully {$type}.");
    }

    public function render()
    {
        return view('admin.update-or-create-role-component', [
            'role' => $this->role,
            'group_permissions' => $this->getPermissions()
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'roles',
            'pageTitle' => $this->pageTitle,
            'metaTitle' => $this->pageTitle . ' - MMP Fintech',
            'metaDescription' => $this->metaDescription,
        ]);
    }
}
