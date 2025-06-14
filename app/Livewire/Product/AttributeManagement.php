<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\ProductAttribute;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AttributeManagement extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    public $attributeId;
    public $name;

    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('product_attributes')->ignore($this->attributeId)],
        ];
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
        $attribute = ProductAttribute::findOrFail($id);
        $this->attributeId = $id;
        $this->name = $attribute->name;

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        ProductAttribute::updateOrCreate(['id' => $this->attributeId], [
            'name' => $this->name,
        ]);

        session()->flash('message', $this->attributeId ? 'Atribut berhasil diupdate.' : 'Atribut berhasil dibuat.');
        $this->closeModal();
    }

    public function delete($id): void
    {
        ProductAttribute::find($id)->delete();
        session()->flash('message', 'Atribut berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->attributeId = null;
        $this->name = '';
    }

    public function render()
    {
        $attributes = ProductAttribute::where('name', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        return view('livewire.product.attribute-management', [
            'attributes' => $attributes
        ])->layout('layouts.app');
    }
}
