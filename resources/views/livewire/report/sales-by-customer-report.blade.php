<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Laporan Penjualan per Pelanggan
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
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

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hasil Laporan</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Nama Pelanggan</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Telepon</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jumlah Transaksi</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Total Belanja</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($salesByCustomer as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $data->customer_name }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-gray-600 dark:text-gray-400">{{ $data->customer_phone }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-center">{{ $data->total_transactions }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right font-bold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($data->total_spent, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="flex justify-center">
                                            <a href="{{ route('customers.show', $data->customer_id) }}" class="btn-sm bg-indigo-500 hover:bg-indigo-600 text-white">Lihat Riwayat</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="5">
                                        Tidak ada data penjualan pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-3">
                        {{ $salesByCustomer->links('components.pagination-numeric') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
