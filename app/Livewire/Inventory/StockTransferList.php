<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory\StockTransfer;
use Livewire\Component;
use Livewire\WithPagination;

class StockTransferList extends Component
{
    use WithPagination;

    public function render()
    {
        $transfers = StockTransfer::with(['fromLocation', 'toLocation', 'user'])
            ->latest()
            ->paginate(10);

        return view('livewire.inventory.stock-transfer-list', [
            'transfers' => $transfers,
        ])->layout('layouts.app');
    }
}
