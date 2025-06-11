<?php

namespace App\Livewire\Management;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManagement extends Component
{

    public $roleId, $roleName;
    public array $selectedPermissions = [];

    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    protected function rules(): array
    {
        return [
            // Menggunakan Rule::unique() untuk validasi nama role
            'roleName' => [
                'required',
                'string',
                'min:3',
                Rule::unique('roles', 'name')->ignore($this->roleId)
            ],
            'selectedPermissions' => 'required|array|min:1',
        ];
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->openModal();
    }

    public function edit($id): void
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditMode = true;

        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        $role = Role::updateOrCreate(
            ['id' => $this->roleId],
            [
                'name' => $this->roleName,
                'guard_name' => 'web'
            ]
        );
        $role->syncPermissions($this->selectedPermissions);

        session()->flash('message', 'Role berhasil disimpan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id): void
    {
        // Mencegah penghapusan role Super Admin
        $role = Role::findOrFail($id);
        if ($role->name === 'Super Admin') {
            session()->flash('error', 'Role Super Admin tidak dapat dihapus.');
            return;
        }

        $role->delete();
        session()->flash('message', 'Role berhasil dihapus.');
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields(): void
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
    }

    public function render()
    {
        // Ambil data roles dan konversi ke array untuk view utama
        $rolesCollection = Role::with('permissions')->get();

        // Ambil data permissions untuk dikirim ke modal form
        $permissions = Permission::all()->groupBy(function($item, $key) {
            return explode('-', $item->name)[0];
        });

        // Kirim semua data yang dibutuhkan oleh view
        return view('livewire.management.role-permission-management', [
            'roles' => $rolesCollection->toArray(),
            'permissions' => $permissions
        ])->layout('layouts.app');
    }
}
