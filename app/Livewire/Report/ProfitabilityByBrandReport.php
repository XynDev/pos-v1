<?php

namespace App\Livewire\Report;

use App\Models\ManagementProduct\Brand;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProfitabilityByBrandReport extends Component
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
        $profitabilityByBrand = Brand::query()
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'brands.name as brand_name',
                DB::raw('SUM(sale_items.quantity) as total_items_sold'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue'),
                DB::raw('SUM((sale_items.price - products.purchase_price) * sale_items.quantity) as gross_profit')
            )
            ->whereBetween('sales.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('gross_profit', 'desc')
            ->paginate(15);

        return view('livewire.report.profitability-by-brand-report', [
            'profitabilityByBrand' => $profitabilityByBrand,
        ])->layout('layouts.app');
    }
}
