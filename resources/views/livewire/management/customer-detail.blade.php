@php use Carbon\Carbon; @endphp
<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Pelanggan: {{ $customer->name }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Card Profil Pelanggan -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                    <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start">
                        <h3 class="text-xl font-semibold text-gray-800">Profil Pelanggan</h3>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500">Poin Loyalitas</p>
                                <p class="text-2xl font-bold text-yellow-500">{{ number_format($customer->points, 0, ',', '.') }} Poin</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama</p>
                                <p class="mt-1 text-gray-900">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="mt-1 text-gray-900">{{ $customer->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Telepon</p>
                                <p class="mt-1 text-gray-900">{{ $customer->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Alamat</p>
                                <p class="mt-1 text-gray-900">{{ $customer->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat Transaksi -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Transaksi</h3>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Invoice</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Kasir</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="border px-4 py-2 font-mono">{{ $sale->invoice_number }}</td>
                                    <td class="border px-4 py-2">{{ Carbon::parse($sale->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="border px-4 py-2">{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($sale->status)
                                            @case('completed') bg-green-100 text-green-800 @break
                                            @case('refunded') bg-yellow-100 text-yellow-800 @break
                                            @case('void') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <a href="{{ route('sales.show', $sale->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="6">Pelanggan ini belum memiliki
                                        riwayat transaksi.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <a href="{{ route('customers.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                        Kembali ke Daftar Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
