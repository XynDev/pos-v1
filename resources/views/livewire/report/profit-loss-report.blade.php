@php use Carbon\Carbon; @endphp
<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Laba Rugi') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">

                    <!-- Filter Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 border rounded-lg">
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
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Invoice</th>
                                <th class="px-4 py-2 text-left">Produk</th>
                                <th class="px-4 py-2 text-center">Jml</th>
                                <th class="px-4 py-2 text-right">Harga Jual</th>
                                <th class="px-4 py-2 text-right">Harga Beli</th>
                                <th class="px-4 py-2 text-right">Laba Kotor</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($saleItems as $item)
                                @php
                                    $purchasePrice = $item->product->purchase_price ?? 0;
                                    $profit = ($item->price - $purchasePrice) * $item->quantity;
                                @endphp
                                <tr>
                                    <td class="border px-4 py-2 text-sm">{{ Carbon::parse($item->sale->created_at)->format('d M Y') }}</td>
                                    <td class="border px-4 py-2 font-mono text-sm">
                                        <a href="{{ route('sales.show', $item->sale_id) }}"
                                           class="text-indigo-600 hover:underline">{{ $item->sale->invoice_number }}</a>
                                    </td>
                                    <td class="border px-4 py-2">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($purchasePrice, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-right font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($profit, 0, ',', '.') }}
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
                            <tfoot class="bg-gray-100 font-bold">
                            <tr>
                                <td class="border px-4 py-3 text-right" colspan="6">Total Laba Kotor (Halaman Ini)</td>
                                <td class="border px-4 py-3 text-right text-green-700">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $saleItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
