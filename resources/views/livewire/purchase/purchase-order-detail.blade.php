@php use Carbon\Carbon; @endphp
<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan #{{ $purchaseOrder->po_number }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if (session()->has('message'))
                    <div
                        class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-6"
                        role="alert">
                        <p class="font-bold">Sukses</p>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-6"
                         role="alert">
                        <p class="font-bold">Gagal</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Header Detail PO -->
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Pesanan Pembelian</h3>
                                <p class="text-gray-600">{{ $purchaseOrder->po_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @switch($purchaseOrder->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst($purchaseOrder->status) }}
                            </span>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pemasok</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $purchaseOrder->supplier->name }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ $purchaseOrder->supplier->address }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pesan</p>
                                <p class="mt-1 text-lg text-gray-900">{{ Carbon::parse($purchaseOrder->order_date)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Dibuat Oleh</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $purchaseOrder->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Item -->
                    <div class="px-6 sm:px-20 pb-6">
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-800">Rincian Produk</h4>
                            <div class="overflow-x-auto mt-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            SKU
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchaseOrder->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->sku }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">Total
                                            Keseluruhan
                                        </td>
                                        <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                                            Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="p-6 bg-gray-50 text-right space-x-4">
                        <a href="{{ route('purchases.orders') }}"
                           class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                        @if($purchaseOrder->status == 'pending')
                            <button wire:click="cancelOrder" wire:confirm="Anda yakin ingin membatalkan pesanan ini?"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Batalkan PO
                            </button>
                            <button wire:click="receiveGoods"
                                    wire:confirm="Anda yakin ingin menerima semua barang dari pesanan ini? Stok akan diperbarui."
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Terima Barang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
