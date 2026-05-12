<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category as CategoryModel;

class Category extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $categoryId = null;
    public $showModal = false;
    public $showDeleteModal = false;
    public $categoryToDelete = null;

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($categoryId)
    {
        $category = CategoryModel::find($categoryId);
        if (!$category) {
            $this->dispatch('toast', [
                'message' => 'Category not found.',
                'type' => 'error',
            ]);
            return;
        }
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function saveCategory()
    {
        // Update validation rule if editing
        if ($this->categoryId) {
            $this->rules['name'] = 'required|string|max:255|unique:categories,name,' . $this->categoryId;
        }

        $this->validate();

        try {
            if ($this->categoryId) {
                // Update existing category
                $category = CategoryModel::find($this->categoryId);
                $category->update(['name' => $this->name]);
                
                $this->dispatch('toast', [
                    'message' => 'Category updated successfully.',
                    'type' => 'success',
                ]);
            } else {
                // Create new category
                CategoryModel::create(['name' => $this->name]);
                
                $this->dispatch('toast', [
                    'message' => 'Category created successfully.',
                    'type' => 'success',
                ]);
            }

            $this->resetForm();
            $this->showModal = false;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Failed to save category. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = $categoryId;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if (!$this->categoryToDelete) {
            return;
        }

        try {
            $category = CategoryModel::find($this->categoryToDelete);
            
            if (!$category) {
                $this->dispatch('toast', [
                    'message' => 'Category not found.',
                    'type' => 'error',
                ]);
                return;
            }

            $categoryName = $category->name;
            $category->delete();

            $this->dispatch('toast', [
                'message' => "Category '{$categoryName}' deleted successfully.",
                'type' => 'success',
            ]);

            $this->cancelDelete();
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Failed to delete category. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    private function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = CategoryModel::when(!blank($this->search), function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderByDesc('id')
        ->paginate(10);

        return view('admin.category', [
            'categories' => $categories,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'categories',
            'pageTitle' => 'Categories Management',
            'metaTitle' => 'Categories Management - MMP Fintech',
            'metaDescription' => 'View and manage all categories in the MMP Fintech admin dashboard.',
        ]);
    }
}
