<?php

use App\Livewire\Management\RolePermissionManagement;
use App\Livewire\Management\UserManagement;
use App\Livewire\Product\BrandProduct;
use App\Livewire\Product\CategoryProduct;
use App\Livewire\Product\ListProduct;
use App\Livewire\Supplier\ManagementSupplier;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:Super Admin'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/user-management', UserManagement::class)->name('user.management');
    Route::get('/role-permission-management', RolePermissionManagement::class)->name('role.permission.management');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/categories', CategoryProduct::class)->name('categories.product')->middleware('permission:manage-categories');
    Route::get('/brands', BrandProduct::class)->name('brands.product')->middleware('permission:manage-brands');
    Route::get('/products', ListProduct::class)->name('list.product')->middleware('permission:manage-product-variants');

    Route::get('/suppliers', ManagementSupplier::class)->name('management.suppliers')->middleware('permission:manage-suppliers');
});
