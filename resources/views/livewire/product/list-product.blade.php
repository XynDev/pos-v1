<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Produk') }}
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
                        Tambah Produk Baru
                    </button>

                    @if($isModalOpen)
                        @include('livewire.product.list-product-form')
                    @endif

                    <div class="mb-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk berdasarkan nama atau SKU..."
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Gambar</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">SKU</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Merek</th>
                                <th class="px-4 py-2">Harga Jual</th>
                                <th class="px-4 py-2">Stok</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="border px-4 py-2">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : '[https://placehold.co/100x100?text=No+Image](https://placehold.co/100x100?text=No+Image)' }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                    </td>
                                    <td class="border px-4 py-2">{{ $product->name }}</td>
                                    <td class="border px-4 py-2">{{ $product->sku }}</td>
                                    <td class="border px-4 py-2">{{ $product->category->name ?? '' }}</td>
                                    <td class="border px-4 py-2">{{ $product->brand->name ?? '' }}</td>
                                    <td class="border px-4 py-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                                    <td class="border px-4 py-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <button wire:click="edit({{ $product->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                        <button wire:click="delete({{ $product->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="9">Tidak ada data.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
