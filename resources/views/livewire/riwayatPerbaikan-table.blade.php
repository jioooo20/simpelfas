<div>
    <!-- Grup Pencarian dan Filter -->
    <div class="mb-6 flex flex-col lg:flex-row items-center gap-4 w-full">

        <!-- Grup Pencarian -->
        <div class="relative w-full lg:flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-search text-gray-500"></i>
        </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Cari kode, masalah, atau teknisi..."
                   class="w-full h-10 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400"/>
            @if ($search)
                <button wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            @endif
        </div> <!-- End Grup Pencarian -->

        <!-- Grup Filter Status -->
        <div class="w-full lg:w-auto">
            @if(isset($teknisiList))
                <div class="dropdown dropdown-end w-full lg:w-auto">
                    <label tabindex="0"
                           class="btn {{ isset($selectedTeknisi) && $selectedTeknisi ? 'btn-primary text-white' : 'btn-outline border-gray-300' }} gap-2 w-full lg:w-auto">
                        <span
                            class="truncate">{{ isset($selectedTeknisi) && $selectedTeknisi ? ($teknisiList->firstWhere('user_id', $selectedTeknisi)?->nama ?? 'Teknisi') : 'Semua Teknisi' }}</span>
                        <i class="bi bi-chevron-down hidden lg:inline-block"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content z-50 menu p-2 shadow bg-base-100 rounded-box w-full lg:w-56 mt-1">
                        <li>
                            <a wire:click="setTeknisiFilter('')"
                               class="{{ empty($selectedTeknisi) ? 'bg-base-200' : '' }}">Semua Teknisi</a>
                        </li>
                        @foreach ($teknisiList as $teknisi)
                            <li>
                                <a wire:click="setTeknisiFilter('{{ $teknisi->user_id }}')"
                                   class="{{ (isset($selectedTeknisi) && $selectedTeknisi == $teknisi->user_id) ? 'bg-base-200' : '' }}">{{ $teknisi->nama }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> <!-- End Grup Filter Status -->
    </div> <!-- End Grup Pencarian dan Filter -->

    <!-- Indikator Loading -->
    <div wire:loading.flex class="w-full justify-center items-center py-8">
        <span class="loading loading-spinner loading-lg text-primary"></span>
    </div> <!-- End Indikator Loading -->

    <!-- Main Content -->
    <div wire:loading.remove>
        <!-- Tampilan Kartu untuk MOBILE -->
        <div class="grid grid-cols-1 gap-4 lg:hidden">
            @forelse ($riwayatPerbaikan as $item)
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <!-- Header Card -->
                    <div>
                        {{-- BARIS 1: KODE LAPORAN (lebar penuh) --}}
                        <p class="font-bold text-gray-800 text-base break-words">
                            {{ $item->latestCode ?? $item->perbaikan->perbaikan_kode }}
                        </p>

                        {{-- BARIS 2: Tanggal dan Status --}}
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500">
                                {{ date('d M Y, H:i', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}
                            </span>
                            @php
                                $warnaBadge = [ 'Selesai' => 'badge-success' ][$item->perbaikan_status] ?? 'badge-ghost';
                            @endphp
                            <span class="badge {{ $warnaBadge }} text-white font-semibold">
                                {{ $item->perbaikan_status }}
                            </span>
                        </div>
                    </div> <!-- End Header Card -->


                    <!-- Deskripsi Perbaikan dan Teknisi -->
                    <div class="space-y-3 text-sm pt-3 border-t">
                        <div>
                            <p class="font-semibold text-gray-500 mb-1">Perbaikan:</p>
                            <p class="text-gray-800 break-words"
                               title="{{ $item->perbaikan->pelaporan->pelaporan_deskripsi }}">{{ Str::limit($item->perbaikan->pelaporan->pelaporan_deskripsi, 100) }}</p>
                            <p class="text-xs text-gray-500 mt-1 break-words">
                                {{ $item->perbaikan->pelaporan->fasilitas->barang->barang_nama ?? '-' }} -
                                {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}
                                {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->lantai_nama ?? '' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-500 mb-1">Teknisi:</p>
                            @php $teknisi = $item->perbaikan->perbaikanPetugas ?? collect(); @endphp
                            @if ($teknisi->count() > 0)
                                <p class="text-gray-800 break-words">{{ $teknisi->pluck('user.nama')->join(', ') }}</p>
                            @else
                                <p class="text-gray-400">Belum ditugaskan</p>
                            @endif
                        </div>
                    </div> <!-- End Deskripsi Perbaikan dan Teknisi -->

                    {{-- Aksi Kartu --}}
                    <div class="flex justify-end pt-3 border-t border-gray-100">
                        <button wire:click="goToDetail('{{ $item->perbaikan->perbaikan_id }}')"
                                class="btn btn-sm btn-primary btn-outline">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            @empty
                {{-- State Kosong untuk Mobile --}}
                <div class="col-span-1 py-10">
                    @include('livewire.includes.empty-state-riwayat')
                </div>
            @endforelse
        </div>

        {{-- Tampilan TABEL untuk DESKTOP (Tidak ada perubahan di sini) --}}
        <div class="hidden lg:block overflow-x-auto rounded-xl border border-gray-200 w-full">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content">
                <tr>
                    <th class="text-center w-[5%]">No</th>
                    <th class="w-[15%]">Kode Perbaikan</th>
                    <th class="w-[30%]">Perbaikan</th>
                    <th class="w-[15%]">Tanggal Selesai</th>
                    <th class="w-[15%]">Teknisi</th>
                    <th class="w-[10%]">Status</th>
                    <th class="text-center w-[10%]">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($riwayatPerbaikan as $index => $item)
                    <tr class="hover">
                        <td class="text-center align-top">{{ $riwayatPerbaikan->firstItem() + $index }}</td>
                        <td class="text-start align-top">
                            <span
                                class="font-mono text-xs px-2 py-1 bg-gray-100 rounded whitespace-nowrap">{{ $item->latestCode ?? $item->perbaikan->perbaikan_kode }}</span>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800"
                                      title="{{ $item->perbaikan->pelaporan->pelaporan_deskripsi }}">{{ Str::limit($item->perbaikan->pelaporan->pelaporan_deskripsi, 70) }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 truncate">
                                            {{ $item->perbaikan->pelaporan->fasilitas->barang->barang_nama ?? '-' }} -
                                            {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}
                                            {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->lantai_nama ?? '' }}
                                        </span>
                                </div>
                            </div>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col justify-start items-start">
                                <span>{{ date('d M Y', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}</span>
                                <span
                                    class="text-xs text-gray-500">{{ date('H:i', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}</span>
                            </div>
                        </td>
                        <td class="align-top">
                            @php $teknisi = $item->perbaikan->perbaikanPetugas ?? collect(); @endphp
                            @if ($teknisi->isNotEmpty())
                                <div class="text-sm text-gray-800">
                                    {{ $teknisi->pluck('user.nama')->join(', ') }}
                                </div>
                            @else
                                <span class="text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="align-top">
                            @php
                                $warnaBadge = [ 'Selesai' => 'badge-success' ][$item->perbaikan_status] ?? 'badge-ghost';
                            @endphp
                            <span
                                class="badge {{ $warnaBadge }} text-white font-semibold">{{ $item->perbaikan_status }}</span>
                        </td>
                        <td class="text-center align-top">
                            <button wire:click="goToDetail('{{ $item->perbaikan->perbaikan_id }}')"
                                    class="btn btn-sm btn-ghost text-primary" title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            @include('livewire.includes.empty-state-riwayat')
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            @if (method_exists($riwayatPerbaikan, 'hasPages') && $riwayatPerbaikan->hasPages())
                {{ $riwayatPerbaikan->links('vendor.livewire.tailwind-custom') }}
            @endif
        </div>
    </div>
</div>

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    onClick: function () {
                    }
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
                    onClick: function () {
                    }
                }).showToast();
            });
        });
    </script>
@endpush
