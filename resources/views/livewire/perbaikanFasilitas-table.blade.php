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
                    <th>Tanggal</th>
                    <th>Teknisi yang Ditugaskan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perbaikan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-start">{{ $item['kode_perbaikan'] }}</td>
                        <td>{{ $item['deskripsi_masalah'] }} di {{ $item['gedung_nama'] }} {{ $item['ruang_nama'] }}
                        </td>
                        <td class="">{{ date('d M Y', strtotime($item['tanggal_lapor'])) }}</td>
                        <td class="">
                            <span class="px-3 py-1 rounded-full">
                                {{ $item['teknisi_nama'] ?? '-' }}
                            </span>
                        </td>
                        <td class="">
                            @php
                                $warnaBadge = match ($item['status']) {
                                    'Diproses' => 'bg-blue-500',
                                    'Selesai' => 'bg-green-500',
                                    default => 'bg-gray-400',
                                };

                                $statusText = match ($item['status']) {
                                    'Diproses' => 'Diproses',
                                    'Selesai' => 'Selesai',
                                    default => ucfirst($item['status']),
                                };
                            @endphp

                            <span class="badge text-white px-3 py-1 rounded-full {{ $warnaBadge }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="text-center flex items-center justify-center gap-4">
                            <a href="{{ route('detail-perbaikan') }}" class="flex items-center gap-1 justify-center">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data perbaikan fasilitas ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{-- @if ($perbaikan->hasPages()) --}}
    {{-- <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $perbaikan->firstItem() }} - {{ $perbaikan->lastItem() }} dari {{ $perbaikan->total() }}
            hasil
        </div>
        <div class="join">
            @php
                $startPage = max($perbaikan->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $perbaikan->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                    <button class="join-item btn btn-sm {{ $perbaikan->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endfor
        </div>
    </div> --}}
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
            Livewire.on('perbaikanCreated', () => {
                // Force table height recalculation if needed
                setTimeout(() => {
                    const content = document.getElementById('perbaikanCardContent');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                }, 200);
            });

            Livewire.on('perbaikanUpdated', () => {
                // Event handling if needed
            });

            Livewire.on('perbaikanDeleted', () => {
                // Event handling if needed
            });
        });
    </script>
@endpush
