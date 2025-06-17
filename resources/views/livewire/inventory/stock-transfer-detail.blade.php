<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Detail Transfer Stok #{{ $transfer->transfer_number }}

            <x-slot name="actions">
                <a href="{{ route('inventory.transfers.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Kembali ke Daftar Transfer
                </a>
            </x-slot>
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Transfer</h2>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($transfer->status == 'completed') bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300
                            @else bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300 @endif
                        ">
                            {{ ucfirst($transfer->status) }}
                        </span>
                    </div>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dari Lokasi</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $transfer->fromLocation->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ke Lokasi</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $transfer->toLocation->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $transfer->user->name }}</p>
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</p>
                            <p class="mt-1 text-gray-700 dark:text-gray-300 italic">"{{ $transfer->notes ?? '-' }}"</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rincian Produk yang Ditransfer</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">SKU</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jumlah Ditransfer</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($transfer->items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div class="font-mono text-gray-600 dark:text-gray-400">{{ $item->product->sku ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-center font-bold text-gray-800 dark:text-gray-100">{{ $item->quantity }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
