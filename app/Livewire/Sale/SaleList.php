<?php

namespace App\Livewire\Sale;

use App\Models\Sale\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class SaleList extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $startDate;
    public string $endDate;

    public function mount(): void
    {
        // Set default rentang tanggal ke bulan ini
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function applyFilter()
    {
        // Method ini dipanggil saat tombol filter ditekan.
        // Cukup dengan adanya properti public, Livewire akan otomatis
        // me-render ulang dengan data baru saat nilainya berubah.
        $this->resetPage(); // Reset paginasi ke halaman pertama
    }

    public function render()
    {
        $sales = Sale::with(['user', 'customer'])
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->where(function ($query) {
                $query->where('invoice_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', function ($subQuery) {
                        $subQuery->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.sale.sale-list', [
            'sales' => $sales
        ])->layout('layouts.app');
    }
}
