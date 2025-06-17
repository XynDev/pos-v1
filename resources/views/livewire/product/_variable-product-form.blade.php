<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Atribut & Varian</h2>
            <button wire:click="generateVariants" class="btn bg-gray-800 dark:bg-gray-100 hover:bg-gray-900 dark:hover:bg-white text-white dark:text-gray-800">
                Hasilkan Varian
            </button>
        </header>
        <div class="p-5">
            @error('productAttributesData') <div class="text-red-500 text-xs mb-4 p-3 bg-red-50 dark:bg-red-900/30 rounded-lg">{{ $message }}</div> @enderror
            @error('variants') <div class="text-red-500 text-xs mb-4 p-3 bg-red-50 dark:bg-red-900/30 rounded-lg">{{ $message }}</div> @enderror

            <div class="space-y-4">
                @foreach($productAttributesData as $index => $attribute)
                    <div class="flex items-end gap-4">
                        <div class="flex-grow">
                            <label for="attribute_{{ $index }}" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Atribut {{ $index + 1 }}</label>
                            <select wire:model="productAttributesData.{{ $index }}.id" id="attribute_{{ $index }}" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <option value="">Pilih Atribut</option>
                                @foreach($productAttributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow">
                            <label for="values_{{ $index }}" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nilai (pisahkan dengan koma)</label>
                            <input type="text" id="values_{{ $index }}" wire:model="productAttributesData.{{ $index }}.values" placeholder="Contoh: Merah, Biru" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <button wire:click="removeAttribute({{ $index }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white h-10">&times;</button>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button wire:click="addAttribute" type="button" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">+ Tambah Atribut</button>
            </div>
        </div>
    </div>

    @if(!empty($variants))
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Varian Dihasilkan</h2>
            </header>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                        <tr>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Nama Varian</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">SKU</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Harga Beli</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Harga Jual</div></th>
                            @foreach($locations as $location)
                                <th class="p-2 text-center">{{ $location->name }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach($variants as $vIndex => $variant)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                <td class="p-2"><div class="font-medium text-gray-800 dark:text-gray-100">{{ $variant['name'] }}</div></td>
                                <td class="p-2">
                                    <input type="text" wire:model="variants.{{ $vIndex }}.sku" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    @error('variants.'.$vIndex.'.sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="p-2">
                                    <input type="number" wire:model="variants.{{ $vIndex }}.purchase_price" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    @error('variants.'.$vIndex.'.purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="p-2">
                                    <input type="number" wire:model="variants.{{ $vIndex }}.selling_price" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    @error('variants.'.$vIndex.'.selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                @foreach($locations as $location)
                                    <td class="p-2">
                                        <input type="number" wire:model="variants.{{ $vIndex }}.stocks.{{ $location->id }}" class="w-20 form-input text-sm text-center">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
