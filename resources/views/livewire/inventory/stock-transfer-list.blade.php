<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Daftar Transfer Stok

            <x-slot name="actions">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. transfer..."
                           class="form-input w-full md:w-64 pl-9 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                    <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('inventory.transfers.create') }}" class="btn bg-indigo-600 hover:bg-indigo-700 text-white whitespace-nowrap">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
                    </svg>
                    <span class="hidden xs:block ml-2">Buat Transfer Baru</span>
                </a>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
            <div class="p-3">
                @if (session()->has('message'))
                    <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md my-3" role="alert">
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                        <tr>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">No. Transfer</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Dari Lokasi</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Ke Lokasi</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Status</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                        </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($transfers as $transfer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                <td class="p-2">
                                    <div class="font-mono text-gray-800 dark:text-gray-100">{{ $transfer->transfer_number }}</div>
                                </td>
                                <td class="p-2">
                                    <div>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M Y') }}</div>
                                </td>
                                <td class="p-2">
                                    <div>{{ $transfer->fromLocation->name }}</div>
                                </td>
                                <td class="p-2">
                                    <div>{{ $transfer->toLocation->name }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($transfer->status == 'completed') bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300
                                            @else bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300 @endif
                                        ">
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="flex justify-center">
                                        <a href="{{ route('inventory.transfers.detail', $transfer->id) }}" class="btn-sm bg-indigo-500 hover:bg-indigo-600 text-white">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="6">
                                    Belum ada data transfer stok untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-3">
                    {{ $transfers->links('components.pagination-numeric') }}
                </div>
            </div>
        </div>
    </div>
</div>
