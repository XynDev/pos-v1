<?php

namespace App\Livewire\Report;

use App\Models\Sale\SaleItem;
use Livewire\Component;
use Livewire\WithPagination;

class ProfitLossReport extends Component
{
    use WithPagination;

    public string $startDate;
    public string $endDate;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $saleItems = SaleItem::with(['product', 'sale'])
            ->whereHas('sale', function ($query) {
                $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
            })
            ->latest()
            ->paginate(15);

        $totalProfit = $saleItems->sum(function ($item) {
            $purchasePrice = $item->product->purchase_price ?? 0;
            return ($item->price - $purchasePrice) * $item->quantity;
        });

        return view('livewire.report.profit-loss-report', [
            'saleItems' => $saleItems,
            'totalProfit' => $totalProfit
        ])->layout('layouts.app');
    }
}
