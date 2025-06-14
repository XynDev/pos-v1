<?php

namespace App\Livewire\Dashboard;

use App\Models\Crm\Customer;
use App\Models\DataFeed;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $dataFeed = '';
    public int $dailySales = 0;
    public int $dailyTransactions = 0;
    public int $monthlyNewCustomers = 0;
    public ?object $bestSellingProduct = null;

    // Properti untuk Grafik
    public array $salesChartData = [];

    public function mount(): void
    {
        $this->loadStats();
        $this->prepareSalesChartData();
    }

    public function loadStats(): void
    {
        $this->dataFeed = new DataFeed();

        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');

        // Penjualan & Transaksi Hari Ini
        $salesToday = Sale::whereDate('created_at', $today)->get();
        $this->dailySales = $salesToday->sum('final_amount');
        $this->dailyTransactions = $salesToday->count();

        // Pelanggan Baru Bulan Ini
        $this->monthlyNewCustomers = Customer::whereDate('created_at', '>=', $startOfMonth)->count();

        // Produk Terlaris Bulan Ini
        $this->bestSellingProduct = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', function ($query) use ($startOfMonth) {
                $query->whereDate('created_at', '>=', $startOfMonth);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product')
            ->first();
    }

    public function prepareSalesChartData(): void
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(final_amount) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[] = $salesData->get($formattedDate)->total ?? 0;
        }

        $this->salesChartData = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
