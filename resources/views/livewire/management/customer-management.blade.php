<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pelanggan') }}
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

                    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Tambah Pelanggan Baru
                    </button>

                    @if($isModalOpen)
                        @include('livewire.management.customer-form')
                    @endif

                    <div class="mb-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pelanggan berdasarkan nama, email, atau telepon..."
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Telepon</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td class="border px-4 py-2">{{ $customer->name }}</td>
                                    <td class="border px-4 py-2">{{ $customer->email }}</td>
                                    <td class="border px-4 py-2">{{ $customer->phone }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('customers.show', $customer->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Riwayat</a>
                                        <button wire:click="edit({{ $customer->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                        <button wire:click="delete({{ $customer->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="4">Tidak ada data pelanggan.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
