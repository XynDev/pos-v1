<?php

namespace App\Livewire\Report;

use App\Models\Crm\Customer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesByCustomerReport extends Component
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
        $salesByCustomer = Customer::query()
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                'customers.phone as customer_phone',
                DB::raw('COUNT(sales.id) as total_transactions'),
                DB::raw('SUM(sales.final_amount) as total_spent')
            )
            ->whereBetween('sales.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->groupBy('customers.id', 'customers.name', 'customers.phone')
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return view('livewire.report.sales-by-customer-report', [
            'salesByCustomer' => $salesByCustomer,
        ])->layout('layouts.app');
    }
}
