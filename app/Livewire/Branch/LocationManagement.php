<?php

namespace App\Livewire\Branch;

use App\Models\Branch\Location;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class LocationManagement extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public bool $isEditMode = false;
    public $locationId;
    public $name, $address, $is_active = true;


    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('locations')->ignore($this->locationId)],
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->isEditMode = false;
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit(Location $location): void
    {
        $this->isEditMode = true;
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->address = $location->address;
        $this->is_active = $location->is_active;
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate();
        Location::updateOrCreate(['id' => $this->locationId], [
            'name' => $this->name,
            'address' => $this->address,
            'is_active' => $this->is_active,
        ]);
        session()->flash('message', 'Lokasi berhasil disimpan.');
        $this->closeModal();
    }

    public function delete(Location $location): void
    {
        $location->delete();
        session()->flash('message', 'Lokasi berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->locationId = null;
        $this->name = '';
        $this->address = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.branch.location-management', [
            'locations' => Location::paginate(10)
        ])->layout('layouts.app');
    }
}
