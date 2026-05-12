<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

use DirectoryTree\Authorization\Role;

class RoleComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $roleToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($roleId)
    {
        $role = Role::find($roleId);
        if ($role && $role->users_count > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete role that has users assigned to it.'
            ]);
            return;
        }
        
        $this->roleToDelete = $roleId;
        $this->showDeleteModal = true;
    }

    public function deleteRole()
    {
        if (!$this->roleToDelete) {
            return;
        }

        $role = Role::find($this->roleToDelete);
        
        if (!$role) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Role not found.'
            ]);
            return;
        }

        if ($role->users_count > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete role that has users assigned to it.'
            ]);
            return;
        }

        $roleName = $role->label;
        $role->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Role '{$roleName}' has been deleted successfully."
        ]);

        $this->cancelDelete();
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->roleToDelete = null;
    }

    public function render()
    {
        $roles = Role::withCount(['permissions', 'users'])
        ->whereNotIn('name', ['super-admin', 'merchant']);

        if (!blank($this->search)) {
            $roles->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        $roles = $roles->paginate(10);

        return view('admin.role-component', [
            'roles' => $roles,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'roles',
            'pageTitle' => 'Roles & Permissions',
            'metaTitle' => 'Roles & Permissions - MMP Fintech',
            'metaDescription' => 'Review and manage user roles and permissions.',
        ]);
    }
}
