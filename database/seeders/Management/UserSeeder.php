<?php

namespace Database\Seeders\Management;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Ambil role yang sudah dibuat dari RolePermissionSeeder
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $managerRole = Role::where('name', 'Manajer')->first();
        $cashierRole = Role::where('name', 'Kasir')->first();

        // ----------------------------------------------------------------
        // BUAT USER DEFAULT DAN BERIKAN ROLE
        // ----------------------------------------------------------------

        // Membuat user Super Admin
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin'),
            ]
        );
        $superAdminUser->assignRole($superAdminRole);

        // Membuat user Manajer
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('manager'),
            ]
        );
        $managerUser->assignRole($managerRole);

        // Membuat user Kasir
        $cashierUser = User::firstOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('cashier'),
            ]
        );
        $cashierUser->assignRole($cashierRole);
    }
}
