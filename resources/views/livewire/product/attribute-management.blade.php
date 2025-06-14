<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Atribut Produk') }}
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
                        Tambah Atribut Baru
                    </button>

                    @if($isModalOpen)
                        @include('livewire.product.attribute-form')
                    @endif

                    <div class="mb-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari atribut..."
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Nama Atribut</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($attributes as $attribute)
                                <tr>
                                    <td class="border px-4 py-2">{{ $attribute->name }}</td>
                                    <td class="border px-4 py-2">
                                        <button wire:click="edit({{ $attribute->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                        <button wire:click="delete({{ $attribute->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="2">Tidak ada data atribut.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $attributes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
