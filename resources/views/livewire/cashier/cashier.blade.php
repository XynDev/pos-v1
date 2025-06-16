{{--
    CATATAN:
    - Halaman ini didesain ulang dengan tata letak dua kolom yang dioptimalkan untuk kasir.
    - Kolom kiri (Keranjang) dan kanan (Daftar Produk) memiliki gaya yang berbeda untuk memisahkan fungsi.
    - Semua elemen, termasuk input, tombol, dan kartu produk, telah disesuaikan dengan tema modern.
    - REVISI: Mengubah h-screen menjadi height kalkulasi untuk mengatasi masalah layout dan scrolling.
--}}
<div>
    {{--
        PERUBAHAN KUNCI: `h-screen` diubah menjadi `style="height: calc(100vh - 4rem);"`
        Ini membuat kontainer mengisi tinggi layar yang tersisa setelah dikurangi tinggi header (h-16 atau 4rem).
        Ini akan memperbaiki masalah tumpang tindih dengan sidebar dan scrolling internal.
    --}}
    <div class="grid grid-cols-12" style="height: calc(100vh - 4rem);">

        {{-- Kolom Kiri: Keranjang & Pembayaran --}}
        <div class="col-span-12 lg:col-span-5 xl:col-span-4 bg-white dark:bg-gray-800 p-4 flex flex-col h-full border-r border-gray-200 dark:border-gray-700/60">

            {{-- Bagian Pelanggan --}}
            <div class="flex-shrink-0 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700/60">
                @if($selectedCustomer)
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pelanggan:</p>
                                <p class="font-bold text-indigo-800 dark:text-indigo-200">{{ $selectedCustomer['name'] }}</p>
                            </div>
                            <button wire:click="removeCustomer" class="text-sm text-red-600 hover:text-red-900 font-semibold">Ganti</button>
                        </div>
                    </div>
                @else
                    <div class="relative">
                        <label for="customer_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Pelanggan (Opsional)</label>
                        <input type="text" id="customer_search" wire:model.live.debounce.300ms="customerSearchQuery" placeholder="Cari nama / telepon..." class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600">
                        @if(!empty($customerSearchResults) && strlen($customerSearchQuery) > 1)
                            <ul class="absolute z-20 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg max-h-48 overflow-y-auto">
                                @forelse($customerSearchResults as $customer)
                                    <li wire:click="selectCustomer({{ $customer['id'] }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        {{ $customer['name'] }} - {{ $customer['phone'] }}
                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-gray-500">Pelanggan tidak ditemukan.</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Daftar Item Keranjang --}}
            <div class="flex-grow overflow-y-auto -mx-4 px-4 custom-scrollbar">
                @forelse($cart as $productId => $item)
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-grow pr-2">
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <button wire:click="decrementQuantity({{ $productId }})" class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full font-bold text-lg flex items-center justify-center">-</button>
                            <span class="w-10 text-center font-bold text-gray-800 dark:text-gray-100">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity({{ $productId }})" class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full font-bold text-lg flex items-center justify-center">+</button>
                        </div>
                        <div class="w-28 text-right font-semibold text-gray-800 dark:text-gray-100 flex-shrink-0 ml-2">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </div>
                        <button wire:click="removeFromCart({{ $productId }})" class="ml-3 text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-16 flex flex-col items-center justify-center h-full">
                        <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.51 0 .962-.344 1.087-.835l1.858-6.441a.5.5 0 0 0-.164-.531l-1.522-1.025a.5.5 0 0 0-.531.164L10.5 11.25H7.5" />
                        </svg>
                        <p>Keranjang masih kosong</p>
                    </div>
                @endforelse
            </div>

            {{-- Total & Tombol Aksi --}}
            <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700/60 pt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-300">Subtotal</span>
                    <span class="font-semibold dark:text-gray-200">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-800 dark:text-gray-100">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button wire:click="openHoldModal" @if(empty($cart)) disabled @endif class="btn w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        Tahan
                    </button>
                    <button wire:click="openPaymentModal" @if(empty($cart)) disabled @endif class="btn w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        Bayar
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pencarian Produk --}}
        <div class="col-span-12 lg:col-span-7 xl:col-span-8 bg-gray-50 dark:bg-gray-900/70 p-6 flex flex-col h-full">

            {{-- Notifikasi --}}
            <div>
                @if(session()->has('message'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-md" role="alert"><p>{{ session('message') }}</p></div>@endif
                @if(session()->has('error'))<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-md" role="alert"><p>{{ session('error') }}</p></div>@endif
            </div>

            {{-- Card Transaksi Tertunda --}}
            <div class="flex-shrink-0 mb-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-3 border-b border-gray-100 dark:border-gray-700/60">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Transaksi Tertunda</h3>
                </header>
                <div class="p-3 max-h-32 overflow-y-auto custom-scrollbar">
                    @forelse($heldTransactions as $held)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $held->reference_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($held->total_amount, 0, ',', '.') }} - {{ $held->customer->name ?? 'Umum' }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="resumeTransaction({{ $held->id }})" class="btn-sm bg-green-500 hover:bg-green-600 text-white">Lanjutkan</button>
                                <button wire:click="deleteHeldTransaction({{ $held->id }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">Hapus</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada transaksi yang ditahan.</p>
                    @endforelse
                </div>
            </div>

            {{-- Pencarian & Grid Produk --}}
            <div class="flex-grow flex flex-col">
                <div class="relative mb-6">
                    <input wire:model.live.debounce.300ms="searchQuery" type="text" placeholder="Cari produk dengan nama atau SKU..." class="form-input w-full p-4 text-lg pl-12 dark:bg-gray-800 dark:border-gray-600">
                    <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-4">
                        <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-grow overflow-y-auto -mx-2 px-2 custom-scrollbar">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                        @forelse($searchResults as $product)
                            <div wire:click="selectProduct({{ $product->id }})" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-xs cursor-pointer hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
                                <p class="font-bold truncate text-gray-800 dark:text-gray-100">{{ $product->name }}</p>
                                @if($product->type == 'simple')
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Stok: {{ $product->stock }}</p>
                                    <p class="text-indigo-600 font-semibold mt-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @elseif($product->type == 'variable')
                                    <p class="text-sm text-purple-600 dark:text-purple-400 font-semibold mt-2">Pilih Varian</p>
                                @elseif($product->type == 'bundle')
                                    <p class="text-sm text-green-600 dark:text-green-400 font-semibold mt-2">Paket</p>
                                    <p class="text-indigo-600 font-semibold">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        @empty
                            @if(empty($searchQuery))
                                <div class="col-span-full text-center text-gray-500 py-16 flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                    <p>Ketik untuk mulai mencari produk.</p>
                                </div>
                            @else
                                <div class="col-span-full text-center text-gray-500 py-16">
                                    <p>Produk tidak ditemukan.</p>
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if($isPaymentModalOpen) @include('livewire.cashier.cashier-payment-modal') @endif
        @if($isHoldModalOpen) @include('livewire.cashier.cashier-hold-modal') @endif
        @if($isVariantModalOpen) @include('livewire.cashier.cashier-variant-modal') @endif
    </div>

<script>
        document.addEventListener('livewire:navigated', () => {
            const paymentInput = document.getElementById('paymentAmount');
            if (paymentInput) {
                paymentInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let formattedValue = new Intl.NumberFormat('id-ID').format(value);
                    e.target.value = formattedValue;
                @this.set('paymentAmount', value)
                });
            }
        });
    </script>
</div>
