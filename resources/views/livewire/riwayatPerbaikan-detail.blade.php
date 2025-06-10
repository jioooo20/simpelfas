<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Section 1: Informasi Perbaikan -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Perbaikan
                </h2>

                <div class="mt-4">
                    <div class="mb-3 flex flex-col">
                        <span class="font-semibold">Status :</span>
                        <span class="badge badge-primary mt-1 text-white">{{ $perbaikan['status'] }}</span>
                    </div>                    <div class="mb-3">
                        <span class="font-semibold">Kode Perbaikan :</span>
                        <div class="flex items-center">
                            <p>{{ $perbaikan['kode'] }}</p>
                            @if (strpos($perbaikan['kode'], '-') !== false && preg_match('/-\d+[A-Z]*$/i', $perbaikan['kode']))
                            @endif
                        </div>
                    </div>                    <div class="mb-3">
                        <span class="font-semibold">Tanggal Dibuat :</span>
                        <p>{{ $perbaikan['created_at'] }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">{{ $perbaikan['status'] === 'Selesai' ? 'Tanggal Selesai :' : 'Terakhir Update :' }}</span>
                        <p>{{ $perbaikan['updated_at'] }}</p>
                        @if($perbaikan['status'] === 'Selesai' && isset($perbaikan['completion_date']))
                            <span class="badge badge-sm badge-success text-white mt-1">Perbaikan Selesai</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>        <!-- Section 2: Informasi Laporan -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Laporan
                </h2>

                <div class="mt-4">
                    <div class="mb-3">
                        <span class="font-semibold">Lokasi :</span>
                        <p>{{ $pelaporanInfo['lokasi'] }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Fasilitas :</span>
                        <p>{{ $pelaporanInfo['fasilitas'] }}</p>
                    </div>                    <div class="mb-3">
                        <span class="font-semibold">Deskripsi Masalah :</span>
                        <p class="text-sm">{{ $pelaporanInfo['deskripsi'] }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Total Laporan :</span>
                        <p>{{ $pelaporanInfo['total_laporan'] ?? 1 }} laporan</p>
                    </div>
                </div>
            </div>
        </div>        <!-- Section 3: Informasi Teknisi -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Teknisi
                </h2>

                <div class="mt-4">
                    <div class="mb-3">
                        <span class="font-semibold">Nama Teknisi :</span>
                        <p>{{ $teknisiInfo['nama'] }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="font-semibold">Deskripsi Perbaikan :</span>
                        <p class="text-sm">{{ $teknisiInfo['deskripsi_perbaikan'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Dokumentasi Foto -->    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Dokumentasi Foto</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Selalu tampilkan card untuk status Dilaporkan --}}
            <div class="card bg-base-100 shadow-lg">
                <figure class="px-4 pt-4">
                    @php
                        $dilaporkanImage = collect($documentationImages)->firstWhere('status', 'Dilaporkan');
                    @endphp
                    
                    @if ($dilaporkanImage)
                        <img src="{{ $dilaporkanImage['url'] }}" alt="Foto Dilaporkan"
                            class="rounded-lg h-48 w-full object-cover cursor-pointer"
                            onclick="openImageModal('{{ $dilaporkanImage['url'] }}', 'Dilaporkan')">
                    @else
                        <div class="rounded-lg h-48 w-full flex items-center justify-center bg-gray-100">
                            <p class="text-gray-500 text-center">Foto tidak tersedia</p>
                        </div>
                    @endif
                </figure>
                <div class="card-body pt-2">
                    <h3 class="card-title text-md">Foto Dilaporkan</h3>
                    <p class="text-sm text-gray-500">
                        {{ $dilaporkanImage['tanggal'] ?? $historyInfo[0]['tanggal'] ?? now()->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm">
                        {{ $dilaporkanImage['keterangan'] ?? 'Dokumentasi saat kerusakan dilaporkan' }}
                    </p>
                </div>
            </div>
            
            {{-- Tampilkan card untuk status lainnya --}}
            @foreach ($documentationImages as $image)
                @if ($image['status'] !== 'Dilaporkan')
                    <div class="card bg-base-100 shadow-lg">
                        <figure class="px-4 pt-4">
                            <img src="{{ $image['url'] }}" alt="Foto {{ $image['status'] }}"
                                class="rounded-lg h-48 w-full object-cover cursor-pointer"
                                onclick="openImageModal('{{ $image['url'] }}', '{{ $image['status'] }}')">
                        </figure>
                        <div class="card-body pt-2">
                            <h3 class="card-title text-md">Foto {{ $image['status'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $image['tanggal'] }}</p>
                            <p class="text-sm">{{ $image['keterangan'] }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
            
            @if (count($documentationImages) <= 1 && !collect($documentationImages)->firstWhere('status', 'Dilaporkan'))
                <div class="col-span-2 flex items-center justify-center py-6">
                    <p class="text-gray-500">Tidak ada dokumentasi foto tambahan yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
    <!-- Histori update perbaikan -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historyInfo as $history)
                        <tr>
                            <td>{{ $history['tanggal'] }}</td>
                            <td>
                                @php
                                    $badgeClass = match($history['status']) {
                                        'Dilaporkan' => 'badge-ghost',
                                        'Diverifikasi' => 'badge-info',
                                        'Ditugaskan' => 'badge-info',
                                        'Dalam Proses' => 'badge-warning',
                                        'Menunggu Komponen' => 'badge-primary',
                                        'Selesai' => 'badge-success',
                                        'Dibatalkan' => 'badge-error',
                                        default => 'badge-neutral'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $history['status'] }}</span>
                            </td>
                            <td>{{ $history['keterangan'] }}</td>
                            <td>{{ $history['oleh'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada histori perbaikan yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        <!-- Modal Foto -->
    <dialog id="image_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-3xl">
            <h3 id="modal-title" class="font-bold text-lg mb-4">Foto</h3>
            <div class="flex justify-center">
                <img id="modal-image" src="https://placehold.co/400x300" alt="Preview" class="max-h-96 rounded-lg">
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Tutup</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>        </form>
    </dialog>

    <script>
        function openImageModal(url, status) {
            const modal = document.getElementById('image_modal');
            const modalImage = document.getElementById('modal-image');
            const modalTitle = document.getElementById('modal-title');
            
            if (url) {
                modalImage.src = url;
                modalTitle.textContent = 'Foto ' + status;
                modal.showModal();
            }
        }
    </script>
</div>
