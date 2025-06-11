{{-- filepath: d:\laragon\www\simpelfas\resources\views\livewire\riwayatPerbaikan-table.blade.php --}}
<div> 
    {{-- search --}}
    <div class="mb-4 flex flex-col justify-between md:flex-row md:items-center md:justify-between gap-2">
        <div class="relative w-full max-w-[85%]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-search text-gray-500"></i>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Cari kode, masalah, atau teknisi..."
                class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400" />
            @if ($search)
                <button wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-circle"></i>
                </button>
            @endif
        </div>
        <div class="flex gap-2 mt-2 md:mt-0 max-w-[15%] w-full">
            {{-- Teknisi Filter --}}
            @if(isset($teknisiList))
                <div class="dropdown flex-col w-full">
                    <label tabindex="0" class="btn {{ isset($selectedTeknisi) && $selectedTeknisi ? 'btn-primary text-white' : 'btn-outline' }} gap-2">
                        {{ isset($selectedTeknisi) && $selectedTeknisi ? ($teknisiList->firstWhere('user_id', $selectedTeknisi)?->nama ?? 'Teknisi') : 'Semua Teknisi' }}
                        <i class="bi bi-chevron-down"></i>
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <a wire:click="setTeknisiFilter('')" class="{{ empty($selectedTeknisi) ? 'bg-base-200' : '' }}">Semua Teknisi</a>
                        </li>
                        @foreach ($teknisiList as $teknisi)
                            <li>
                                <a wire:click="setTeknisiFilter('{{ $teknisi->user_id }}')" class="{{ (isset($selectedTeknisi) && $selectedTeknisi == $teknisi->user_id) ? 'bg-base-200' : '' }}">{{ $teknisi->nama }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 w-full">
        <table class="table table-zebra w-full table-fixed">
            <thead class="bg-base-200 text-base-content">
                <tr>
                    <th class="text-center w-[5%]">No</th>
                    <th class="w-[15%]">
                        <div class="flex items-center gap-1">Kode Perbaikan</div>
                    </th>
                    <th class="w-[30%]">
                        <div class="flex items-center gap-1">Perbaikan</div>
                    </th>
                    <th class="w-[15%]">
                        <div class="flex justify-start items-center gap-1">Tanggal Selesai</div>
                    </th>
                    <th class="w-[15%]">Teknisi yang Ditugaskan</th>
                    <th class="w-[10%]">
                        <div class="flex items-center gap-1">Status</div>
                    </th>
                    <th class="text-center w-[10%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatPerbaikan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-start">
                            <span class="font-mono text-xs px-2 py-1 bg-gray-100 rounded whitespace-nowrap">{{ $item->latestCode ?? $item->perbaikan->perbaikan_kode }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-medium truncate" title="{{ $item->perbaikan->pelaporan->pelaporan_deskripsi }}">{{ $item->perbaikan->pelaporan->pelaporan_deskripsi }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 truncate">
                                        {{ $item->perbaikan->pelaporan->fasilitas->barang->barang_nama ?? '-' }} -
                                        {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}
                                        {{ $item->perbaikan->pelaporan->fasilitas->ruang->lantai->lantai_nama ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col justify-start items-start">
                                <span>{{ date('d M Y', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}</span>
                                <span class="text-xs text-gray-500">{{ date('H:i', strtotime($item['tanggal_selesai'] ?? $item['updated_at'])) }}</span>
                            </div>
                        </td>
                        <td>
                            @php $teknisi = $item->perbaikan->perbaikanPetugas ?? collect(); @endphp
                            @if ($teknisi->count() == 1)
                                <span>{{ $teknisi->first()->user->nama ?? '-' }}</span>
                            @elseif ($teknisi->count() > 1)
                                <div>
                                    <span class="font-medium">{{ $teknisi->count() }} teknisi:</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $teknisi->pluck('user.nama')->join(', ') }}
                                </div>
                            @else
                                <span class="text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $warnaBadge = [
                                    'Selesai' => 'bg-green-500',
                                ][$item->perbaikan_status] ?? 'bg-gray-400';
                                $statusText = [
                                    'Selesai' => 'Selesai',
                                ][$item->perbaikan_status] ?? ucfirst($item->perbaikan_status);
                            @endphp
                            <span class="badge text-white px-3 py-1 rounded-full {{ $warnaBadge }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-4">
                                <button wire:click="goToDetail('{{ $item->perbaikan->perbaikan_id }}')" class="text-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-lg font-medium">Tidak ada riwayat perbaikan fasilitas ditemukan</span>
                                <span class="text-sm">Coba cari dengan kata kunci lain atau reset filter</span>
                                @if ($search || $selectedStatus)
                                    <button wire:click="resetFilters" class="btn btn-sm btn-outline mt-3">
                                        <i class="bi bi-arrow-repeat mr-1"></i> Reset Filter
                                    </button>
                                @endif
                            </div>
                        </td>
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
                    $startPage = max($page - 1, 1);
                    $endPage = min($startPage + 2, $riwayatPerbaikan->lastPage());

                    if ($endPage - $startPage < 2) {
                        $startPage = max($endPage - 2, 1);
                    }
                @endphp

                @for ($i = $startPage; $i <= $endPage; $i++)
                    <button wire:click="gotoPage({{ $i }})" class="join-item btn btn-sm {{ $page == $i ? 'btn-active' : '' }}">
                        {{ $i }}
                    </button>
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
