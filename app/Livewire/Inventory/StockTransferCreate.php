<?php

namespace App\Livewire\Inventory;

use App\Models\Branch\Location;
use App\Models\Inventory\StockTransfer;
use App\Models\ManagementProduct\Product;
use App\Models\Stock\MovementStock;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockTransferCreate extends Component
{
    public $from_location_id;
    public $to_location_id;
    public $transfer_date;
    public $notes;

    public array $transferItems = [];
    public string $searchQuery = '';
    public $searchResults = [];

    public $locations;

    protected function rules(): array
    {
        return [
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id|different:from_location_id',
            'transfer_date' => 'required|date',
            'transferItems' => 'required|array|min:1',
            'transferItems.*.quantity' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'to_location_id.different' => 'Lokasi asal dan tujuan tidak boleh sama.',
        'transferItems.required' => 'Setidaknya harus ada satu produk untuk ditransfer.',
    ];

    public function mount(): void
    {
        $this->locations = Location::where('is_active', true)->get();
        $this->transfer_date = now()->format('Y-m-d');
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) >= 2 && $this->from_location_id) {
            $this->searchResults = Product::whereIn('type', ['simple', 'variant'])
                ->whereHas('locations', function ($query) {
                    $query->where('location_id', $this->from_location_id)->where('stock', '>', 0);
                })
                ->where('name', 'like', '%' . $this->searchQuery . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addProduct(Product $product): void
    {
        if (!isset($this->transferItems[$product->id])) {
            $stockAtLocation = $product->locations()->where('location_id', $this->from_location_id)->first()->pivot->stock;

            $this->transferItems[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'stock_at_source' => $stockAtLocation,
                'quantity' => 1,
            ];
        }
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function removeProduct($productId): void
    {
        unset($this->transferItems[$productId]);
    }

    public function saveTransfer()
    {
        $this->validate();

        try {
            DB::transaction(function() {
                $transfer = StockTransfer::create([
                    'transfer_number' => 'TRF-' . now()->timestamp,
                    'from_location_id' => $this->from_location_id,
                    'to_location_id' => $this->to_location_id,
                    'user_id' => Auth::id(),
                    'transfer_date' => $this->transfer_date,
                    'status' => 'completed',
                    'notes' => $this->notes,
                ]);

                $fromLocation = Location::find($this->from_location_id);
                $toLocation = Location::find($this->to_location_id);

                foreach ($this->transferItems as $item) {
                    $product = Product::find($item['product_id']);
                    $quantity = $item['quantity'];

                    $currentStockFrom = $product->locations()->where('location_id', $this->from_location_id)->first()->pivot->stock;
                    $newStockFrom = $currentStockFrom - $quantity;
                    $product->locations()->updateExistingPivot($this->from_location_id, ['stock' => $newStockFrom]);
                    MovementStock::create(['product_id' => $product->id, 'type' => 'transfer_out', 'quantity' => -$quantity, 'stock_after' => $newStockFrom, 'notes' => 'Transfer ke ' . $toLocation->name, 'user_id' => Auth::id()]);

                    $currentStockTo = $product->locations()->where('location_id', $this->to_location_id)->first()->pivot->stock ?? 0;
                    $newStockTo = $currentStockTo + $quantity;
                    $product->locations()->syncWithoutDetaching([$this->to_location_id => ['stock' => $newStockTo]]);
                    MovementStock::create(['product_id' => $product->id, 'type' => 'transfer_in', 'quantity' => $quantity, 'stock_after' => $newStockTo, 'notes' => 'Transfer dari ' . $fromLocation->name, 'user_id' => Auth::id()]);

                    $transfer->items()->create(['product_id' => $product->id, 'quantity' => $quantity]);
                }
            });

            session()->flash('message', 'Transfer stok berhasil dibuat dan stok telah diperbarui.');
            return redirect()->route('inventory.transfers.index');

        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inventory.stock-transfer-create')->layout('layouts.app');
    }
}
