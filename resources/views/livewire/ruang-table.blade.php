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
        <div class="flex gap-3 items-center">
            <div class="relative">
                <select wire:model.live="filterGedungId" class="select select-bordered">
                    <option value="">Semua Gedung</option>
                    @foreach ($gedungs as $gedung)
                        <option value="{{ $gedung->gedung_id }}">{{ $gedung->gedung_kode }} - {{ $gedung->gedung_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="relative">
                <select wire:model.live="filterLantaiId" class="select select-bordered" {{ empty($filterGedungId) ? 'disabled' : '' }}>
                    <option value="">Semua Lantai</option>
                    @foreach ($lantais as $lantai)
                        <option value="{{ $lantai->lantai_id }}">{{ $lantai->lantai_kode }} - {{ $lantai->lantai_nama }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="create" class="btn btn-primary text-white">
                <i class="fas fa-fas fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full relative" id="ruang-table">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">ID</th>
                    <th>Gedung</th>
                    <th>Lantai</th>
                    <th>Kode</th>
                    <th>Nama Ruangan</th>
                    <th>Deskripsi</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ruang as $index => $item)
                    <tr class="hover">
                        <td class="flex gap-2 justify-center">{{ $item->ruang_id }}</td>
                        <td>{{ $item->lantai->gedung->gedung_nama ?? '-' }}</td>
                        <td>{{ $item->lantai->lantai_nama ?? '-' }}</td>
                        <td>{{ $item->ruang_kode }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-door-closed text-lg text-blue-500"></i>
                                <div class="font-medium">{{ $item->ruang_nama }}</div>
                            </div>
                        </td>
                        <td>{{ $item->ruang_keterangan ?: '-' }}</td>
                        <td class="flex gap-2 justify-center">
                            <a href="#" wire:click.prevent="edit({{ $item->ruang_id }})"
                                class="text-indigo-400 hover:text-indigo-800">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="#" wire:click.prevent="confirmDelete({{ $item->ruang_id }})"
                                class="text-red-500 hover:text-red-500">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data ruangan ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{-- @if ($ruang->hasPages()) --}}
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $ruang->firstItem() }} - {{ $ruang->lastItem() }} dari {{ $ruang->total() }} hasil
            </div>
            <div class="join">
                @php
                    $startPage = max($ruang->currentPage() - 1, 1);
                    $endPage = min($startPage + 2, $ruang->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($page = $startPage; $page <= $endPage; $page++)
                    <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                        <button class="join-item btn btn-sm {{ $ruang->currentPage() == $page ? 'btn-active' : '' }}">
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
            wire:key="ruang-modal-{{ now() }}">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl">
                <h2 class="text-xl font-semibold mb-4">{{ $isEditing ? 'Edit Ruangan' : 'Tambah Ruangan Baru' }}</h2>

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Gedung<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <select wire:model.live="filterGedungId" class="select select-bordered w-full">
                                <option value="">-- Pilih Gedung --</option>
                                @foreach ($gedungs as $gedung)
                                    <option value="{{ $gedung->gedung_id }}">{{ $gedung->gedung_kode }} - {{ $gedung->gedung_nama }}</option>
                                @endforeach
                            </select>
                            @error('filterGedungId') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lantai<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <select wire:model="lantai_id" class="select select-bordered w-full" {{ empty($filterGedungId) ? 'disabled' : '' }}>
                                <option value="">-- Pilih Lantai --</option>
                                @foreach ($lantaiOptions as $lantai)
                                    <option value="{{ $lantai->lantai_id }}">{{ $lantai->lantai_kode }} - {{ $lantai->lantai_nama }}</option>
                                @endforeach
                            </select>
                            @error('lantai_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Kode Ruangan<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="ruang_kode" placeholder="Masukkan kode ruangan"
                                class="input input-bordered w-full">
                            @error('ruang_kode') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nama Ruangan<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="ruang_nama" placeholder="Masukkan nama ruangan"
                                class="input input-bordered w-full">
                            @error('ruang_nama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Deskripsi</span>
                            </label>
                            <textarea wire:model="ruang_keterangan" placeholder="Masukkan deskripsi ruangan"
                                class="textarea textarea-bordered w-full"></textarea>
                            @error('ruang_keterangan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="btn btn-sm btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
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
                        Anda akan menghapus ruangan dengan kode <span class="font-semibold">{{ $ruang_kode }}</span>
                        dari sistem. Semua data terkait ruangan ini akan dihapus secara permanen.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModal" class="btn btn-outline btn-sm">
                        <i class="bi bi-x mr-1"></i> Batal
                    </button>
                    <button wire:click="delete" class="btn btn-error btn-sm">
                        <i class="bi bi-trash mr-1"></i> Hapus Ruangan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- @push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('showSuccessToast', function(message) {
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

            Livewire.on('showErrorToast', function(message) {
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

            // Listen for Livewire events to update collapse state
            Livewire.on('ruangCreated', () => {
                // Force table height recalculation for collapse toggle
                setTimeout(() => {
                    const content = document.getElementById('ruangCardContent');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                }, 200);
            });

            Livewire.on('ruangUpdated', () => {
                // Event handling if needed
            });

            Livewire.on('ruangDeleted', () => {
                // Event handling if needed
            });
        });
    </script>
@endpush --}}
