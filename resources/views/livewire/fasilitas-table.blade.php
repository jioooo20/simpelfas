<div>
    <div class="flex flex-col lg:flex-row gap-4 mb-6">
        <div class="form-control flex-1">
            <input type="text" wire:model.live="search" placeholder="Cari fasilitas, ruangan, atau barang..."
                   class="input input-bordered w-full" />
        </div>

        <div class="flex flex-wrap gap-2">
            <select wire:model.live="gedungFilter" class="select select-bordered">
                <option value="">Semua Gedung</option>
                @foreach($gedungs as $gedung)
                    <option value="{{ $gedung->gedung_id }}">{{ $gedung->gedung_nama }}</option>
                @endforeach
            </select>

            <select wire:model.live="lantaiFilter" class="select select-bordered"
                    {{ !$gedungFilter ? 'disabled' : '' }}>
                <option value="">Semua Lantai</option>
                @foreach($lantais as $lantai)
                    <option value="{{ $lantai->lantai_id }}">{{ $lantai->lantai_nama }}</option>
                @endforeach
            </select>

            <select wire:model.live="ruangFilter" class="select select-bordered"
                    {{ !$lantaiFilter ? 'disabled' : '' }}>
                <option value="">Semua Ruangan</option>
                @foreach($ruangs as $ruang)
                    <option value="{{ $ruang->ruang_id }}">{{ $ruang->ruang_nama }}</option>
                @endforeach
            </select>

            <select wire:model.live="perPage" class="select select-bordered">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
                <option value="100">100 per halaman</option>
            </select>
        </div>

        {{-- + --}}
        <button wire:click="openModal" class="btn btn-primary">
              <i class="fas fa-fas fa-plus text-white"></i>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">Kode</th>
                    <th>Gedung</th>
                    <th>Lantai</th>
                    <th>Ruangan</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $facility)
                    <tr>
                        <td class="flex gap-2 justify-center font-medium">{{ $facility->fasilitas_kode }}</td>
                        <td>{{ $facility->ruang->lantai->gedung->gedung_nama }}</td>
                        <td>{{ $facility->ruang->lantai->lantai_nama }}</td>
                        <td>{{ $facility->ruang->ruang_nama }}</td>
                        <td>{{ $facility->barang->barang_nama }}</td>
                        <td>
                            <span class="badge
                                @if($facility->fasilitas_status == 'Baik') badge-success
                                @elseif($facility->fasilitas_status == 'Dalam Perbaikan') badge-warning
                                @else badge-error
                                @endif">
                                {{ $facility->fasilitas_status }}
                            </span>
                        </td>
                        <td class="flex gap-2 justify-center">
                            <div class="flex gap-2">
                                <button wire:click="editFacility({{ $facility->fasilitas_id }})"
                                        class="text-indigo-400 hover:text-indigo-800">
                                   <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $facility->fasilitas_id }})"
                                        class="text-red-500 hover:text-red-700">
                                  <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            Tidak ada fasilitas ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagz --}}
  <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $facilities->firstItem() }} - {{ $facilities->lastItem() }} dari {{ $facilities->total() }} hasil
            </div>
            <div class="join">
                @php
                    $startPage = max($facilities->currentPage() - 1, 1);
                    $endPage = min($startPage + 2, $facilities->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($page = $startPage; $page <= $endPage; $page++)
                    <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                        <button class="join-item btn btn-sm {{ $facilities->currentPage() == $page ? 'btn-active' : '' }}">
                            {{ $page }}
                        </button>
                    </a>
                @endfor
            </div>
        </div>

    {{-- modal add/edit --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">
                        {{ $editingId ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="btn btn-sm btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    {{-- gedung --}}
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Gedung <span class="text-red-500">*</span></span>
                        </label>
                        <select wire:model.live="selectedGedung" class="select select-bordered w-full">
                            <option value="">Pilih Gedung</option>
                            @foreach($allGedungs as $gedung)
                                <option value="{{ $gedung->gedung_id }}">{{ $gedung->gedung_nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- lantai --}}
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Lantai <span class="text-red-500">*</span></span>
                        </label>
                        <select wire:model.live="selectedLantai" class="select select-bordered w-full"
                                {{ !$selectedGedung ? 'disabled' : '' }}>
                            <option value="">Pilih Lantai</option>
                            @foreach($formLantais as $lantai)
                                <option value="{{ $lantai->lantai_id }}">{{ $lantai->lantai_nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ruangan --}}
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Ruangan <span class="text-red-500">*</span></span>
                        </label>
                        <select wire:model.live="selectedRuang" class="select select-bordered w-full"
                                {{ !$selectedLantai ? 'disabled' : '' }}>
                            <option value="">Pilih Ruangan</option>
                            @foreach($formRuangs as $ruang)
                                <option value="{{ $ruang->ruang_id }}">{{ $ruang->ruang_nama }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('selectedRuang'))
                            <span class="text-error text-sm">{{ $errors->first('selectedRuang') }}</span>
                        @endif
                    </div>

                    {{-- barang --}}
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Barang <span class="text-red-500">*</span></span>
                        </label>
                        <select wire:model="selectedBarang" class="select select-bordered w-full">
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->barang_id }}">{{ $barang->barang_nama }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('selectedBarang'))
                            <span class="text-error text-sm">{{ $errors->first('selectedBarang') }}</span>
                        @endif
                    </div>

                    {{-- kode fasilitas, nnt jadiin otomatis via backend --}}
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Kode Fasilitas <span class="text-red-500">*</span><span class="text-gray-400"> Pastikan kode unik</span></span>
                        </label>
                        <input type="text" wire:model="fasilitasKode" class="input input-bordered w-full"
                               placeholder="Masukkan kode fasilitas">
                        @if ($errors->has('fasilitasKode'))
                            <span class="text-error text-sm">{{ $errors->first('fasilitasKode') }}</span>
                        @endif
                    </div>

                    {{-- status fasilitas --}}
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text">Status <span class="text-red-500">*</span></span>
                        </label>
                        <select wire:model="fasilitasStatus" class="select select-bordered w-full">
                            <option value="Baik">Baik</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                        @if ($errors->has('fasilitasStatus'))
                            <span class="text-error text-sm">{{ $errors->first('fasilitasStatus') }}</span>
                        @endif
                    </div>

                    {{-- simpan apa update hayo --}}
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="btn btn-sm btn-outline">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white">
                            {{ $editingId ? 'Perbarui Fasilitas' : 'Simpan Fasilitas' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Fasilitas</h3>
                    <p class="text-gray-600">Apakah Anda yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="flex justify-center gap-3">
                    <button wire:click="closeDeleteModal" class="btn btn-sm btn-outline">
                        Batal
                    </button>
                    <button wire:click="confirmDelete" class="btn btn-sm btn-error">
                        Hapus
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
        });
    </script>
@endpush
