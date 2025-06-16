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

    {{-- search --}}
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari berdasarkan nama pelapor, judul laporan, atau fasilitas..." />
        </div>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full relative" id="laporan-table">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">ID</th>
                    <th>Judul Laporan</th>
                    <th>Pelapor</th>
                    
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table as $laporan)
                    <tr class="hover">
                        <td class="flex gap-2 justify-center">{{ $laporan->id ?? $laporan->pelaporan_id }}</td>
                        <td>
                            <div class="font-medium">
                                {{ Str::limit($laporan->judul_laporan ?? 'Tidak ada judul', 30) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($laporan->deskripsi_laporan ?? 'Tidak ada deskripsi', 50) }}
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($laporan->user->nama ?? 'Unknown') }}&background=4338ca&color=fff"
                                    alt="{{ $laporan->user->nama ?? 'Unknown' }}" loading="lazy">
                                <div>
                                    <div class="font-medium">{{ $laporan->user->nama ?? 'Tidak diketahui' }}</div>
                                    <div class="text-sm text-gray-500">{{ $laporan->user->email ?? 'Email tidak tersedia' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @php
                                $latestStatus = $laporan->statusPelaporan->sortByDesc('created_at')->first();
                                $statusClass = match($latestStatus->nama ?? 'pending') {
                                    'selesai', 'completed' => 'badge-success',
                                    'dalam_proses', 'in_progress' => 'badge-warning',
                                    'pending' => 'badge-info',
                                    'ditolak', 'rejected' => 'badge-error',
                                    default => 'badge-ghost'
                                };
                            @endphp
                            <div class="badge {{ $statusClass }}">
                                {{ $latestStatus->nama ?? 'Pending' }}
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : 'Tidak diketahui' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $laporan->created_at ? $laporan->created_at->format('H:i') : '' }}
                            </div>
                        </td>
                        <td class="flex gap-2 justify-center">
                            <a href="#" class="text-blue-400 hover:text-blue-800" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="text-indigo-400 hover:text-indigo-800" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="#" class="text-red-500 hover:text-red-700" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $table->firstItem() ?? 0 }} - {{ $table->lastItem() ?? 0 }} dari {{ $table->total() }} hasil
        </div>
        <div class="join">
            {{-- Previous Page Link --}}
            @if($table->onFirstPage())
                <button class="join-item btn btn-sm" disabled>«</button>
            @else
                <button class="join-item btn btn-sm" wire:click="previousPage">«</button>
            @endif

            {{-- Pagination Elements --}}
            @php
                $startPage = max($table->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $table->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <button class="join-item btn btn-sm {{ $table->currentPage() == $page ? 'btn-active' : '' }}"
                        wire:click="gotoPage({{ $page }})">
                    {{ $page }}
                </button>
            @endfor

            {{-- Next Page Link --}}
            @if($table->hasMorePages())
                <button class="join-item btn btn-sm" wire:click="nextPage">»</button>
            @else
                <button class="join-item btn btn-sm" disabled>»</button>
            @endif
        </div>
    </div>
</div>