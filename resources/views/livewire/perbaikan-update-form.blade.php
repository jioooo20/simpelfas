<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
                <h3 class="text-lg font-bold mb-4">Update Status & Upload Gambar</h3>
                <form wire:submit.prevent="updatePerbaikan" enctype="multipart/form-data">
                    <div class="mb-2">
                        <label>Status</label>
                        <select wire:model="status" class="input input-bordered w-full">
                            <option value="">Pilih Status</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>                    <!-- Upload Foto Perbaikan -->
                    <div class="grid gap-2">
                        <label class="text-gray-700 font-medium flex items-center gap-1">
                            Upload Foto Perbaikan
                            <i class="bi bi-info-circle text-gray-400"
                                title="Gunakan foto yang jelas agar proses perbaikan cepat diproses"></i>
                        </label>                        <div id="upload-area" class="relative">
                            @if ($gambar)
                                <div class="h-48 w-full relative">
                                    <img src="{{ $gambar->temporaryUrl() }}" class="w-full h-full object-contain rounded-lg">
                                    <button type="button" wire:click="$set('gambar', null)" 
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                        <i class="bi bi-x text-xl"></i>
                                    </button>
                                </div>
                            @else
                                <label for="gambar-input" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div wire:loading.remove wire:target="gambar" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="bi bi-upload text-2xl text-gray-500 mb-2"></i>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 2MB)</p>
                                    </div>
                                    <div wire:loading wire:target="gambar" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="animate-spin h-10 w-10 text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-blue-500 font-medium">Mengupload gambar...</p>
                                    </div>
                                </label><input id="gambar-input" wire:model="gambar" type="file" accept="image/*" class="hidden" wire:loading.attr="disabled" />
                            @endif
                        </div>
                        @error('gambar')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        
                        <!-- Indikator Loading -->
                        <div wire:loading wire:target="gambar" class="mt-2">
                            <div class="flex items-center justify-center gap-2 text-blue-500">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang memproses gambar...</span>
                            </div>                        </div>
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn btn-sm" wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled" wire:target="updatePerbaikan,gambar">
                            <span wire:loading.remove wire:target="updatePerbaikan">Simpan</span>
                            <span wire:loading wire:target="updatePerbaikan" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>    
        @endif
</div>
