<?php

namespace App\Livewire\Management;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public $userId, $name, $email, $password, $password_confirmation;
    public array $roles = [];
    public array $selectedRoles = [];
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId)
            ],
            'selectedRoles' => 'required|array|min:1',
        ];

        if (!$this->isEditMode) {
            // Tambahkan aturan password hanya saat membuat user baru
            $rules['password'] = 'required|string|min:8|confirmed';
        } elseif (!empty($this->password)) {
            // Tambahkan aturan password jika diisi saat edit
            $rules['password'] = 'sometimes|string|min:8|confirmed';
        }

        return $rules;
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->roles = Role::all()->pluck('name')->toArray();
        $this->openModal();
    }

    public function edit($id): void
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Kosongkan password
        $this->password_confirmation = '';

        $this->roles = Role::all()->pluck('name')->toArray();
        $this->selectedRoles = $user->getRoleNames()->toArray();

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $userData);
        $user->syncRoles($this->selectedRoles);

        session()->flash('message', $this->userId ? 'User berhasil diupdate.' : 'User berhasil dibuat.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id): void
    {
        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus.');
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
    }

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        return view('livewire.management.user-management', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
