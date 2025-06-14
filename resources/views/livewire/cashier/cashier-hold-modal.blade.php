<div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                    Tahan Transaksi
                </h3>
                <div>
                    <label for="holdReferenceName" class="block text-sm font-medium text-gray-700">Nama Referensi</label>
                    <input type="text" wire:model="holdReferenceName" id="holdReferenceName" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Pelanggan Baju Merah">
                    @error('holdReferenceName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <p class="text-xs text-gray-500 mt-2">Beri nama agar mudah dikenali nanti.</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <button wire:click.prevent="holdTransaction" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 sm:w-auto sm:text-sm">
                    Tahan Transaksi
                </button>
                <button wire:click="$set('isHoldModalOpen', false)" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
