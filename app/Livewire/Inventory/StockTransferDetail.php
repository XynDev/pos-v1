<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory\StockTransfer;
use Livewire\Component;

class StockTransferDetail extends Component
{
    public StockTransfer $transfer;

    public function mount(StockTransfer $transfer)
    {
        $this->transfer = $transfer->load(['fromLocation', 'toLocation', 'user', 'items.product']);
    }

    public function render()
    {
        return view('livewire.inventory.stock-transfer-detail')->layout('layouts.app');
    }
}
