<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubCategory as SubCategoryModel;
use App\Models\Category;

class SubCategory extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $category_id = '';
    public $subCategoryId = null;
    public $showModal = false;
    public $showDeleteModal = false;
    public $subCategoryToDelete = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
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

    public function openEditModal($subCategoryId)
    {
        $subCategory = SubCategoryModel::find($subCategoryId);
        if (!$subCategory) {
            $this->dispatch('toast', [
                'message' => 'Sub-category not found.',
                'type' => 'error',
            ]);
            return;
        }
        
        $this->subCategoryId = $subCategory->id;
        $this->name = $subCategory->name;
        $this->category_id = $subCategory->category_id;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function saveSubCategory()
    {
        $this->validate();

        try {
            if ($this->subCategoryId) {
                // Update existing sub-category
                $subCategory = SubCategoryModel::find($this->subCategoryId);
                $subCategory->update([
                    'name' => $this->name,
                    'category_id' => $this->category_id,
                ]);
                
                $this->dispatch('toast', [
                    'message' => 'Sub-category updated successfully.',
                    'type' => 'success',
                ]);
            } else {
                // Create new sub-category
                SubCategoryModel::create([
                    'name' => $this->name,
                    'category_id' => $this->category_id,
                ]);
                
                $this->dispatch('toast', [
                    'message' => 'Sub-category created successfully.',
                    'type' => 'success',
                ]);
            }

            $this->resetForm();
            $this->showModal = false;
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Failed to save sub-category. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function confirmDelete($subCategoryId)
    {
        $this->subCategoryToDelete = $subCategoryId;
        $this->showDeleteModal = true;
    }

    public function deleteSubCategory()
    {
        if (!$this->subCategoryToDelete) {
            return;
        }

        try {
            $subCategory = SubCategoryModel::find($this->subCategoryToDelete);
            
            if (!$subCategory) {
                $this->dispatch('toast', [
                    'message' => 'Sub-category not found.',
                    'type' => 'error',
                ]);
                return;
            }

            $subCategoryName = $subCategory->name;
            $subCategory->delete();

            $this->dispatch('toast', [
                'message' => "Sub-category '{$subCategoryName}' deleted successfully.",
                'type' => 'success',
            ]);

            $this->cancelDelete();
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Failed to delete sub-category. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->subCategoryToDelete = null;
    }

    private function resetForm()
    {
        $this->subCategoryId = null;
        $this->name = '';
        $this->category_id = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $subCategories = SubCategoryModel::with('category')
            ->when(!blank($this->search), function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderByDesc('id')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('admin.sub-category', [
            'subCategories' => $subCategories,
            'categories' => $categories,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'sub-categories',
            'pageTitle' => 'Sub-Categories Management',
            'metaTitle' => 'Sub-Categories Management - MMP Fintech',
            'metaDescription' => 'View and manage all sub-categories in the MMP Fintech admin dashboard.',
        ]);
    }
}
