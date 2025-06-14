<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Penyesuaian Stok (Stock Opname)') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">

                    <!-- Filter Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 border rounded-lg">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" id="startDate" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" wire:model.live="endDate" id="endDate" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Produk</th>
                                <th class="px-4 py-2 text-center">Jumlah Penyesuaian</th>
                                <th class="px-4 py-2 text-center">Stok Akhir</th>
                                <th class="px-4 py-2 text-left">Alasan</th>
                                <th class="px-4 py-2 text-left">Oleh</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($adjustments as $adjustment)
                                <tr>
                                    <td class="border-t px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($adjustment->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="border-t px-4 py-2">
                                        <p class="font-semibold">{{ $adjustment->product->name ?? 'Produk Dihapus' }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $adjustment->product->sku ?? 'N/A' }}</p>
                                    </td>
                                    <td class="border-t px-4 py-2 text-center font-bold {{ $adjustment->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $adjustment->quantity > 0 ? '+' : '' }}{{ $adjustment->quantity }}
                                    </td>
                                    <td class="border-t px-4 py-2 text-center font-bold">{{ $adjustment->stock_after }}</td>
                                    <td class="border-t px-4 py-2 text-sm italic text-gray-600">"{{ $adjustment->notes }}"</td>
                                    <td class="border-t px-4 py-2 text-sm">{{ $adjustment->user->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border-t px-4 py-2 text-center" colspan="6">Tidak ada riwayat penyesuaian stok pada rentang tanggal ini.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $adjustments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
