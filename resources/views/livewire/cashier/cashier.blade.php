<div>
    <div class="grid grid-cols-12 gap-6 h-screen">
        <div class="col-span-12 lg:col-span-5 xl:col-span-4 bg-white p-4 flex flex-col h-full">
            <div class="flex-shrink-0 mb-4 pb-4 border-b">
                @if($selectedCustomer)
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pelanggan:</p>
                                <p class="font-bold text-indigo-800">{{ $selectedCustomer['name'] }}</p>
                            </div>
                            <button wire:click="removeCustomer" class="text-sm text-red-600 hover:text-red-900 font-semibold">Ganti</button>
                        </div>
                    </div>
                @else
                    <div class="relative">
                        <label for="customer_search" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pelanggan (Opsional)</label>
                        <input type="text" id="customer_search" wire:model.live.debounce.300ms="customerSearchQuery" placeholder="Cari nama / telepon pelanggan..." class="w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm">

                        @if(!empty($customerSearchResults))
                            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg">
                                @forelse($customerSearchResults as $customer)
                                    <li wire:click="selectCustomer({{ $customer['id'] }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
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

            <div class="flex-shrink-0 mb-4">
                <h2 class="text-xl font-bold">Keranjang</h2>
            </div>

            <div class="flex-grow overflow-y-auto -mx-4 px-4">
                @forelse($cart as $productId => $item)
                    <div class="flex items-center justify-between mb-4 pb-2 border-b">
                        <div class="flex-grow">
                            <p class="font-semibold">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <button wire:click="decrementQuantity({{ $productId }})" class="w-6 h-6 bg-gray-200 rounded-full font-bold">-</button>
                            <span class="w-10 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity({{ $productId }})" class="w-6 h-6 bg-gray-200 rounded-full font-bold">+</button>
                        </div>
                        <div class="w-24 text-right font-semibold flex-shrink-0 ml-4">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </div>
                        <button wire:click="removeFromCart({{ $productId }})" class="ml-4 text-red-500 hover:text-red-700">&times;</button>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-16">
                        <p>Keranjang masih kosong</p>
                    </div>
                @endforelse
            </div>

            <div class="flex-shrink-0 border-t pt-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold mb-4">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="openHoldModal" @if(empty($cart)) disabled @endif class="w-full bg-yellow-500 text-white font-bold py-3 rounded-lg text-lg disabled:bg-gray-400">
                        Tahan
                    </button>
                    <button wire:click="openPaymentModal" @if(empty($cart)) disabled @endif class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg text-lg disabled:bg-gray-400">
                        Bayar
                    </button>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-7 xl:col-span-8 bg-gray-50 p-6 flex flex-col h-full">
            <div>@if(session()->has('message'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('message') }}</p></div>@endif @if(session()->has('error'))<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert"><p>{{ session('error') }}</p></div>@endif</div>

            <div class="flex-shrink-0 mb-6 border rounded-lg bg-white p-4">
                <h3 class="font-bold text-lg mb-2">Transaksi Tertunda</h3>
                <div class="max-h-32 overflow-y-auto">
                    @forelse($heldTransactions as $held)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-100 rounded">
                            <div>
                                <p class="font-semibold">{{ $held->reference_name }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($held->total_amount, 0, ',', '.') }} - {{ $held->customer->name ?? 'Umum' }}</p>
                            </div>
                            <div>
                                <button wire:click="resumeTransaction({{ $held->id }})" class="text-sm bg-green-500 text-white px-2 py-1 rounded">Lanjutkan</button>
                                <button wire:click="deleteHeldTransaction({{ $held->id }})" class="text-sm bg-red-500 text-white px-2 py-1 rounded ml-1">Hapus</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada transaksi yang ditahan.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex-grow flex flex-col">
                <div class="relative mb-6"><input wire:model.live.debounce.300ms="searchQuery" type="text" placeholder="Cari produk..." class="w-full p-4 rounded-lg border-2 border-gray-200 text-lg"></div>
                <div class="flex-grow overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse($searchResults as $product)
                            <div wire:click="selectProduct({{ $product->id }})" class="bg-white p-4 rounded-lg shadow cursor-pointer hover:shadow-md">
                                <p class="font-bold truncate">{{ $product->name }}</p>
                                @if($product->type == 'simple')
                                    <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                                    <p class="text-indigo-600 font-semibold mt-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @elseif($product->type == 'variable')
                                    <p class="text-sm text-purple-600 font-semibold">Pilih Varian</p>
                                @elseif($product->type == 'bundle')
                                    <p class="text-sm text-green-600 font-semibold">Paket</p>
                                    <p class="text-indigo-600 font-semibold mt-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        @empty
                            @if(empty($searchQuery))<div class="col-span-full text-center text-gray-500 py-10"><p>Ketik untuk mulai mencari produk.</p></div>@else<div class="col-span-full text-center text-gray-500 py-10"><p>Produk tidak ditemukan.</p></div>@endif
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if($isPaymentModalOpen) @include('livewire.Cashier.cashier-payment-modal') @endif
        @if($isHoldModalOpen) @include('livewire.Cashier.cashier-hold-modal') @endif
        @if($isVariantModalOpen) @include('livewire.Cashier.cashier-variant-modal') @endif

        @if($isPaymentModalOpen)
            @include('livewire.cashier.cashier-payment-modal')
        @endif
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
