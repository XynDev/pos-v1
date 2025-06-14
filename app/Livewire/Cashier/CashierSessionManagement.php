<?php

namespace App\Livewire\Cashier;

use App\Models\Cashier\CashierSession;
use App\Models\Sale\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CashierSessionManagement extends Component
{
    use WithPagination;

    public bool $isStartModalOpen = false;
    public bool $isEndModalOpen = false;

    public $start_balance;
    public $end_balance;
    public $notes;

    public ?CashierSession $activeSession;
    public $calculated_sales = 0;
    public $difference = 0;

    public function mount(): void
    {
        $this->activeSession = CashierSession::where('status', 'open')->first();
    }

    public function openStartModal(): void
    {
        $this->reset(['start_balance']);
        $this->isStartModalOpen = true;
    }

    public function startSession(): void
    {
        $this->validate(['start_balance' => 'required|numeric|min:0']);

        if (CashierSession::where('status', 'open')->exists()) {
            session()->flash('error', 'Tidak bisa memulai sesi baru. Masih ada sesi yang aktif.');
            return;
        }

        $session = CashierSession::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'start_balance' => $this->start_balance,
            'status' => 'open',
        ]);

        $this->activeSession = $session;
        $this->isStartModalOpen = false;
        session()->flash('message', 'Sesi kasir berhasil dimulai.');
    }

    public function openEndModal(): void
    {
        if ($this->activeSession) {
            $this->calculated_sales = Sale::where('payment_method', 'cash')
                ->where('created_at', '>=', $this->activeSession->start_time)
                ->sum('final_amount');

            $this->reset(['end_balance', 'notes']);
            $this->isEndModalOpen = true;
        }
    }

    public function updatedEndBalance(): void
    {
        $endBalance = (int) $this->end_balance;
        $startBalance = (int) $this->activeSession->start_balance;
        $cashSales = (int) $this->calculated_sales;

        $this->difference = $endBalance - ($startBalance + $cashSales);
    }

    public function endSession(): void
    {
        $this->validate(['end_balance' => 'required|numeric|min:0']);

        if ($this->activeSession) {
            $this->activeSession->update([
                'end_time' => now(),
                'end_balance' => $this->end_balance,
                'calculated_sales' => $this->calculated_sales,
                'difference' => $this->difference,
                'status' => 'closed',
                'notes' => $this->notes,
            ]);

            $this->activeSession = null;
            $this->isEndModalOpen = false;
            session()->flash('message', 'Sesi kasir berhasil ditutup.');
        }
    }

    public function render()
    {
        $closedSessions = CashierSession::where('status', 'closed')
            ->latest()
            ->paginate(10);

        return view('livewire.cashier.cashier-session-management', [
            'closedSessions' => $closedSessions,
        ])->layout('layouts.app');
    }
}
