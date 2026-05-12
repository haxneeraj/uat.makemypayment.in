<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class StaffComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $employeeToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($employeeId)
    {
        $this->employeeToDelete = $employeeId;
        $this->showDeleteModal = true;
    }

    public function deleteEmployee()
    {
        if (!$this->employeeToDelete) {
            return;
        }

        $employee = User::find($this->employeeToDelete);
        
        if (!$employee) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Employee not found.',
                'icon' => 'error',
            ]);
            return;
        }

        $employeeName = $employee->full_name;
        $employee->delete();

        $this->dispatch('swal:success', [
            'title' => 'Success',
            'text' => "Employee '{$employeeName}' has been deleted successfully.",
            'icon' => 'success',
        ]);

        $this->cancelDelete();
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->employeeToDelete = null;
    }

    public function render()
    {
        $employees = User::whereNotIn('role', ['merchant', 'super-admin'])
        ->when(!blank($this->search), function ($query){
            $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        })
        ->orderByDesc('id')
        ->paginate(10); 

        return view('admin.staff-component', [
            'employees' => $employees,
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
