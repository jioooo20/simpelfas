<div>
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
                        // close: true,
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
                        // close: true,
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
    {{-- search --}}
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari berdasarkan kode, fasilitas, atau lokasi..." />
        </div>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full relative" id="perbaikan-table">
            <thead>
                <tr class="bg-base-200">
                    <th>Kode</th>
                    <th>Fasilitas</th>
                    <th>Lokasi</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table as $perbaikan)
                    <tr class="hover">
                        <td>{{ $perbaikan->kode_perbaikan }}</td>
                        <td>{{ $perbaikan->fasilitas->nama ?? 'Tidak diketahui' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                <div>
                                    <div class="font-medium">{{ $perbaikan->lokasi }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $perbaikan->tanggal_selesai ? date('d/m/Y', strtotime($perbaikan->tanggal_selesai)) : '-' }}
                        </td>
                        <td>
                            @if ($perbaikan->status == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($perbaikan->status == 'dibatalkan')
                                <span class="badge badge-error">Dibatalkan</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($perbaikan->status) }}</span>
                            @endif
                        </td>
                        <td class="flex gap-2 justify-center">
                            <a href="{{ route('teknisi.perbaikan.detail', $perbaikan->id) }}"
                                class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="bi bi-eye-fill"></i>
                            </a>

                            <button onclick="cetak_laporan_modal.showModal()"
                                class="btn btn-sm btn-circle btn-ghost text-green-500">
                                <i class="bi bi-printer-fill"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $table->firstItem() }} - {{ $table->lastItem() }} dari {{ $table->total() }} hasil
        </div>
        <div class="join">
            @php
                $startPage = max($table->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $table->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <a href="{{ $table->url($page) }}">
                    <button class="join-item btn btn-sm {{ $table->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endfor
        </div>
        <label class="label">
            <span class="label-text">Tanggal Selesai</span>
        </label>
        <input type="date" wire:model="tanggal_selesai" class="input input-bordered w-full">

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" wire:click="$set('showEditModal', false)" class="btn btn-sm btn-ghost">Batal</button>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </div>
        </form>
    </div>
    @endif
    <!-- Delete Confirmation Modal -->
    @if ($confirmingPerbaikanDeletion)
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
                        Anda akan menghapus data perbaikan <span class="font-semibold">{{ $kode_perbaikan }}</span>
                        dari sistem. Semua data terkait perbaikan ini akan dihapus secara permanen.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('confirmingPerbaikanDeletion', false)" class="btn btn-outline btn-sm">
                        <i class="bi bi-x mr-1"></i> Batal
                    </button>
                    <button wire:click="deletePerbaikan" class="btn btn-error btn-sm">
                        <i class="bi bi-trash mr-1"></i> Hapus Data
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
