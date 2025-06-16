@php use Carbon\Carbon; @endphp
<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Detail Pesanan #{{ $purchaseOrder->po_number }}

            <x-slot name="actions">
                <a href="{{ route('purchases.orders') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Kembali
                </a>
                @if($purchaseOrder->status == 'pending')
                    <button wire:click="cancelOrder" wire:confirm="Anda yakin ingin membatalkan pesanan ini?"
                            class="btn bg-red-600 hover:bg-red-700 text-white">
                        Batalkan PO
                    </button>
                    <button wire:click="receiveGoods"
                            wire:confirm="Anda yakin ingin menerima semua barang dari pesanan ini? Stok akan diperbarui."
                            class="btn bg-green-600 hover:bg-green-700 text-white">
                        Terima Barang
                    </button>
                @endif
            </x-slot>
        </x-page-header>

        @if (session()->has('message'))
            <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="font-bold">Sukses</p>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="font-bold">Gagal</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Detail Informasi</h2>
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @switch($purchaseOrder->status)
                                @case('pending') bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300 @break
                                @case('completed') bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300 @break
                                @case('cancelled') bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300 @break
                                @default bg-gray-100 dark:bg-gray-600/30 text-gray-800 dark:text-gray-300
                            @endswitch
                        ">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span>
                    </div>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemasok</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $purchaseOrder->supplier->name }}</p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $purchaseOrder->supplier->address }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pesan</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ Carbon::parse($purchaseOrder->order_date)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $purchaseOrder->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rincian Produk</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">SKU</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Kuantitas</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Harga</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Subtotal</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($purchaseOrder->items as $item)
                                <tr>
                                    <td class="p-2"><div class="font-mono text-gray-600 dark:text-gray-400">{{ $item->product->sku }}</div></td>
                                    <td class="p-2"><div class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name }}</div></td>
                                    <td class="p-2"><div class="text-center">{{ $item->quantity }}</div></td>
                                    <td class="p-2"><div class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</div></td>
                                    <td class="p-2"><div class="text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <td colspan="4" class="p-2 text-right text-sm font-bold text-gray-700 dark:text-gray-200 uppercase">Total Keseluruhan</td>
                                <td class="p-2 text-right text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
