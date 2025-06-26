<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Manajemen Cetak Barcode</h1>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <input type="text"
                            wire:model.live.debounce.300ms="search"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            wire:target="search, selectedCategory, selectedBrand, clearFilters, selectedForPrint"
                            placeholder="Cari nama atau kode produk..."
                            class="w-full sm:flex-grow rounded-md border-gray-300 shadow-sm"
                        >
                        <select wire:model.live="selectedCategory"
                                wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                wire:target="search, selectedCategory, selectedBrand, clearFilters, selectedForPrint"
                                class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="selectedBrand"
                                wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                wire:target="search, selectedCategory, selectedBrand, clearFilters, selectedForPrint"
                                class="w-full sm:w-auto rounded-md border-gray-300 shadow-sm">
                            <option value="">Semua Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @if ($search || $selectedCategory || $selectedBrand)
                            <button
                                wire:click="clearFilters"
                                class="text-sm text-gray-500 hover:text-red-600 hover:underline whitespace-nowrap"
                            >
                                &times; Reset Filter
                            </button>
                        @endif
                    </div>

                    <div wire:loading wire:target="search, selectedCategory, selectedBrand" class="text-center w-full">Memuat...</div>

                    <div wire:loading.remove wire:target="search, selectedCategory, selectedBrand" class="space-y-3 h-90 overflow-y-auto">
                        @forelse ($products as $product)
                            <label wire:key="product-list-{{ $product->id }}" class="flex items-center p-3 rounded-lg cursor-pointer transition {{ array_key_exists($product->id, $selectedForPrint) ? 'bg-indigo-100 dark:bg-indigo-900' : 'bg-gray-50 dark:bg-gray-700 hover:bg-gray-100' }}">
                                {{-- HANYA GUNAKAN wire:model.live. Kita hapus readonly dan pointer-events-none --}}
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedForPrint.{{ $product->id }}"
                                    wire:loading.attr="disabled"
                                    wire:target="search, selectedCategory, selectedBrand, clearFilters, selectedForPrint"
                                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >

                                <span class="ml-4 flex-grow font-medium">{{ $product->name }} <span class="text-sm text-gray-500">({{ $product->internal_code }})</span></span>
                            </label>
                        @empty
                            <p class="text-center text-gray-500">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div id="print-area" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Pratinjau Cetak</h2>
                                @if (!empty($selectedForPrint))
                                    <button
                                        wire:click="clearSelection"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50"
                                        wire:target="updateQuantity, removeFromQueue, clearSelection"
                                        class="text-sm text-red-500 hover:underline"
                                    >&times; Kosongkan</button>
                                @endif
                            </div>

                            <div id="print-area-content"
                                 wire:loading.class.delay="opacity-50"
                                 class="p-4 bg-gray-50 dark:bg-gray-900 border-2 border-dashed min-h-[200px] h-90 overflow-y-auto"
                            >
                                @forelse ($selectedProducts as $product)
                                    <div wire:key="queue-item-{{ $product->id }}" class="py-2 border-b border-gray-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <p class="font-semibold text-sm flex-grow">{{ $product->name }}</p>
                                            <button wire:click.prevent="removeFromQueue({{ $product->id }})"
                                                    wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                                    wire:target="updateQuantity, removeFromQueue, clearSelection"
                                                    class="text-red-500 hover:text-red-700 text-xl"
                                            >&times;</button wire:target="updateQuantity">
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label for="qty-{{ $product->id }}" class="text-xs">Qty:</label>
                                            <input
                                                type="number"
                                                id="qty-{{ $product->id }}"
                                                min="1"
                                                value="{{ $selectedForPrint[$product->id]['quantity'] ?? 1 }}"
                                                wire:input.debounce.500ms="updateQuantity({{ $product->id }}, $event.target.value)"
                                                wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                                wire:target="updateQuantity, removeFromQueue, clearSelection"
                                                class="w-20 text-center rounded-md border-gray-300 shadow-sm text-sm"
                                            >
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Pilih produk di sebelah kiri untuk melihat pratinjau.</p>
                                @endforelse
                            </div>

                            {{-- Tombol cetak dengan jumlah total label --}}
                            @if ($this->getTotalLabelCount() > 0)
                                <button
                                    id="print-button"
                                    onclick="printPreview()"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    wire:target="search, selectedCategory, selectedBrand, clearFilters, selectedForPrint, updateQuantity, removeFromQueue, clearSelection"
                                    class="mt-4 w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center"
                                >
                                    {{-- KONTEN NORMAL: Tampil saat tidak loading --}}
                                    <span
                                        id="print-button-normal-state"
                                        wire:loading.remove
                                    >
                                        Cetak {{ $this->getTotalLabelCount() }} Label
                                    </span>

                                    {{-- State Loading --}}
                                    <span id="print-button-loading-state" wire:loading>
                                    {{-- Span dalam ini yang bertugas menata posisi --}}
                                        <span style="display: flex; align-items: center; justify-content: center; white-space: nowrap;">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Memproses...</span>
                                        </span>
                                    </span>
                                </button>
                            @else
                                <button
                                    disabled
                                    class="mt-4 w-full opacity-50 cursor-not-allowed bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center"
                                >
                                    <span>
                                        Antrian Kosong
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div id="printable-content">
                        @if(!empty($selectedProducts))
                            @foreach($selectedProducts as $product)
                                @for ($i = 0; $i < ($selectedForPrint[$product->id]['quantity'] ?? 1); $i++)
                                    {{-- Kita gunakan class, bukan inline style --}}
                                    <div class="printable-label">
                                        <p class="product-name">{{ $product->name }}</p>
                                        <div class="barcode-img">
                                            {!! DNS1D::getBarcodeSVG($product->internal_code, 'C128', 2, 50, 'black', true) !!}
                                        </div>
                                        <p class="product-price">Rp {{ number_format($product->purchase_price) }}</p>
                                    </div>
                                @endfor
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
    </div>

    <style>
        /* CSS untuk barcode SVG di halaman web (jika diperlukan) */
        .barcode-wrapper svg {
            width: 100%;
            height: auto;
        }

        /* Sembunyikan area cetak saat di layar biasa */
        #printable-content {
            display: none;
        }

        /* Aturan untuk kondisi CETAK */
        @media print {
            /* Sembunyikan semua elemen di body secara default */
            body * {
                visibility: hidden;
            }

            /* Tampilkan HANYA area yang mau di-print dan semua isinya */
            #printable-content, #printable-content * {
                visibility: visible;
            }

            /* Atur layout untuk halaman cetak */
            #printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }

            /* Aturan untuk setiap label */
            .printable-label {
                border: 1px dashed #888;
                padding: 10px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                page-break-inside: avoid;
                box-sizing: border-box;
            }

            /* Aturan untuk teks di dalam label */
            .printable-label .product-name { ... }
            .printable-label .product-price { ... }

            /* Aturan untuk div pembungkus barcode */
            .printable-label .barcode-img {
                margin: 8px 0;
                width: 90%;
                height: auto;
            }

            /* KUNCI PERBAIKAN: Aturan untuk elemen SVG di dalam barcode-img */
            .printable-label .barcode-img svg {
                width: 100%;
                height: auto;
            }
        }
    </style>

    @push('scripts')
    <script>
        function printPreview() {
            // Cukup panggil window.print(). Browser akan otomatis menerapkan style @media print.
            window.print();
        }
    </script>
    @endpush
</div>
