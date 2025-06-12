<?php

namespace App\Livewire\Management;

use App\Models\Crm\Customer;
use Livewire\Component;

class CustomerDetail extends Component
{
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        // Load relasi penjualan untuk ditampilkan
        $this->customer = $customer->load('sales.user');
    }

    public function render()
    {
        $sales = $this->customer->sales()->latest()->paginate(10);

        return view('livewire.management.customer-detail', [
            'sales' => $sales,
        ])->layout('layouts.app');
    }
}
