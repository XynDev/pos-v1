<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Penjualan #{{ $sale->invoice_number }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Header Detail -->
                    <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">INVOICE</h3>
                                <p class="text-gray-600">{{ $sale->invoice_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @switch($sale->status)
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('refunded') bg-yellow-100 text-yellow-800 @break
                                    @case('void') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst($sale->status) }}
                            </span>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Transaksi</p>
                                <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($sale->created_at)->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kasir</p>
                                <p class="mt-1 text-gray-900">{{ $sale->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                                <p class="mt-1 text-gray-900">{{ $sale->customer->name ?? 'Umum' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Rincian Item -->
                    <div class="px-6 sm:px-10 pb-6">
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-800">Rincian Produk</h4>
                            <div class="overflow-x-auto mt-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sale->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Pembayaran -->
                    <div class="px-6 sm:px-10 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="w-full md:w-1/2 ml-auto">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Subtotal</span>
                                <span class="text-sm text-gray-800">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mt-2 pt-2 border-t">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <span class="text-lg font-bold text-gray-800">Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm text-gray-600">Metode Bayar</span>
                                <span class="text-sm text-gray-800">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm text-gray-600">Jumlah Bayar</span>
                                <span class="text-sm text-gray-800">Rp {{ number_format($sale->amount_paid, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm text-gray-600">Kembalian</span>
                                <span class="text-sm text-gray-800">Rp {{ number_format($sale->change_due, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="p-6 bg-white text-right space-x-4 border-t">
                        <a href="{{ route('sales.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                            Kembali ke Laporan
                        </a>
                        <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Cetak Struk
                        </a>
                        @if($sale->status == 'completed' && auth()->user()->can('process-refunds'))
                            <button wire:click="openRefundModal" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Proses Refund
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($isRefundModalOpen)
            @include('livewire.sale.sale-refund-modal')
        @endif
    </div>
</div>
