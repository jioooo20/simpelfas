{{-- filepath: d:\laragon\www\simpelfas\resources\views\livewire\riwayatPerbaikan-table.blade.php --}}
<div> {{-- search --}}
    <div class="mb-4 flex items-center justify-between flex-wrap gap-2">
        <div class="relative w-full max-w-xs"> <span
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-search text-gray-500"></i>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Cari kode, masalah, lokasi, atau teknisi..."
                class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400" />
            @if ($search)
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-circle"></i>
                </button>
            @endif
        </div>

        {{-- status filter dropdown --}}
        <div class="dropdown">
            <label tabindex="0" class="btn {{ $selectedStatus ? 'btn-primary text-white' : 'btn-outline' }} gap-2">
                {{ $selectedStatus ?: 'Semua Status' }}
                <i class="bi bi-chevron-down"></i>
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                <li>
                    <a wire:click="setStatusFilter('')" class="{{ !$selectedStatus ? 'bg-base-200' : '' }}">Semua
                        Status</a>
                </li>
                <li>
                    <a wire:click="setStatusFilter('Selesai')"
                        class="{{ $selectedStatus === 'Selesai' ? 'bg-base-200' : '' }}">Selesai</a>
                </li>
                <li>
                    <a wire:click="setStatusFilter('Dibatalkan')"
                        class="{{ $selectedStatus === 'Dibatalkan' ? 'bg-base-200' : '' }}">Dibatalkan</a>
                </li>
            </ul>
        </div>
    </div>

    {{-- active filters indicator --}}
    @if ($selectedStatus || $search)
        <div class="mb-4 flex flex-wrap gap-2">
            <div class="text-sm text-gray-500">Filter aktif:</div>

            @if ($selectedStatus)
                <div class="badge badge-outline gap-1 px-3 py-2">
                    Status: {{ $selectedStatus }}
                    <button wire:click="clearStatusFilter" class="ml-2 hover:text-red-500">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            @endif

            @if ($search)
                <div class="badge badge-outline gap-1 px-3 py-2">
                    Pencarian: "{{ $search }}"
                    <button wire:click="clearSearch" class="ml-2 hover:text-red-500">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="table table-zebra w-full">
            <thead class="bg-base-200 text-base-content">
                <tr class="">
                    <th class="text-center">No</th>
                    <th>Kode Perbaikan</th>
                    <th>Perbaikan</th>
                    <th>Tanggal Selesai</th>
                    <th>Teknisi yang Ditugaskan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 0;
                @endphp
                @forelse ($riwayatPerbaikan as $index => $item)
                    <tr>
                        <td class="text-center">{{ ++$no }}</td>
                        <td class="text-start">{{ $item->latestCode ?? $item->perbaikan->perbaikan_kode }}</td>
                        <td>{{ $item->perbaikan->pelaporan->pelaporan_deskripsi }} di {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama }} {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->lantai_nama }}
                        </td>
                        <td class="">
                            <p>
                                {{ date('d M Y', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}
                            </p>
                            <p>
                                {{ date('H:i', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}
                            </p>
                        </td>
                        <td class="">
                            <span class="px-3 py-1 rounded-full">
                                {{ $item->perbaikan->perbaikanPetugas->pluck('user.nama')->join(', ') }}
                            </span>
                        </td>
                        <td class="">
                            <span class="badge text-white px-3 py-1 rounded-full bg-green-500">
                                {{ $item->perbaikan_status }}
                            </span>
                        </td>
                        <td class="h-full">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="goToDetail('{{ $item->perbaikan->perbaikan_id }}')" class="flex items-center gap-1 justify-center">
                                    <i class="fas fa-eye text-primary"></i>
                                </button>
                                <button class="flex items-center gap-1 justify-center text-green-500" 
                                    onclick="window.print()">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada riwayat perbaikan fasilitas ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    @if (method_exists($riwayatPerbaikan, 'hasPages') && $riwayatPerbaikan->hasPages())
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $riwayatPerbaikan->firstItem() }} - {{ $riwayatPerbaikan->lastItem() }} dari {{ $riwayatPerbaikan->total() }}
                hasil
            </div>
            <div class="join">
                @php
                    $startPage = max($riwayatPerbaikan->currentPage() - 1, 1);
                    $endPage = min($startPage + 2, $riwayatPerbaikan->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($page = $startPage; $page <= $endPage; $page++)
                    <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                        <button class="join-item btn btn-sm {{ $riwayatPerbaikan->currentPage() == $page ? 'btn-active' : '' }}">
                            {{ $page }}
                        </button>
                    </a>
                @endfor
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
