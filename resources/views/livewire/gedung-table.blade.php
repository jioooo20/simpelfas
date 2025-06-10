<div>
    {{-- search --}}
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari kode, nama, atau deskripsi" />
        </div>
        <button wire:click="create" class="btn btn-primary text-white">
             <i class="fas fa-fas fa-plus"></i>
        </button>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full relative" id="gedung-table">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">ID</th>
                    <th>Kode</th>
                    <th>Nama Gedung</th>
                    <th>Deskripsi</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gedung as $index => $item)
                    <tr class="hover">
                        <td class="flex gap-2 justify-center">{{ $item->gedung_id }}</td>
                        <td>{{ $item->gedung_kode }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-building text-lg text-blue-500"></i>
                                <div class="font-medium">{{ $item->gedung_nama }}</div>
                            </div>
                        </td>
                        <td>{{ $item->gedung_keterangan ?: '-' }}</td>
                        <td class="flex gap-2 justify-center">
                            <a href="#" wire:click.prevent="edit({{ $item->gedung_id }})"
                                class="text-indigo-400 hover:text-indigo-800">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="#" wire:click.prevent="confirmDelete({{ $item->gedung_id }})"
                                class="text-red-500 hover:text-red-500">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data gedung ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{-- @if ($gedung->hasPages()) --}}
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $gedung->firstItem() }} - {{ $gedung->lastItem() }} dari {{ $gedung->total() }} hasil
            </div>
            <div class="join">


                @php
                    $startPage = max($gedung->currentPage() - 1, 1);
                    $endPage = min($startPage + 2, $gedung->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($page = $startPage; $page <= $endPage; $page++)
                    <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                        <button class="join-item btn btn-sm {{ $gedung->currentPage() == $page ? 'btn-active' : '' }}">
                            {{ $page }}
                        </button>
                    </a>
                @endfor

            </div>
        </div>
    {{-- @endif --}}

    {{-- edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            wire:key="gedung-modal-{{ now() }}">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl">
                <h2 class="text-xl font-semibold mb-4">{{ $isEditing ? 'Edit Gedung' : 'Tambah Gedung Baru' }}</h2>

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Kode Gedung<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="gedung_kode" placeholder="Masukkan kode gedung"
                                class="input input-bordered w-full">
                            @error('gedung_kode') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nama Gedung<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="gedung_nama" placeholder="Masukkan nama gedung"
                                class="input input-bordered w-full">
                            @error('gedung_nama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Deskripsi</span>
                            </label>
                            <textarea wire:model="gedung_keterangan" placeholder="Masukkan deskripsi (opsional)"
                                class="textarea textarea-bordered w-full" rows="3"></textarea>
                            @error('gedung_keterangan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="btn btn-sm btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white" wire:loading.attr="disabled">
                            <span wire:loading.class="hidden"
                                wire:target="save">{{ $isEditing ? 'Perbarui' : 'Simpan' }}</span>
                            <span wire:loading wire:target="save">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- delete --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
                <div class="text-center mb-6">
                    <div class="flex justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-6xl text-red-500 mb-2"></i>
                    </div>
                    <h2 class="text-xl font-bold">Konfirmasi Hapus</h2>
                    <p class="text-gray-500 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                </div>

                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-5 rounded">
                    <p class="text-md">
                        Anda akan menghapus gedung dengan kode <span class="font-semibold">{{ $gedung_kode }}</span>
                        dari sistem. Semua data terkait gedung ini akan dihapus secara permanen.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModal" class="btn btn-outline btn-sm">
                        <i class="bi bi-x mr-1"></i> Batal
                    </button>
                    <button wire:click="delete" class="btn btn-error btn-sm">
                        <i class="bi bi-trash mr-1"></i> Hapus Gedung
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('showSuccessToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-check-circle-fill text-xl"></i>
                              <span>${message}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });

            Livewire.on('showErrorToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-exclamation-circle-fill text-xl"></i>
                              <span>${message}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });

            Livewire.on('gedungCreated', () => {
                // Force table height recalculation for collapse toggle
                setTimeout(() => {
                    const content = document.getElementById('gedungCardContent');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                }, 200);
            });

            Livewire.on('gedungUpdated', () => {
                // Event handling if needed
            });

            Livewire.on('gedungDeleted', () => {
                // Event handling if needed
            });

             Livewire.on('lantaiCreated', () => {
                // Force table height recalculation for collapse toggle
                setTimeout(() => {
                    const content = document.getElementById('lantaiCardContent');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                }, 200);
            });

            Livewire.on('lantaiUpdated', () => {
                // Event handling if needed
            });

            Livewire.on('lantaiDeleted', () => {
                // Event handling if needed
            });
        });
    </script>
@endpush
