<?php

namespace App\Livewire\Report;

use App\Models\Stock\MovementStock;
use Livewire\Component;
use Livewire\WithPagination;

class StockAdjustmentReport extends Component
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
        $adjustments = MovementStock::with(['product', 'user'])
            ->where('type', 'adjustment')
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->latest()
            ->paginate(15);

        return view('livewire.report.stock-adjustment-report', [
            'adjustments' => $adjustments,
        ])->layout('layouts.app');
    }
}
