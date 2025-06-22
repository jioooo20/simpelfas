<div>
    <div class="mb-6">
        <a href="{{ route('riwayat-perbaikan') }}" {{-- GANTI DENGAN ROUTE ANDA, CONTOH: href="{{ route('halaman.sebelumnya') }}" --}}
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- Kartu Informasi Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ========================================================== --}}
        {{-- KARTU 1: INFORMASI PERBAIKAN (DIDISAIN ULANG) --}}
        {{-- ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Perbaikan
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Status</p>
                    <p class="mt-1">
                        <span class="badge badge-primary text-white text-sm p-3">{{ $perbaikan['status'] }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Kode Perbaikan</p>
                    <p class="mt-1 font-mono text-slate-900">{{ $perbaikan['kode'] }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Tanggal Dibuat</p>
                    <p class="mt-1 text-slate-900">{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $perbaikan['created_at'])->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">{{ $perbaikan['status'] === 'Selesai' ? 'Tanggal Selesai' : 'Terakhir Update' }}</p>
                    <p class="mt-1 text-slate-900">{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $perbaikan['updated_at'])->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- KARTU 2: INFORMASI LAPORAN (DIDISAIN ULANG) --}}
        {{-- ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Laporan
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Lokasi</p>
                    <p class="mt-1 text-slate-900">{{ $pelaporanInfo['lokasi'] }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Fasilitas</p>
                    <p class="mt-1 text-slate-900">{{ $pelaporanInfo['fasilitas'] }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Deskripsi Masalah</p>
                    <p class="mt-1 text-slate-900">{{ $pelaporanInfo['deskripsi'] }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Total Laporan</p>
                    <p class="mt-1 text-slate-900">{{ $pelaporanInfo['total_laporan'] ?? 1 }} laporan</p>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- KARTU 3: INFORMASI TEKNISI (DIDISAIN ULANG) --}}
        {{-- ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Teknisi
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Nama Teknisi</p>
                    <p class="mt-1 text-slate-900">{{ $teknisiInfo['nama'] }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Deskripsi Perbaikan</p>
                    <p class="mt-1 text-slate-900">{{ $teknisiInfo['deskripsi_perbaikan'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Dokumentasi Foto</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- ========================================================= --}}
            {{-- AWAL BLOK FOTO YANG DIDESAIN ULANG & LEBIH ROBUST --}}
            {{-- ========================================================= --}}

            @php
                // Tentukan urutan status foto yang ingin ditampilkan
                $photoStatuses = ['Dilaporkan', 'Diproses', 'Selesai'];
            @endphp

            @foreach ($photoStatuses as $status)
                @php
                    // 1. Cari gambar yang sesuai dengan status saat ini dari data Anda
                    $image = collect($documentationImages)->firstWhere('status', $status);

                    $finalImageUrl = null;

                    // 2. Proses URL jika gambar ditemukan
                    if ($image && !empty($image['url'])) {
                        $rawUrl = $image['url'];
                        $url = $rawUrl;

                        // 3. Cek apakah URL adalah string JSON (untuk foto dummy)
                        if (is_string($rawUrl) && str_starts_with($rawUrl, '[') && str_ends_with($rawUrl, ']')) {
                            $decodedUrls = json_decode($rawUrl, true);
                            // Ambil URL pertama dari array
                            $url = $decodedUrls[0] ?? null;
                        }

                        // 4. Bangun path gambar final yang valid
                        if ($url) {
                            // Jika path sudah mengandung 'storage/', itu adalah path dummy/lengkap
                            if (str_starts_with($url, 'storage/')) {
                                $finalImageUrl = asset($url);
                            } else {
                                $finalImageUrl = asset('storage/' . $url);
                            }
                        }
                    }
                @endphp

                {{-- Selalu render satu kartu untuk setiap status --}}
                <div class="card bg-base-100 shadow-lg">
                    <figure class="px-4 pt-4">
                        {{-- 5. Tampilkan gambar jika URL valid, jika tidak, tampilkan placeholder --}}
                        @if ($finalImageUrl)
                            <img src="{{ $finalImageUrl }}" alt="Foto {{ $status }}"
                                 class="rounded-lg h-48 w-full object-cover cursor-pointer"
                                 onclick="openImageModal('{{ $finalImageUrl }}', '{{ $status }}')">
                        @else
                            {{-- Ini adalah placeholder jika foto tidak ada atau URL tidak valid --}}
                            <div
                                class="rounded-lg h-48 w-full flex items-center justify-center bg-gray-100 border-2 border-dashed">
                                <div class="text-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                         class="bi bi-image-alt mx-auto" viewBox="0 0 16 16">
                                        <path
                                            d="M7 2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0m4.225 4.053a.5.5 0 0 0-.577.093l-3.71 4.71-2.66-2.772a.5.5 0 0 0-.63.062L.002 13.5v-11a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v11-1.428z"/>
                                        <path
                                            d="M.002 14a1.5 1.5 0 0 0 1.5 1.5h12.996A1.5 1.5 0 0 0 16 14v-2.529l-4.26-4.228-2.66 2.772-2.796-3.543z"/>
                                    </svg>
                                    <p class="mt-2 text-sm">Foto Belum Tersedia</p>
                                </div>
                            </div>
                        @endif
                    </figure>
                    <div class="card-body pt-2">
                        <h3 class="card-title text-md">Foto {{ $status }}</h3>
                        <p class="text-sm text-gray-500">
                            {{-- Tampilkan tanggal jika gambar ada, jika tidak, tampilkan strip --}}
                            @if ($image && $image['tanggal'])
                                {{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $image['tanggal'])->translatedFormat('d F Y, H:i') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach

            {{-- ========================================================= --}}
            {{-- AKHIR BLOK FOTO YANG DIPERBAIKI --}}
            {{-- ========================================================= --}}

        </div>
    </div>

    <div class="mt-8 mb-10">
        <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>

        {{-- Tampilan TIMELINE untuk MOBILE --}}
        <div class="lg:hidden space-y-6">
            @foreach ($historyInfo as $row)
                <div class="flex gap-4">
                    {{-- Dot dan line --}}
                    <div class="flex flex-col items-center">
                        @if ($row['perbaikan_status'] == 'Selesai')
                            <div
                                class="w-5 h-5 rounded-full bg-success flex items-center justify-center ring-4 ring-success/30">
                                <i class="bi bi-check-lg text-white text-xs"></i>
                            </div>
                        @else
                            <div class="w-4 h-4 mt-1 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        @endif

                        @if (!$loop->last)
                            <div class="w-px h-full bg-gray-300 mt-2"></div>
                        @endif
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 pb-6">
                        @if ($row['perbaikan_status'] == 'Menunggu')
                            <span class="badge badge-info text-white">{{ $row['perbaikan_status'] }}</span>
                        @elseif ($row['perbaikan_status'] == 'Diproses')
                            <span class="badge badge-primary text-white">{{ $row['perbaikan_status'] }}</span>
                        @elseif ($row['perbaikan_status'] == 'Selesai')
                            <span class="badge badge-success text-white">{{ $row['perbaikan_status'] }}</span>
                        @else
                            <span class="badge badge-neutral text-white">{{ $row['perbaikan_status'] }}</span>
                        @endif

                        <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $row['tanggal'])->translatedFormat('d F Y, H:i') }}</p>

                        <div class="text-sm text-gray-700 mt-2">
                            @if ($row['perbaikan_status'] == 'Menunggu')
                                <p>Penugasan dibuat dan menunggu teknisi untuk melakukan perbaikan.</p>
                            @elseif ($row['perbaikan_status'] == 'Diproses')
                                <p>Teknisi sedang melakukan perbaikan.</p>
                            @elseif ($row['perbaikan_status'] == 'Selesai')
                                <p>Fasilitas telah selesai diperbaiki.</p>
                            @else
                                <p>Status perbaikan diperbarui.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tampilan TABEL untuk DESKTOP --}}
        <div class="hidden lg:block overflow-x-auto rounded-xl border">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                <tr>
                    <th class="w-1/5">Tanggal</th>
                    <th class="w-1/5">Status</th>
                    <th class="w-3/5">Keterangan</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($historyInfo as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $row['tanggal'])->translatedFormat('d F Y, H:i') }}</td>
                        <td>
                            @if ($row['perbaikan_status'] == 'Menunggu')
                                <span class="badge badge-info text-white">{{ $row['perbaikan_status'] }}</span>
                            @elseif ($row['perbaikan_status'] == 'Diproses')
                                <span class="badge badge-primary text-white">{{ $row['perbaikan_status'] }}</span>
                            @elseif ($row['perbaikan_status'] == 'Selesai')
                                <span class="badge badge-success text-white">{{ $row['perbaikan_status'] }}</span>
                            @else
                                <span class="badge badge-neutral text-white">{{ $row['perbaikan_status'] }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($row['perbaikan_status'] == 'Menunggu')
                                <p>Penugasan Perbaikan Fasilitas dibuat dan menunggu teknisi untuk melakukan
                                    perbaikan.</p>
                            @elseif ($row['perbaikan_status'] == 'Diproses')
                                <p>Teknisi sedang melakukan perbaikan.</p>
                            @elseif ($row['perbaikan_status'] == 'Selesai')
                                <p>Fasilitas telah selesai diperbaiki.</p>
                            @else
                                <p>Status perbaikan diperbarui.</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="image_modal" class="modal modal-middle">

        {{-- Kita kembalikan ke struktur sederhana tanpa flex-col pada modal-box --}}
        <div class="modal-box w-11/12 max-w-4xl p-0">

            {{-- Header Modal (tidak ada perubahan) --}}
            <div class="flex items-center justify-between p-4 border-b">
                <h3 id="modal-title" class="font-bold text-lg">Tampilan Gambar</h3>
                <button onclick="document.getElementById('image_modal').close()"
                        class="btn btn-sm btn-circle btn-ghost">
                    <i class="bi bi-x text-2xl"></i>
                </button>
            </div>

            {{-- Konten Gambar --}}
            <div class="p-4 flex justify-center items-center bg-slate-50">
                {{--
                  ================================================================
                  PERUBAHAN KUNCI DI SINI:
                  Tinggi maksimal gambar dibatasi menjadi 75% tinggi layar.
                  Ini secara eksplisit menyisakan 25% ruang untuk header, padding,
                  dan UI browser, sehingga scrollbar tidak akan muncul.
                  ================================================================
                --}}
                <img id="modal-image" src="" alt="Tampilan Gambar"
                     class="max-w-full max-h-[75vh] rounded-lg object-contain">
            </div>
        </div>

        {{-- Klik di luar untuk menutup --}}
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>

@push('skrip')
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
@endpush
