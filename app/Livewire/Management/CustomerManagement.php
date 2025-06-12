<?php

namespace App\Livewire\Management;

use App\Models\Crm\Customer;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerManagement extends Component
{
    use WithPagination;

    // Properti untuk Modal dan Form
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    // Properti untuk Data Pelanggan
    public $customerId;
    public $name, $email, $phone, $address;

    // Properti untuk Fungsionalitas Tabel
    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'email', Rule::unique('customers')->ignore($this->customerId)],
            'phone' => ['nullable', 'string', Rule::unique('customers')->ignore($this->customerId)],
            'address' => ['nullable', 'string'],
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
        $customer = Customer::findOrFail($id);
        $this->customerId = $id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        Customer::updateOrCreate(['id' => $this->customerId], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('message', $this->customerId ? 'Data pelanggan berhasil diupdate.' : 'Pelanggan baru berhasil ditambahkan.');

        $this->closeModal();
    }

    public function delete($id): void
    {
        Customer::find($id)->delete();
        session()->flash('message', 'Data pelanggan berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->customerId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
    }

    public function render()
    {
        $customers = Customer::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orWhere('phone', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        return view('livewire.management.customer-management', [
            'customers' => $customers
        ])->layout('layouts.app');
    }
}
