@php use Carbon\Carbon; @endphp
<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Penjualan') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">

                    <!-- Filter Section -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 border rounded-lg">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" id="startDate"
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" wire:model.live="endDate" id="endDate"
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Invoice /
                                Kasir</label>
                            <input type="text" wire:model.live.debounce.300ms="search" id="search" placeholder="Cari..."
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Invoice</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Kasir</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Metode Bayar</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="border px-4 py-2 font-mono">{{ $sale->invoice_number }}</td>
                                    <td class="border px-4 py-2">{{ Carbon::parse($sale->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</td>
                                    <td class="border px-4 py-2 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($sale->status)
                                            @case('completed') bg-green-100 text-green-800 @break
                                            @case('refunded') bg-yellow-100 text-yellow-800 @break
                                            @case('void') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="7">Tidak ada data penjualan pada
                                        rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
