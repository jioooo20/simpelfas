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

    <div>
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
    <table class="table">
        <thead>
            <tr>
                <th>Pelapor</th>
                <th>Judul Laporan</th>
                <th>Fasilitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($table as $laporan)
    @php
        // Mengambil status terakhir dari relasi
        $latestStatus = $laporan->statusPelaporan()->latest()->first();
        $status = $latestStatus->status_pelaporan ?? 'pending';
        
        $statusClass = match ($status) {
            'selesai' => 'badge-success',
            'dalam_proses' => 'badge-warning',
            'pending' => 'badge-info',
            'ditolak' => 'badge-error',
            default => 'badge-ghost',
        };
    @endphp
    <tr>
        <td>{{ $laporan->user->nama ?? '-' }}</td>
        <td>{{ $laporan->judul_laporan ?? '-' }}</td>
        <td>{{ $laporan->fasilitas->barang->nama ?? '-' }}</td>
        <td>
            <div class="badge {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </div>
        </td>
    </tr>
@endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $table->firstItem() ?? 0 }} - {{ $table->lastItem() ?? 0 }} dari {{ $table->total() }} hasil
        </div>
        <div class="join">
            {{-- Previous Page Link --}}
            @if ($table->onFirstPage())
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
            @if ($table->hasMorePages())
                <button class="join-item btn btn-sm" wire:click="nextPage">»</button>
            @else
                <button class="join-item btn btn-sm" disabled>»</button>
            @endif
        </div>
    </div>
</div>
