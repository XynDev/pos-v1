<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Laporan Penyesuaian Stok
        </x-page-header>

        @if (session()->has('message'))
            <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

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
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jumlah Penyesuaian</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Stok Akhir</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Alasan</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Oleh</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($adjustments as $adjustment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($adjustment->created_at)->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $adjustment->product->name ?? 'Produk Dihapus' }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $adjustment->product->sku ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <div class="font-bold {{ $adjustment->quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $adjustment->quantity > 0 ? '+' : '' }}{{ $adjustment->quantity }}
                                        </div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <div class="font-bold text-gray-800 dark:text-gray-100">{{ $adjustment->stock_after }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="italic text-gray-600 dark:text-gray-400">"{{ $adjustment->notes }}"</div>
                                    </td>
                                    <td class="p-2">
                                        <div>{{ $adjustment->user->name ?? 'Sistem' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="6">
                                        Tidak ada riwayat penyesuaian stok pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-3">
                        {{ $adjustments->links('components.pagination-numeric') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
