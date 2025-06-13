<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pesanan Pembelian (PO)') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    @if (session()->has('message'))
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    @endif

                    <!-- Tombol Buat PO Baru -->
                    <a href="{{ route('purchases.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Buat PO Baru
                    </a>

                    <div class="mb-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan No. PO atau Nama Pemasok..."
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No. PO</th>
                                <th class="px-4 py-2">Pemasok</th>
                                <th class="px-4 py-2">Tanggal Pesan</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($purchaseOrders as $po)
                                <tr>
                                    <td class="border px-4 py-2 font-mono">{{ $po->po_number }}</td>
                                    <td class="border px-4 py-2">{{ $po->supplier->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                    <td class="border px-4 py-2 text-right">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($po->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('completed') bg-green-100 text-green-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <a href="{{ route('purchases.show', $po->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="6">Belum ada Pesanan Pembelian.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $purchaseOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
