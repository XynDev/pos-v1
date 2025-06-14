<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Atribut & Varian</h3>
    @error('productAttributesData') <div class="text-red-500 text-xs mb-2">{{ $message }}</div> @enderror
    @error('variants') <div class="text-red-500 text-xs mb-2">{{ $message }}</div> @enderror

    <div class="space-y-4 p-4 border rounded-lg bg-gray-50">
        @foreach($productAttributesData as $index => $attribute)
            <div class="flex items-end gap-4">
                <div class="flex-grow">
                    <label class="block font-medium text-sm text-gray-700">Atribut {{ $index + 1 }}</label>
                    <select wire:model="productAttributesData.{{ $index }}.id" class="mt-1 block w-full form-select rounded-md shadow-sm">
                        <option value="">Pilih Atribut</option>
                        @foreach($productAttributes as $attr)
                            <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow">
                    <label class="block font-medium text-sm text-gray-700">Nilai (pisahkan dengan koma)</label>
                    <input type="text" wire:model="productAttributesData.{{ $index }}.values" placeholder="Contoh: Merah, Biru, Hijau" class="mt-1 block w-full form-input rounded-md shadow-sm">
                </div>
                <button wire:click="removeAttribute({{ $index }})" class="bg-red-500 text-white px-3 py-2 rounded-md">&times;</button>
            </div>
        @endforeach

        <button wire:click="addAttribute" class="text-sm text-blue-600 font-semibold">+ Tambah Atribut</button>
    </div>

    <div class="my-4 text-center">
        <button wire:click="generateVariants" class="bg-gray-800 text-white font-bold py-2 px-4 rounded-lg">
            Hasilkan Varian
        </button>
    </div>

    @if(!empty($variants))
        <div class="mt-6 overflow-x-auto">
            <h4 class="text-md font-medium text-gray-800 mb-2">Daftar Varian Dihasilkan</h4>
            <table class="w-full">
                <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Nama Varian</th>
                    <th class="p-2 text-left">SKU</th>
                    <th class="p-2 text-left">Harga Beli</th>
                    <th class="p-2 text-left">Harga Jual</th>
                    <th class="p-2 text-left">Stok</th>
                </tr>
                </thead>
                <tbody>
                @foreach($variants as $vIndex => $variant)
                    <tr class="border-b">
                        <td class="p-2">{{ $variant['name'] }}</td>
                        <td class="p-2">
                            <input type="text" wire:model="variants.{{ $vIndex }}.sku" class="w-full form-input text-sm">
                            @error('variants.'.$vIndex.'.sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </td>
                        <td class="p-2">
                            <input type="number" wire:model="variants.{{ $vIndex }}.purchase_price" class="w-full form-input text-sm">
                            @error('variants.'.$vIndex.'.purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </td>
                        <td class="p-2">
                            <input type="number" wire:model="variants.{{ $vIndex }}.selling_price" class="w-full form-input text-sm">
                            @error('variants.'.$vIndex.'.selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </td>
                        <td class="p-2">
                            <input type="number" wire:model="variants.{{ $vIndex }}.stock" class="w-full form-input text-sm">
                            @error('variants.'.$vIndex.'.stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
