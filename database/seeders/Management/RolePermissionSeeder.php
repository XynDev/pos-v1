<?php

namespace Database\Seeders\Management;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ----------------------------------------------------------------
        // BUAT PERMISSIONS
        // ----------------------------------------------------------------
        $permissions = [
            // Dashboard
            'view-dashboard',

            // Manajemen Penjualan (Kasir)
            'access-cashier',
            'create-sales',
            'hold-transactions',
            'process-refunds',
            'view-sales-history',
            'manage-cashier-sessions',

            // Manajemen Produk
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-categories',
            'manage-brands',
            'manage-product-variants',
            'manage-products',
            'manage-bundle-products',

            // Manajemen Inventaris
            'view-inventory',
            'manage-stock-opname',
            'manage-stock-transfers',
            'view-stock-cards',

            // Manajemen Pelanggan (CRM)
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'manage-loyalty-program',
            'manage-customers',

            // Manajemen Pemasok (Supplier)
            'manage-suppliers',
            'view-suppliers',
            'create-suppliers',
            'edit-suppliers',
            'delete-suppliers',
            'create-purchase-orders',
            'manage-goods-receipt',

            // Manajemen Karyawan & Akses (Fitur Super Admin)
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-roles-permissions',

            // Laporan & Analitik
            'view-sales-reports',
            'view-profit-loss-reports',
            'view-product-analysis-reports',
            'view-employee-performance-reports',

            // Setting Application
            'manage-settings',
            'manage-transfer-stock',
        ];

        foreach ($permissions as $permission) {
            // Cek jika permission sudah ada, jika belum maka buat
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ----------------------------------------------------------------
        // BUAT ROLES DAN BERIKAN PERMISSIONS
        // ----------------------------------------------------------------

        // Role: Super Admin (Bisa segalanya)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Role: Manajer (Akses manajerial)
        $managerRole = Role::firstOrCreate(['name' => 'Manajer']);
        $managerRole->syncPermissions([
            'view-dashboard',
            'view-sales-history',
            'manage-cashier-sessions',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'manage-categories', 'manage-brands', 'manage-product-variants', 'manage-bundle-products',
            'view-inventory', 'manage-stock-opname', 'manage-stock-transfers', 'view-stock-cards',
            'view-customers', 'create-customers', 'edit-customers', 'delete-customers', 'manage-loyalty-program',
            'view-suppliers', 'create-suppliers', 'edit-suppliers', 'delete-suppliers', 'create-purchase-orders', 'manage-goods-receipt',
            'view-users', 'create-users', 'edit-users',
            'view-sales-reports', 'view-profit-loss-reports', 'view-product-analysis-reports', 'view-employee-performance-reports'
        ]);

        // Role: Kasir (Akses operasional penjualan)
        $cashierRole = Role::firstOrCreate(['name' => 'Kasir']);
        $cashierRole->syncPermissions([
            'view-dashboard',
            'access-cashier',
            'create-sales',
            'hold-transactions',
            'process-refunds',
            'view-sales-history',
            'view-products',
            'view-customers', 'create-customers', 'edit-customers',
        ]);
    }
}
