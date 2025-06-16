@php use Carbon\Carbon; @endphp
<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Laporan Penjualan
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Filter Laporan</h2>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" id="startDate" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                            <input type="date" wire:model.live="endDate" id="endDate" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Cari Invoice / Kasir</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search" id="search" placeholder="Ketik nomor invoice atau nama kasir..." class="form-input w-full pl-9 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-3">
                                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                            </div>
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
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Invoice</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Kasir</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Total</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Metode Bayar</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Status</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div class="font-mono text-gray-800 dark:text-gray-100">{{ $sale->invoice_number }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div>{{ Carbon::parse($sale->created_at)->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div>{{ $sale->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right font-medium">Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <div>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($sale->status)
                                                    @case('completed') bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300 @break
                                                    @case('refunded') bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300 @break
                                                    @case('void') bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300 @break
                                                    @default bg-gray-100 dark:bg-gray-600/30 text-gray-800 dark:text-gray-300
                                                @endswitch
                                            ">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="flex justify-center">
                                            <a href="{{ route('sales.show', $sale->id) }}" class="btn-sm bg-indigo-500 hover:bg-indigo-600 text-white">Detail</a>
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
                        </table>
                    </div>
                    <div class="mt-4 px-3">
                        {{ $sales->links('components.pagination-numeric') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
