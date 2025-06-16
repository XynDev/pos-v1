<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Manajemen Sesi Kasir
        </x-page-header>

        @if (session()->has('message'))
            <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Sesi Aktif</h2>
                </header>
                <div class="p-5">
                    @if($activeSession)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dimulai Oleh</p>
                                <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">{{ $activeSession->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Mulai</p>
                                <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">{{ $activeSession->start_time->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Modal Awal</p>
                                <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($activeSession->start_balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <button wire:click="openEndModal" class="btn bg-red-600 hover:bg-red-700 text-white">
                                Tutup Sesi
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-200">Tidak ada sesi yang sedang aktif.</h3>
                            <div class="mt-6">
                                <button wire:click="openStartModal" class="btn bg-green-600 hover:bg-green-700 text-white">
                                    Mulai Sesi Baru
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Sesi</h2>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Kasir</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Waktu Mulai</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Waktu Selesai</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Selisih</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($closedSessions as $session)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2"><div class="font-medium text-gray-800 dark:text-gray-100">{{ $session->user->name }}</div></td>
                                    <td class="p-2"><div>{{ $session->start_time->format('d M Y, H:i') }}</div></td>
                                    <td class="p-2"><div>{{ $session->end_time->format('d M Y, H:i') }}</div></td>
                                    <td class="p-2 text-right">
                                        <div class="font-bold {{ $session->difference == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            Rp {{ number_format($session->difference, 0, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                        Belum ada riwayat sesi.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-3">
                        {{ $closedSessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isStartModalOpen)
        <div class="relative z-50" x-data="{ show: @entangle('isStartModalOpen') }" x-show="show" @keydown.escape.window="show = false" style="display: none;">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/50 dark:bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-50 w-screen overflow-y-auto" @click="show = false">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="show" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">
                        <form>
                            <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100">Mulai Sesi Kasir Baru</h3></header>
                            <div class="p-6">
                                <label for="start_balance" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Modal Awal (Rp)</label>
                                <input type="number" wire:model="start_balance" id="start_balance" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Masukkan jumlah modal awal">
                                @error('start_balance') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="startSession" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-indigo-600 hover:bg-indigo-700 text-white">Mulai</button>
                                <button wire:click="$set('isStartModalOpen', false)" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Batal</button>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($isEndModalOpen)
        <div class="relative z-50" x-data="{ show: @entangle('isEndModalOpen') }" x-show="show" @keydown.escape.window="show = false" style="display: none;">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/50 dark:bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-50 w-screen overflow-y-auto" @click="show = false">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="show" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">
                        <form>
                            <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100">Tutup Sesi Kasir</h3></header>
                            <div class="p-6 space-y-4">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg space-y-2">
                                    <div class="flex justify-between text-sm"><span class="text-gray-600 dark:text-gray-400">Modal Awal:</span> <span class="font-medium dark:text-gray-200">Rp {{ number_format($activeSession->start_balance ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between text-sm"><span class="text-gray-600 dark:text-gray-400">Penjualan Tunai (Sistem):</span> <span class="font-medium dark:text-gray-200">Rp {{ number_format($calculated_sales, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between text-sm font-bold pt-2 border-t border-gray-200 dark:border-gray-600"><span class="dark:text-gray-200">Total Uang Seharusnya:</span> <span class="dark:text-gray-100">Rp {{ number_format(($activeSession->start_balance ?? 0) + $calculated_sales, 0, ',', '.') }}</span></div>
                                </div>
                                <div>
                                    <label for="end_balance" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Uang Fisik di Laci Kas (Rp)</label>
                                    <input type="number" wire:model.live="end_balance" id="end_balance" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Masukkan jumlah uang fisik">
                                    @error('end_balance') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="p-3 rounded-lg text-center {{ $difference == 0 ? 'bg-green-100 dark:bg-green-800/30' : 'bg-red-100 dark:bg-red-800/30' }}">
                                    <p class="text-sm font-bold {{ $difference == 0 ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300' }}">Selisih: Rp {{ number_format($difference, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Catatan (Opsional)</label>
                                    <textarea wire:model="notes" id="notes" rows="2" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Tambahkan catatan jika ada selisih..."></textarea>
                                </div>
                            </div>
                            <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="endSession" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-red-600 hover:bg-red-700 text-white">Tutup Sesi</button>
                                <button wire:click="$set('isEndModalOpen', false)" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Batal</button>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
