<div class="w-full"> {{-- search --}}
    <div class="mb-4 flex flex-wrap items-center gap-2 w-full">
        <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
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
        <div class="flex items-center">
            <div class="dropdown">
                <label tabindex="0"
                    class="btn {{ $selectedStatus ? 'btn-primary text-white' : 'btn-outline' }} gap-2">
                    {{ $selectedStatus ?: 'Semua Status' }}
                    <i class="bi bi-chevron-down"></i>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
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
                </ul>
            </div>
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
                    @if (preg_match('/^fasilitas_id:(\d+)$/', $search))
                        <span class="flex items-center">
                            <i class="bi bi-filter-circle-fill mr-1"></i>
                            Menampilkan semua perbaikan untuk:
                            <span
                                class="font-semibold ml-1">{{ $this->getFacilityNameFromSearch() ?? 'Fasilitas terpilih' }}</span>
                        </span>
                    @else
                        <span>Pencarian: "{{ $search }}"</span>
                    @endif
                    <button wire:click="clearSearch" class="ml-2 hover:text-red-500">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Table Perbaikan --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 w-full">
        <table class="table table-zebra w-full table-fixed">
            <thead class="bg-base-200 text-base-content">
                <tr>
                    <th class="text-center w-[5%]">No</th>
                    <th class="w-[15%]">
                        <div class="flex items-center gap-1">
                            Kode Perbaikan
                        </div>
                    </th>
                    <th class="w-[30%]">
                        <div class="flex items-center gap-1">
                            Informasi Perbaikan
                        </div>
                    </th>
                    <th class="w-[15%]">
                        <div class="flex justify-start items-center gap-1">
                            Tanggal
                        </div>
                    </th>
                    <th class="w-[15%]">Teknisi</th>
                    <th class="w-[10%]">
                        <div class="flex items-center gap-1">
                            Status
                        </div>
                    </th>
                    <th class="text-center w-[10%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perbaikan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-start">
                            <span
                                class="font-mono text-xs px-2 py-1 bg-gray-100 rounded whitespace-nowrap">{{ $item['kode_perbaikan'] }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-medium truncate"
                                    title="{{ $item['deskripsi_masalah'] }}">{{ $item['deskripsi_masalah'] }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 truncate">
                                        {{ $item['fasilitas_nama'] }} - {{ $item['gedung_nama'] }}
                                        {{ $item['ruang_nama'] }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col justify-start items-start">
                                <span>{{ date('d M Y', strtotime($item['tanggal_perbaikan'])) }}</span>
                                <span>{{ date('H:i', strtotime($item['tanggal_perbaikan'])) }}</span>
                            </div>
                        </td>
                        <td>
                            @if (!empty($item['teknisi_collection']) && $item['jumlah_teknisi'] > 0)
                                <div class="flex flex-col gap-1">
                                    @if ($item['jumlah_teknisi'] == 1)
                                        <span>{{ $item['teknisi_nama'] }}</span>
                                    @else
                                        <div>
                                            <span class="font-medium">{{ $item['jumlah_teknisi'] }} teknisi:</span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item['teknisi_collection']->pluck('nama')->join(', ') }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $warnaBadge =
                                    [
                                        'Menunggu' => 'bg-yellow-500',
                                        'Diproses' => 'bg-blue-500',
                                        'Selesai' => 'bg-green-500',
                                    ][$item['status']] ?? 'bg-gray-400';
                                $statusText =
                                    [
                                        'Menunggu' => 'Menunggu',
                                        'Diproses' => 'Diproses',
                                        'Selesai' => 'Selesai',
                                    ][$item['status']] ?? ucfirst($item['status']);
                            @endphp
                            <span class="badge text-white px-3 py-1 rounded-full {{ $warnaBadge }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-4">
                                    <button wire:click="goToDetail('{{ $item['id'] }}')"
                                        class="text-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-lg font-medium">Tidak ada data perbaikan ditemukan</span>
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

    {{-- Pagination --}}
    @if (isset($perbaikanData) && $perbaikanData->hasPages())
        <div class="mt-4 flex justify-between items-center w-full">
            <div class="flex items-center">
                <span class="text-sm text-gray-500 mr-2">Data per halaman:</span>
                <select wire:model.live="perPage" class="select select-sm select-bordered">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-500 ml-4">
                    Menampilkan {{ $perbaikanData->firstItem() }} - {{ $perbaikanData->lastItem() }} dari
                    {{ $perbaikanData->total() }} data
                </span>
            </div>
            <div class="join">
                @if ($perbaikanData->onFirstPage())
                    <button class="join-item btn btn-disabled">«</button>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" class="join-item btn">«</button>
                @endif
                @foreach (range(1, $perbaikanData->lastPage()) as $page)
                    @if ($page == $perbaikanData->currentPage())
                        <button class="join-item btn btn-active">{{ $page }}</button>
                    @else
                        <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                            class="join-item btn">{{ $page }}</button>
                    @endif
                @endforeach
                @if ($perbaikanData->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" class="join-item btn">»</button>
                @else
                    <button class="join-item btn btn-disabled">»</button>
                @endif
            </div>
        </div>
    @endif

    @push('skrip')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                        onClick: function() {}
                    }).showToast();
                });
                Livewire.on('showErrorToast', (message) => {
                    Toastify({
                        onClick: function() {}
                    }).showToast();
                });
            });
        </script>
    @endpush
</div>
