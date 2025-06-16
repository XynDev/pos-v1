@php use Carbon\Carbon; @endphp
<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Riwayat Pelanggan

            <x-slot name="actions">
                <a href="{{ route('customers.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Kembali ke Daftar Pelanggan
                </a>
            </x-slot>
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-start justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Profil Pelanggan</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Poin Loyalitas</p>
                        <p class="text-2xl font-bold text-yellow-500">{{ number_format($customer->points, 0, ',', '.') }} Poin</p>
                    </div>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Transaksi</h2>
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
                                    <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="6">
                                        Pelanggan ini belum memiliki riwayat transaksi.
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
