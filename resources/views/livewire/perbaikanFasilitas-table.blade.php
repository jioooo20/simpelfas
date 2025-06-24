<div class="w-full">
    <!-- Grup Pencarian dan Filter Status -->
    <div class="mb-4 flex flex-col lg:flex-row items-center gap-4 w-full">

        <!-- Grup Pencarian -->
        <div class="relative w-full lg:flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-search text-gray-500"></i>
        </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Cari kode, masalah, lokasi..."
                   class="w-full h-10 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400"/>
            @if ($search)
                <button wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            @endif
        </div>

        <!-- Filter Status -->
        <div class="w-full lg:w-auto">
            <div class="dropdown dropdown-end w-full lg:w-auto">
                <label tabindex="0"
                       class="btn {{ $selectedStatus ? 'btn-primary text-white' : 'btn-outline border-gray-300' }} gap-2 w-full lg:w-auto">
                    <span>{{ $selectedStatus ?: 'Semua Status' }}</span>
                    <i class="bi bi-chevron-down hidden lg:inline-block"></i>
                </label>
                <!-- Dropdown Menu -->
                <ul tabindex="0"
                    class="dropdown-content z-50 menu p-2 shadow bg-base-100 rounded-box w-full lg:w-56 mt-1">
                    <li>
                        <a wire:click="setStatusFilter('')" class="{{ !$selectedStatus ? 'bg-base-200' : '' }}">Semua
                            Status</a>
                    </li>
                    <li>
                        <a wire:click="setStatusFilter('Menunggu')"
                           class="{{ $selectedStatus === 'Menunggu' ? 'bg-base-200' : '' }}">Menunggu</a>
                    </li>
                    <li>
                        <a wire:click="setStatusFilter('Diproses')"
                           class="{{ $selectedStatus === 'Diproses' ? 'bg-base-200' : '' }}">Diproses</a>
                    </li>
                    <li>
                        <a wire:click="setStatusFilter('Selesai')"
                           class="{{ $selectedStatus === 'Selesai' ? 'bg-base-200' : '' }}">Selesai</a>
                    </li>
                </ul> <!-- End Dropdown Menu -->
            </div> <!-- End Filter Status -->
        </div> <!-- End Grup Pencarian dan Filter Status -->
    </div> <!-- End Grup Pencarian dan Filter Status -->

    <!-- Indikator Loading -->
    <div wire:loading.flex class="w-full justify-center items-center py-8">
        <span class="loading loading-spinner loading-lg text-primary"></span>
    </div>

    <!-- Daftar Perbaikan Fasilitas -->
    <div wire:loading.remove>
        <div class="grid grid-cols-1 gap-4 lg:hidden">
            @forelse ($perbaikan as $item)
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    {{-- Baris Atas: Kode & Status --}}
                    <div class="flex justify-between items-start gap-4">
                        <span
                            class="font-mono text-sm font-bold text-gray-800 break-all">{{ $item['kode_perbaikan'] }}</span>
                        @php
                            $warnaBadge = ['Menunggu' => 'badge-warning', 'Diproses' => 'badge-primary', 'Selesai' => 'badge-success'][$item['status']] ?? 'badge-ghost';
                        @endphp
                        <span class="badge {{ $warnaBadge }} text-white font-semibold">{{ $item['status'] }}</span>
                    </div>

                    {{-- Konten Utama --}}
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="font-medium text-gray-800 break-words"
                               title="{{ $item['deskripsi_masalah'] }}">{{ Str::limit($item['deskripsi_masalah'], 120) }}</p>
                            <p class="text-xs text-gray-500 mt-1 break-words">{{ $item['fasilitas_nama'] }}
                                - {{ $item['gedung_nama'] }} {{ $item['ruang_nama'] }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-500">Teknisi:</p>
                            @if (!empty($item['teknisi_collection']) && $item['jumlah_teknisi'] > 0)
                                <p class="text-gray-800 break-words">{{ $item['teknisi_collection']->pluck('nama')->join(', ') }}</p>
                            @else
                                <p class="text-gray-400 italic">Belum ditugaskan</p>
                            @endif
                        </div>
                    </div>

                    <!-- Baris Bawah: Tanggal & Aksi -->
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500">{{ date('d M Y, H:i', strtotime($item['tanggal_perbaikan'])) }}</p>
                        <button wire:click="goToDetail('{{ $item['id'] }}')" class="btn btn-sm btn-outline btn-primary">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-1 text-center py-10">
                    @include('livewire.includes.empty-state-perbaikan')
                </div>
            @endforelse
        </div>

        <!-- Tabel Perbaikan Fasilitas (Desktop) -->
        <div class="hidden lg:block overflow-x-auto rounded-xl border border-gray-200 w-full">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content">
                <tr>
                    <th class="text-center w-[5%]">No</th>
                    <th class="w-[15%]">Kode Perbaikan</th>
                    <th class="w-[30%]">Informasi Perbaikan</th>
                    <th class="w-[15%]">Tanggal</th>
                    <th class="w-[15%]">Teknisi</th>
                    <th class="w-[10%]">Status</th>
                    <th class="text-center w-[10%]">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($perbaikan as $index => $item)
                    <tr class="hover">
                        <td class="text-center align-top">{{ $perbaikanData->firstItem() + $index }}</td>
                        <td class="text-start align-top">
                            <span
                                class="font-mono text-xs px-2 py-1 bg-gray-100 rounded whitespace-nowrap">{{ $item['kode_perbaikan'] }}</span>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col">
                                <span class="font-medium truncate"
                                      title="{{ $item['deskripsi_masalah'] }}">{{ $item['deskripsi_masalah'] }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 truncate">
                                            {{ $item['fasilitas_nama'] }} - {{ $item['gedung_nama'] }} {{ $item['ruang_nama'] }}
                                        </span>
                                </div>
                            </div>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col justify-start items-start">
                                <span>{{ date('d M Y', strtotime($item['tanggal_perbaikan'])) }}</span>
                                <span
                                    class="text-xs text-gray-500">{{ date('H:i', strtotime($item['tanggal_perbaikan'])) }}</span>
                            </div>
                        </td>
                        <td class="align-top">
                            @if (!empty($item['teknisi_collection']) && $item['jumlah_teknisi'] > 0)
                                @if ($item['jumlah_teknisi'] == 1)
                                    <span>{{ $item['teknisi_nama'] }}</span>
                                @else
                                    <div class="text-sm text-gray-800">
                                        {{ $item['teknisi_collection']->pluck('nama')->join(', ') }}
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="align-top">
                            @php
                                $warnaBadge = ['Menunggu' => 'badge-warning', 'Diproses' => 'badge-primary', 'Selesai' => 'badge-success'][$item['status']] ?? 'badge-ghost';
                            @endphp
                            <span class="badge {{ $warnaBadge }} text-white font-semibold">{{ $item['status'] }}</span>
                        </td>
                        <td class="text-center align-top">
                            <div class="flex items-center justify-center">
                                <button wire:click="goToDetail('{{ $item['id'] }}')"
                                        class="btn btn-sm btn-ghost text-primary" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            @include('livewire.includes.empty-state-perbaikan')
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Pagination -->
    <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:justify-between items-center w-full">
        <div class="text-sm text-gray-500">
            @if ($perbaikanData->total() > 0)
                Menampilkan {{ $perbaikanData->firstItem() }} - {{ $perbaikanData->lastItem() }}
                dari {{ $perbaikanData->total() }} data
            @else
                Tidak ada data untuk ditampilkan
            @endif
        </div>
        @if ($perbaikanData->hasPages())
            <div class="join">
                @if ($perbaikanData->onFirstPage())
                    <button class="join-item btn btn-sm btn-disabled">«</button>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" class="join-item btn btn-sm">«
                    </button>
                @endif

                @php
                    $startPage = max($page - 1, 1);
                    $endPage = min($startPage + 2, $perbaikanData->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($i = $startPage; $i <= $endPage; $i++)
                    @if ($i == $perbaikanData->currentPage())
                        <button class="join-item btn btn-sm btn-active">{{ $i }}</button>
                    @else
                        <button wire:click="gotoPage({{ $i }})" wire:loading.attr="disabled"
                                class="join-item btn btn-sm">{{ $i }}</button>
                    @endif
                @endfor

                @if ($perbaikanData->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" class="join-item btn btn-sm">»</button>
                @else
                    <button class="join-item btn btn-sm btn-disabled">»</button>
                @endif
            </div>
        @endif
    </div>
</div>

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('showSuccessToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3"><i class="bi bi-check-circle-fill text-xl"></i></div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    escapeMarkup: false,
                    style: {
                        minWidth: "300px"
                    },
                    onClick: function () {
                    }
                }).showToast();
            });
            Livewire.on('showErrorToast', (message) => {
                Toastify({
                    onClick: function () {
                    }
                }).showToast();
            });
        });
    </script>
@endpush
