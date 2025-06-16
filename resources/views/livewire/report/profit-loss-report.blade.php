@php use Carbon\Carbon; @endphp
<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Laporan Laba Rugi
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Filter Laporan</h2>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" id="startDate" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                            <input type="date" wire:model.live="endDate" id="endDate" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hasil Laporan</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Invoice</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jml</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Harga Jual</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Harga Beli</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Laba Kotor</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($saleItems as $item)
                                @php
                                    $purchasePrice = $item->product->purchase_price ?? 0;
                                    $profit = ($item->price - $purchasePrice) * $item->quantity;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div>{{ Carbon::parse($item->sale->created_at)->format('d M Y') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <a href="{{ route('sales.show', $item->sale_id) }}" class="font-mono text-indigo-500 hover:underline">{{ $item->sale->invoice_number }}</a>
                                    </td>
                                    <td class="p-2">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-center">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right">Rp {{ number_format($purchasePrice, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right font-bold {{ $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            Rp {{ number_format($profit, 0, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="7">
                                        Tidak ada data penjualan pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if($saleItems->isNotEmpty())
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                                <tr>
                                    <td class="p-2 text-right text-gray-700 dark:text-gray-200" colspan="6">Total Laba Kotor (Halaman Ini)</td>
                                    <td class="p-2 text-right text-green-700 dark:text-green-500">
                                        Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                    </td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div class="mt-4 px-3">
                        {{ $saleItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
