<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier\ManagementSupplier as ManagementSuppliers;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ManagementSupplier extends Component
{
    use WithPagination;

    // Properti untuk Modal dan Form
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    // Properti untuk Data Pemasok
    public $supplierId;
    public $name, $email, $phone, $address, $contact_person;

    // Properti untuk Fungsionalitas Tabel
    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'email', Rule::unique('management_suppliers')->ignore($this->supplierId)],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string'],
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
        $supplier = ManagementSuppliers::findOrFail($id);
        $this->supplierId = $id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->contact_person = $supplier->contact_person;

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        ManagementSuppliers::updateOrCreate(['id' => $this->supplierId], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'contact_person' => $this->contact_person,
        ]);

        session()->flash('message', $this->supplierId ? 'Pemasok berhasil diupdate.' : 'Pemasok berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id): void
    {
        ManagementSuppliers::find($id)->delete();
        session()->flash('message', 'Pemasok berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->supplierId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->contact_person = '';
    }

    public function render()
    {
        $suppliers = ManagementSuppliers::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        return view('livewire.supplier.management-supplier', [
            'suppliers' => $suppliers,
        ])->layout('layouts.app');
    }
}
