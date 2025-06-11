<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\Brand;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class BrandProduct extends Component
{
    use WithPagination;

    // Properti untuk Modal dan Form
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    // Properti untuk Data Merek
    public $brandId;
    public $name;
    public $slug;

    // Properti untuk Fungsionalitas Tabel
    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2'],
            'slug' => ['required', 'string', Rule::unique('brands')->ignore($this->brandId)],
        ];
    }

    public function updatedName($value): void
    {
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
        $brand = Brand::findOrFail($id);
        $this->brandId = $id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        Brand::updateOrCreate(['id' => $this->brandId], [
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        session()->flash('message', $this->brandId ? 'Merek berhasil diupdate.' : 'Merek berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id): void
    {
        Brand::find($id)->delete();
        session()->flash('message', 'Merek berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->brandId = null;
        $this->name = '';
        $this->slug = '';
    }

    public function render()
    {
        $brands = Brand::where('name', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        return view('livewire.product.brand-product', [
            'brands' => $brands,
        ])->layout('layouts.app');
    }
}
