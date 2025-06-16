@php use Carbon\Carbon; @endphp
<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Detail Penjualan #{{ $sale->invoice_number }}

            <x-slot name="actions">
                <a href="{{ route('sales.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Kembali ke Laporan
                </a>
                <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    Cetak Struk
                </a>
                @if($sale->status == 'completed' && auth()->user()->can('process-refunds'))
                    <button wire:click="openRefundModal" class="btn bg-yellow-500 hover:bg-yellow-600 text-white">
                        Proses Refund
                    </button>
                @endif
            </x-slot>
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Transaksi</h2>
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
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
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transaksi</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($sale->created_at)->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kasir</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $sale->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $sale->customer->name ?? 'Umum' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rincian Produk & Pembayaran</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Kuantitas</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Harga</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Subtotal</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($sale->items as $item)
                                <tr>
                                    <td class="p-2"><div class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name }}</div></td>
                                    <td class="p-2"><div class="text-center">{{ $item->quantity }}</div></td>
                                    <td class="p-2"><div class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</div></td>
                                    <td class="p-2"><div class="text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-5 pb-5 pt-3">
                    <div class="w-full md:w-1/2 ml-auto space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Subtotal</span>
                            <span class="text-sm text-gray-800 dark:text-gray-200">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-100">Total</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-dashed border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Metode Bayar</span>
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Bayar</span>
                            <span class="text-sm text-gray-800 dark:text-gray-200">Rp {{ number_format($sale->amount_paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Kembalian</span>
                            <span class="text-sm text-gray-800 dark:text-gray-200">Rp {{ number_format($sale->change_due, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isRefundModalOpen)
            @include('livewire.sale.sale-refund-modal')
        @endif
    </div>
</div>
