<div class="lg:col-span-1">
    <div class="sticky top-8">
        <div id="print-area" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Pratinjau Cetak</h2>
                @if (!empty($selectedForPrint))
                    <button wire:click="clearSelection" class="text-sm text-red-500 hover:underline">&times; Kosongkan</button>
                @endif
            </div>

            <div id="print-area-content"
                 wire:loading.class.delay="opacity-50"
                 wire:target="updateQuantity"
                 class="p-4 bg-gray-50 dark:bg-gray-900 border-2 border-dashed min-h-[200px] h-90 overflow-y-auto"
            >
                @forelse ($selectedProducts as $product)
                    <div wire:key="queue-item-{{ $product->id }}" class="py-2 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-semibold text-sm flex-grow">{{ $product->name }}</p>
                            <button wire:click.prevent="removeFromQueue({{ $product->id }})" class="text-red-500 hover:text-red-700 text-xl">&times;</button>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="qty-{{ $product->id }}" class="text-xs">Qty:</label>
                            <input
                                type="number"
                                id="qty-{{ $product->id }}"
                                min="1"
                                value="{{ $selectedForPrint[$product->id]['quantity'] ?? 1 }}"
{{--                                wire:change="updateQuantity({{ $product->id }}, $event.target.value)"--}}
                                wire:input.debounce.500ms="updateQuantity({{ $product->id }}, $event.target.value)"
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
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    onclick="printPreview()"
                    id="print-button"
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
        @foreach($selectedProducts as $product)
            @for ($i = 0; $i < ($selectedForPrint[$product->id]['quantity'] ?? 1); $i++)
                <div class="label" style="text-align: center; margin-bottom: 20px; border: 1px solid black; padding: 10px; display: inline-block;">
                    <p style="font-size: 14px; font-weight: bold; margin: 0;">{{ $product->name }}</p>
                    <div class="barcode-wrapper" style="margin: 5px 0;">
                        {!! DNS1D::getBarcodeSVG($product->internal_code, 'C128', 2, 50, 'black', true) !!}
                    </div>
                    <p style="font-size: 12px; margin: 0;">Rp {{ number_format($product->price) }}</p>
                </div>
            @endfor
        @endforeach
    </div>
</div>
