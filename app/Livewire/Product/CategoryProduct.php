<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryProduct extends Component
{
    use WithPagination;

    // Properti untuk Modal dan Form
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    // Properti untuk Data Kategori
    public $categoryId;
    public $name;
    public $slug;
    public $parentId;

    // Properti untuk Fungsionalitas Tabel
    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'slug' => ['required', 'string', Rule::unique('categories')->ignore($this->categoryId)],
            'parentId' => ['nullable', 'exists:categories,id'],
        ];
    }

    // Dipanggil setiap kali properti 'name' diupdate
    public function updatedName($value): void
    {
        // Membuat slug secara otomatis dari nama
        $this->slug = Str::slug($value);
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function create(): void
    {
        $this->isEditMode = false;
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id): void
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parentId = $category->parent_id;

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parentId,
        ]);

        session()->flash('message', $this->categoryId ? 'Kategori berhasil diupdate.' : 'Kategori berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id): void
    {
        Category::find($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->parentId = null;
    }

    public function render()
    {
        $categories = Category::with('parent')
            ->where('name', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        // Digunakan untuk dropdown parent category
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('livewire.product.category-product', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ])->layout('layouts.app');
    }
}
