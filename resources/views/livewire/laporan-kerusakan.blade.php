<div>
    {{-- Header dengan Search dan Filter --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        {{-- Search --}}
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari kode laporan, deskripsi, pelapor, atau fasilitas..." />
        </div>

        {{-- Filter Status --}}
        <div class="flex gap-3">
            <select wire:model.live="statusFilter" class="select select-bordered min-w-40">
                <option value="">Semua Status</option>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage" class="select select-bordered min-w-24">
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th class="text-center">No</th>
                    <th>Kode Laporan</th>
                    <th>Pelapor</th>
                    <th>Fasilitas</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $index => $laporan)
                    @php
                        $status = $this->getLatestStatus($laporan);
                        $statusClass = $this->getStatusBadgeClass($status);
                        $lokasi = '';
                        if ($laporan->fasilitas && $laporan->fasilitas->ruang) {
                            $lokasi =
                                $laporan->fasilitas->ruang->lantai->gedung->gedung_nama .
                                ' - ' .
                                $laporan->fasilitas->ruang->lantai->lantai_nama .
                                ' - ' .
                                $laporan->fasilitas->ruang->ruang_nama;
                        }
                    @endphp
                    <tr class="hover">
                        <td class="text-center">{{ $laporans->firstItem() + $index }}</td>
                        <td>
                            <div class="font-medium">{{ $laporan->pelaporan_kode }}</div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person-fill text-blue-500"></i>
                                <div>
                                    <div class="font-medium">{{ $laporan->user->nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $laporan->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $laporan->fasilitas->barang->barang_nama ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $laporan->fasilitas->fasilitas_kode ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-red-500"></i>
                                <div class="text-sm">{{ $lokasi ?: 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $laporan->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $laporan->created_at->format('H:i') }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $statusClass }}">{{ $status }}</span>
                        </td>
                        <td class="text-center">
                            <button wire:click="showDetail({{ $laporan->pelaporan_id }})"
                                class="btn btn-sm btn-circle btn-ghost text-blue-500 tooltip" data-tip="Tinjau Detail">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-inbox text-4xl text-gray-400"></i>
                                <span class="text-gray-500">Tidak ada laporan ditemukan</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $laporans->firstItem() }} - {{ $laporans->lastItem() }} dari {{ $laporans->total() }}
            hasil
        </div>
        <div class="join">


            @php
                $startPage = max($laporans->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $laporans->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                    <button class="join-item btn btn-sm {{ $laporans->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endfor

        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showDetailModal && $selectedLaporan)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            wire:key="detail-modal-{{ $selectedLaporan->pelaporan_id }}">
            <div class="bg-white rounded-lg p-6 w-full max-w-4xl shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Detail Laporan Kerusakan</h2>
                    <button wire:click="closeModal" class="btn btn-circle btn-ghost">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Informasi Laporan --}}
                    <div class="card bg-base-100 shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                <i class="bi bi-file-text"></i>
                                Informasi Laporan
                            </h3>

                            <div class="space-y-4 mt-4">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Kode Laporan:</span>
                                    <span class="badge badge-outline">{{ $selectedLaporan->pelaporan_kode }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="font-semibold">Status:</span>
                                    @php $currentStatus = $this->getLatestStatus($selectedLaporan); @endphp
                                    <span
                                        class="badge {{ $this->getStatusBadgeClass($currentStatus) }}">{{ $currentStatus }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="font-semibold">Tanggal Laporan:</span>
                                    <span>{{ $selectedLaporan->created_at->format('d/m/Y H:i') }}</span>
                                </div>

                                <div>
                                    <span class="font-semibold">Deskripsi:</span>
                                    <p class="mt-2 p-3 bg-gray-50 rounded text-sm">
                                        {{ $selectedLaporan->pelaporan_deskripsi }}</p>
                                </div>

                                {{-- Foto --}}
                                @if ($selectedLaporan->pelaporan_gambar)
                                    <div>
                                        <span class="font-semibold">Foto Kerusakan:</span>
                                        @php
                                            $photos = json_decode($selectedLaporan->pelaporan_gambar, true) ?: [];
                                        @endphp

                                        @if (count($photos) > 0)
                                            @if (count($photos) === 1)
                                                {{-- Single image --}}
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $photos[0]) }}"
                                                        alt="Foto kerusakan"
                                                        class="w-full h-48 object-cover rounded-lg border cursor-pointer hover:opacity-80 transition-opacity"
                                                        onclick="window.open(this.src, '_blank')"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="w-full h-48 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center"
                                                        style="display:none;">
                                                        <div class="text-center text-gray-500">
                                                            <i class="bi bi-image text-4xl mb-2"></i>
                                                            <p class="text-sm">Foto tidak tersedia</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Carousel for multiple images (max 3) --}}
                                                <div class="mt-2 carousel w-full h-48 rounded-lg border">
                                                    @foreach (array_slice($photos, 0, 3) as $index => $photo)
                                                        <div id="slide{{ $index }}"
                                                            class="carousel-item relative w-full">
                                                            <img src="{{ asset("storage/{$photo}") }}"
                                                                alt="Foto kerusakan"
                                                                class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity"
                                                                onclick="window.open(this.src, '_blank')"
                                                                onerror="this.style.display='none';">

                                                            {{-- Navigation arrows --}}
                                                            @if (count($photos) > 1)
                                                                <div
                                                                    class="absolute flex justify-between transform -translate-y-1/2 left-2 right-2 top-1/2">
                                                                    <a href="#slide{{ $index === 0 ? count(array_slice($photos, 0, 3)) - 1 : $index - 1 }}"
                                                                        class="btn btn-circle btn-sm bg-black/50 border-none text-white hover:bg-black/70">❮</a>
                                                                    <a href="#slide{{ $index === count(array_slice($photos, 0, 3)) - 1 ? 0 : $index + 1 }}"
                                                                        class="btn btn-circle btn-sm bg-black/50 border-none text-white hover:bg-black/70">❯</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Dots indicator --}}
                                                @if (count($photos) > 1)
                                                    <div class="flex justify-center w-full py-2 gap-2">
                                                        @foreach (array_slice($photos, 0, 3) as $index => $photo)
                                                            <a href="#slide{{ $index }}"
                                                                class="btn btn-xs btn-circle">{{ $index + 1 }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            {{-- No image placeholder --}}
                                            <div class="mt-2">
                                                <div
                                                    class="w-full h-48 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                                    <div class="text-center text-gray-500">
                                                        <i class="bi bi-image text-4xl mb-2"></i>
                                                        <p class="text-sm">Tidak ada foto tersedia</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Pelapor & Fasilitas --}}
                    <div class="space-y-6">
                        {{-- Pelapor --}}
                        <div class="card bg-base-100 shadow-lg">
                            <div class="card-body">
                                <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                    <i class="bi bi-person"></i>
                                    Pelapor
                                </h3>

                                <div class="space-y-3 mt-4">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Nama:</span>
                                        <span>{{ $selectedLaporan->user->nama ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Email:</span>
                                        <span>{{ $selectedLaporan->user->email ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Identitas:</span>
                                        <span>{{ $selectedLaporan->user->identitas ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Fasilitas --}}
                        <div class="card bg-base-100 shadow-lg">
                            <div class="card-body">
                                <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                    <i class="bi bi-tools"></i>
                                    Fasilitas
                                </h3>

                                <div class="space-y-3 mt-4">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Nama Barang:</span>
                                        <span>{{ $selectedLaporan->fasilitas->barang->barang_nama ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Kode Fasilitas:</span>
                                        <span
                                            class="badge badge-outline">{{ $selectedLaporan->fasilitas->fasilitas_kode ?? 'N/A' }}</span>
                                    </div>
                                    @if ($selectedLaporan->fasilitas && $selectedLaporan->fasilitas->ruang)
                                        <div>
                                            <span class="font-semibold">Lokasi:</span>
                                            <div class="mt-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span><i class="bi bi-building"></i>
                                                        {{ $selectedLaporan->fasilitas->ruang->lantai->gedung->gedung_nama }}
                                                    </span>
                                                    <span>{{ $selectedLaporan->fasilitas->ruang->lantai->gedung->gedung_kode }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span><i class="bi bi-layers"></i>
                                                        {{ $selectedLaporan->fasilitas->ruang->lantai->lantai_nama }}
                                                    </span>
                                                    <span>{{ $selectedLaporan->fasilitas->ruang->lantai->lantai_kode }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span><i class="bi bi-door-open"></i>
                                                        {{ $selectedLaporan->fasilitas->ruang->ruang_nama }}
                                                    </span>
                                                    <span>{{ $selectedLaporan->fasilitas->ruang->ruang_kode }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Biaya dan Aksi --}}
                @if ($currentStatus === 'Menunggu')
                    <div class="card bg-base-100 shadow-lg mt-6">
                        <div class="card-body">
                            <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                <i class="bi bi-currency-dollar"></i>
                                Persetujuan Laporan
                            </h3>

                            <div class="mt-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Estimasi Biaya Perbaikan (Rp)</span>
                                    </label>
                                    <input wire:model.live="biaya" type="number" class="input input-bordered w-full"
                                        placeholder="Masukkan estimasi biaya perbaikan (Masukkan 00 untuk tanpa biaya)"
                                        min="0" step="1000">
                                    @error('biaya')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end gap-3 mt-6">
                                    <button wire:click="closeModal" class="btn btn-outline">
                                        <i class="bi bi-x mr-1"></i> Batal
                                    </button>

                                    <button wire:click="tolakLaporan" class="btn btn-error">
                                        <i class="bi bi-x-circle mr-1"></i> Tolak
                                    </button>

                                    <button wire:click="terimaLaporan" class="btn btn-success"
                                        @if (empty($biaya) || ($biaya = 0)) disabled @endif> {{-- bisa biaya 0 --}}
                                        {{-- @if (empty($biaya) || $biaya <= 0) disabled @endif> --}}
                                        <i class="bi bi-check-circle mr-1"></i> Terima
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-end mt-6">
                        <button wire:click="closeModal" class="btn btn-outline">
                            <i class="bi bi-x mr-1"></i> Tutup
                        </button>
                    </div>
                @endif
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
