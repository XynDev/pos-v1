<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Sesi Kasir') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session()->has('message'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Sesi Aktif</h3>
                    @if($activeSession)
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Dimulai Oleh</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $activeSession->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Waktu Mulai</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ $activeSession->start_time->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Modal Awal</p>
                                <p class="mt-1 font-semibold text-gray-900">Rp {{ number_format($activeSession->start_balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <button wire:click="openEndModal" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Tutup Sesi
                            </button>
                        </div>
                    @else
                        <div class="mt-4 text-center text-gray-500">
                            <p>Tidak ada sesi yang sedang aktif.</p>
                            <button wire:click="openStartModal" class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Mulai Sesi Baru
                            </button>
                        </div>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Sesi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Kasir</th>
                                <th class="px-4 py-2 text-left">Waktu Mulai</th>
                                <th class="px-4 py-2 text-left">Waktu Selesai</th>
                                <th class="px-4 py-2 text-right">Selisih</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($closedSessions as $session)
                                <tr>
                                    <td class="border-t px-4 py-2">{{ $session->user->name }}</td>
                                    <td class="border-t px-4 py-2">{{ $session->start_time->format('d/m/y H:i') }}</td>
                                    <td class="border-t px-4 py-2">{{ $session->end_time->format('d/m/y H:i') }}</td>
                                    <td class="border-t px-4 py-2 text-right font-bold {{ $session->difference == 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($session->difference, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 border-t">Belum ada riwayat sesi.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $closedSessions->links() }}
                    </div>
                </div>
            </div>
        </div>

        @if($isStartModalOpen)
            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="px-4 pt-5 pb-4 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900">Mulai Sesi Kasir Baru</h3>
                            <div class="mt-4">
                                <label for="start_balance" class="block text-sm font-medium text-gray-700">Modal Awal (Rp)</label>
                                <input type="number" wire:model="start_balance" id="start_balance" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('start_balance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="startSession" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Mulai</button>
                            <button wire:click="$set('isStartModalOpen', false)" class="mr-2 bg-gray-200 font-bold py-2 px-4 rounded">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($isEndModalOpen)
            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="px-4 pt-5 pb-4 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900">Tutup Sesi Kasir</h3>
                            <div class="mt-4 space-y-4">
                                <div class="p-3 bg-gray-100 rounded">
                                    <p class="text-sm">Modal Awal: Rp {{ number_format($activeSession->start_balance, 0, ',', '.') }}</p>
                                    <p class="text-sm">Penjualan Tunai (Sistem): Rp {{ number_format($calculated_sales, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold">Total Uang Seharusnya: Rp {{ number_format($activeSession->start_balance + $calculated_sales, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label for="end_balance" class="block text-sm font-medium text-gray-700">Uang Fisik di Laci Kas (Rp)</label>
                                    <input type="number" wire:model.live="end_balance" id="end_balance" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('end_balance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="p-3 rounded {{ $difference == 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                    <p class="text-sm font-bold">Selisih: Rp {{ number_format($difference, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                    <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="endSession" class="bg-red-600 text-white font-bold py-2 px-4 rounded">Tutup Sesi</button>
                            <button wire:click="$set('isEndModalOpen', false)" class="mr-2 bg-gray-200 font-bold py-2 px-4 rounded">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
