<?php

namespace App\Livewire\Report;

use App\Models\ManagementProduct\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesByCategoryReport extends Component
{
    use WithPagination;

    public string $startDate;
    public string $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $salesByCategory = Category::query()
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity) as total_items_sold'),
                DB::raw('SUM(sale_items.subtotal) as total_sales')
            )
            ->whereBetween('sales.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->paginate(15);

        return view('livewire.report.sales-by-category-report', [
            'salesByCategory' => $salesByCategory,
        ])->layout('layouts.app');
    }
}
