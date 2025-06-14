<?php

use App\Http\Controllers\Receipt\ReceiptController;
use App\Livewire\Cashier\Cashier;
use App\Livewire\Cashier\CashierSessionManagement;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Inventory\StockOpname;
use App\Livewire\Management\CustomerDetail;
use App\Livewire\Management\CustomerManagement;
use App\Livewire\Management\RolePermissionManagement;
use App\Livewire\Management\UserManagement;
use App\Livewire\Product\AttributeManagement;
use App\Livewire\Product\BrandProduct;
use App\Livewire\Product\CategoryProduct;
use App\Livewire\Product\ListProduct;
use App\Livewire\Product\ProductForm;
use App\Livewire\Purchase\PurchaseOrderCreate;
use App\Livewire\Purchase\PurchaseOrderDetail;
use App\Livewire\Purchase\PurchaseOrderList;
use App\Livewire\Report\ProfitLossReport;
use App\Livewire\Report\StockAdjustmentReport;
use App\Livewire\Report\StockCardReport;
use App\Livewire\Sale\SaleDetail;
use App\Livewire\Sale\SaleList;
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
    Route::get('/products', ListProduct::class)->name('products.index')->middleware('permission:manage-product-variants');
    Route::get('/products/create', ProductForm::class)->name('products.create')->middleware('permission:create-products');
    Route::get('/products/{productId}/edit', ProductForm::class)->name('products.edit')->middleware('permission:edit-products');

    Route::get('/product-attributes', AttributeManagement::class)->name('attributes.index')->middleware('permission:manage-products');

    Route::get('/suppliers', ManagementSupplier::class)->name('management.suppliers')->middleware('permission:manage-suppliers');
    Route::get('/purchases', PurchaseOrderList::class)->name('purchases.orders')->middleware('permission:create-purchase-orders');
    Route::get('/purchases/create', PurchaseOrderCreate::class)->name('purchases.create')->middleware('permission:create-purchase-orders');
    Route::get('/purchases/{purchaseOrder}', PurchaseOrderDetail::class)->name('purchases.show')->middleware('permission:create-purchase-orders');
    Route::get('/reports/stock-card', StockCardReport::class)->name('reports.stock-card')->middleware('permission:view-stock-cards');

    Route::get('/cashier', Cashier::class)->name('cashier.index')->middleware('permission:access-cashier');
    Route::get('/sales', SaleList::class)->name('sales.index')->middleware('permission:view-sales-reports');
    Route::get('/sales/{sale}', SaleDetail::class)->name('sales.show')->middleware('permission:view-sales-reports');

    Route::get('/sales/{sale}/print', [ReceiptController::class, 'showSaleReceipt'])->name('sales.print')->middleware('permission:view-sales-reports');
    Route::get('/customers', CustomerManagement::class)->name('customers.index')->middleware('permission:manage-customers');
    Route::get('/customers/{customer}', CustomerDetail::class)->name('customers.show')->middleware('permission:manage-customers');

    Route::get('/dashboard', Dashboard::class)->name('dashboard')->middleware(['auth', 'verified']);
    Route::get('/reports/profit-loss', ProfitLossReport::class)->name('reports.profit-loss')->middleware('permission:view-profit-loss-reports');
    Route::get('/sessions', CashierSessionManagement::class)->name('sessions.index')->middleware('permission:manage-cashier-sessions');

    Route::get('/inventory/stock-opname', StockOpname::class)->name('inventory.stock-opname')->middleware('permission:manage-stock-opname');
    Route::get('/reports/stock-adjustments', StockAdjustmentReport::class)->name('reports.stock-adjustments')->middleware('permission:manage-stock-opname');
});
