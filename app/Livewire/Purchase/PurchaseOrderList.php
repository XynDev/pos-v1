<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase\PurchaseOrders;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderList extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public function render()
    {
        $purchaseOrders = PurchaseOrders::with(['supplier', 'user'])
            ->where('po_number', 'like', '%'.$this->search.'%')
            ->orWhereHas('supplier', function($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest() // Menampilkan yang terbaru di atas
            ->paginate($this->perPage);

        return view('livewire.purchase.purchase-order-list', [
            'purchaseOrders' => $purchaseOrders
        ])->layout('layouts.app');
    }
}
