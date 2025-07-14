<div>
    <div class="mb-6">
        <a href="{{ url()->previous() }}" {{-- Kembali ke halaman sebelumnya --}}
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-md flex flex-col">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Perbaikan
            </h2>
            <div class="p-6 space-y-4 flex-grow">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Status</p>
                    <p class="mt-1">
                        @if ($statusTerakhir->perbaikan_status == 'Menunggu')
                            <span class="badge badge-warning text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Diproses')
                            <span class="badge badge-primary text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Selesai')
                            <span class="badge badge-success text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @else
                            <span class="badge badge-ghost">{{ $statusTerakhir->perbaikan_status ?? '-' }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Kode Perbaikan</p>
                    <p class="mt-1 font-mono text-slate-900">{{ $perbaikan->perbaikan_kode ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Tanggal Dibuat</p>
                    <p class="mt-1 text-slate-900">{{ $perbaikan->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Terakhir Update</p>
                    <p class="mt-1 text-slate-900">{{ ($statusTerakhir->created_at ?? $perbaikan->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="p-6 pt-0">
                @if ($statusTerakhir->perbaikan_status != 'Selesai')
                    <div class="card-actions justify-end pt-4 border-t">
                        @php
                            $isAssignedTechnician = false;
                            $userId = auth()->id();
                            if ($perbaikan->perbaikanPetugas && $perbaikan->perbaikanPetugas->count() > 0) {
                                foreach ($perbaikan->perbaikanPetugas as $petugas) {
                                    if ($petugas->user_id == $userId) {
                                        $isAssignedTechnician = true;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if ($isAssignedTechnician)
                            <button type="button" onclick="Livewire.dispatch('openUpdateModal')"
                                    class="btn btn-primary btn-sm text-white">Update Status
                            </button>
                        @else
                            <button type="button" disabled
                                    class="btn btn-primary btn-sm text-white opacity-50 cursor-not-allowed"
                                    title="Hanya teknisi yang ditugaskan yang dapat mengupdate status">Update Status
                            </button>
                        @endif
                    </div>
                    @livewire('perbaikan-update-form', ['perbaikanId' => $perbaikan->perbaikan_id],
                    key($perbaikan->perbaikan_id))
                @else
                    <div class="text-center pt-4 border-t">
                        <span class="text-gray-500 italic text-sm">Perbaikan telah selesai.</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b flex justify-between items-center">
                <span>Informasi Laporan</span>
                @if(!empty($pelaporanTerkait) && count($pelaporanTerkait) > 1)
                <span class="text-xs font-normal text-gray-500">{{ count($pelaporanTerkait) }} laporan terkait</span>
                @endif
            </h2>
            <div class="p-6">
                @if(empty($pelaporanTerkait) || count($pelaporanTerkait) <= 1)
                    <!-- TAMPILKAN INFORMASI LAPORAN UTAMA SAJA TANPA CAROUSEL -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold text-slate-800">Laporan Utama</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Lokasi</p>
                            <p class="mt-1 text-slate-900">{{ $lokasi ? (($lokasi->lantai->gedung->gedung_nama ?? '') . ' - ' . ($lokasi->lantai->lantai_nama ?? '') . '-' . ($lokasi->ruang_nama ?? '')) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Fasilitas</p>
                            <p class="mt-1 text-slate-900">{{ $fasilitas->barang->barang_nama ?? ''}} <span>{{ substr($fasilitas->fasilitas_kode, -2) }}</span> </p>
                            <p class="text-slate-900 text-xs">{{ $fasilitas->nama_barang ?? ($fasilitas->fasilitas_kode ?? '-') }}</p>                    
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Deskripsi Masalah</p>
                            <p class="mt-1 text-slate-900 break-words">{{ $perbaikan->pelaporan->pelaporan_deskripsi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Pelapor</p>
                            <p class="mt-1 text-slate-900">{{ $perbaikan->pelaporan->user->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Tanggal Laporan</p>
                            <p class="mt-1 text-slate-900">{{ isset($perbaikan->pelaporan->created_at) ? \Carbon\Carbon::parse($perbaikan->pelaporan->created_at)->translatedFormat('d F Y, H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Total Laporan Terkait</p>
                            <p class="mt-1 text-slate-900">1 laporan</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Bukti Foto</p>
                            <div class="mt-2">
                                @php
                                    $fotoArr = isset($perbaikan->pelaporan->pelaporan_gambar) ? json_decode($perbaikan->pelaporan->pelaporan_gambar, true) : null;
                                @endphp
                                @if(is_array($fotoArr) && count($fotoArr) > 0)
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openImageGalleryModal()" class="btn btn-sm btn-outline btn-primary">
                                            <i class="bi bi-images"></i> Lihat {{ count($fotoArr) }} Foto
                                        </button>
                                        <span class="text-xs text-gray-500">
                                            <i class="bi bi-info-circle"></i> Klik untuk melihat semua foto
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada foto</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- CAROUSEL DAN NAVIGASI JIKA LAPORAN > 1 -->
                    <div class="carousel w-full" id="info-carousel">
                        <!-- Hapus slide Utama, hanya render slide laporan terkait -->
                        @if(!empty($pelaporanTerkait) && count($pelaporanTerkait) > 1)
                            @foreach($pelaporanTerkait as $index => $pelaporan)
                            <div id="infoSlide{{ $index }}" class="carousel-item relative w-full" style="display: none;">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-slate-800">Laporan Terkait ({{ $index + 1 }}/{{ count($pelaporanTerkait) }})</span>
                                        <span class="badge badge-ghost text-xs">{{ $pelaporan->pelaporan_kode ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Lokasi</p>
                                        <p class="mt-1 text-slate-900">{{ $lokasi ? (($lokasi->lantai->gedung->gedung_nama ?? '') . ' - ' . ($lokasi->lantai->lantai_nama ?? '') . '-' . ($lokasi->ruang_nama ?? '')) : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Fasilitas</p>
                                        <p class="mt-1 text-slate-900">{{ $fasilitas->barang->barang_nama ?? ''}} <span>{{ substr($fasilitas->fasilitas_kode, -2) }}</span> </p>
                                        <p class="text-slate-900 text-xs">{{ $fasilitas->nama_barang ?? ($fasilitas->fasilitas_kode ?? '-') }}</p>                    
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Deskripsi Masalah</p>
                                        <p class="mt-1 text-slate-900 break-words">{{ $pelaporan->pelaporan_deskripsi ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Pelapor</p>
                                        <p class="mt-1 text-slate-900">{{ $pelaporan->user->nama ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Tanggal Laporan</p>
                                        <p class="mt-1 text-slate-900">{{ isset($pelaporan->created_at) ? \Carbon\Carbon::parse($pelaporan->created_at)->translatedFormat('d F Y, H:i') : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-500">Bukti Foto</p>
                                        <div class="mt-2">
                                            @php
                                                $fotoArr = isset($pelaporan->pelaporan_gambar) ? json_decode($pelaporan->pelaporan_gambar, true) : null;
                                            @endphp
                                            @if(is_array($fotoArr) && count($fotoArr) > 0)
                                                <div class="flex items-center gap-2">
                                                    <button type="button" onclick="openImageGalleryModal({{ $index }})" class="btn btn-sm btn-outline btn-primary">
                                                        <i class="bi bi-images"></i> Lihat {{ count($fotoArr) }} Foto
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Tidak ada foto</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- Navigasi carousel -->
                    @if(!empty($pelaporanTerkait) && count($pelaporanTerkait) > 1)
                    <div class="mt-4">
                        <div class="flex justify-around items-center gap-4 mb-4">
                            <button type="button" class="btn btn-circle btn-sm bg-black/20 hover:bg-black/40 text-white border-0 shadow-lg" 
                                    onclick="showInfoSlide(currentInfoSlide === 0 ? {{ count($pelaporanTerkait) - 1 }} : currentInfoSlide - 1)">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-circle btn-sm bg-black/20 hover:bg-black/40 text-white border-0 shadow-lg" 
                                    onclick="showInfoSlide(currentInfoSlide === {{ count($pelaporanTerkait) - 1 }} ? 0 : currentInfoSlide + 1)">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center border-t pt-4" id="info-carousel-controls">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500">
                                    <i class="bi bi-info-circle"></i> Geser untuk navigasi
                                </span>
                            </div>
                            <div class="btn-group" id="info-carousel-indicators">
                                @php
                                    $total = count($pelaporanTerkait);
                                    $maxWindow = 5;
                                    $start = 0;
                                    $end = min($total, $maxWindow);
                                @endphp
                                @for($i = $start; $i < $end; $i++)
                                    <button onclick="showInfoSlide({{ $i }})" class="btn btn-xs {{ $i === 0 ? 'btn-active' : '' }}" data-index="{{ $i }}">{{ $i + 1 }}</button>
                                @endfor
                                @if($total > $maxWindow)
                                    <span class="mx-1 text-xs text-gray-400">...</span>
                                    <button onclick="showInfoSlide({{ $total - 1 }})" class="btn btn-xs" data-index="{{ $total - 1 }}">{{ $total }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Teknisi
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Teknisi Bertugas</p>
                    @if ($perbaikan->perbaikanPetugas->isNotEmpty())
                        <ul class="list-disc list-inside mt-1 text-slate-900">
                            @foreach ($perbaikan->perbaikanPetugas as $petugas)
                                <li>{{ $petugas->user->nama ?? '-' }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-1 text-gray-400 italic">Belum ada teknisi ditugaskan</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Deskripsi Penanganan</p>
                    <p class="mt-1 text-slate-900 break-words">{{ $perbaikan->perbaikan_deskripsi ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 mb-10">
        <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>

        <div class="lg:hidden space-y-6">
            @forelse ($histori as $row)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        @if ($row->perbaikan_status == 'Selesai')
                            <div
                                    class="w-5 h-5 rounded-full bg-success flex items-center justify-center ring-4 ring-success/30">
                                <i class="bi bi-check-lg text-white text-xs"></i>
                            </div>
                        @else
                            <div class="w-4 h-4 mt-1 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        @endif
                        @if (!$loop->last)
                            <div class="w-px h-full bg-gray-200 mt-2"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-6">
                        @if ($row->perbaikan_status == 'Menunggu')
                            <p class="font-bold text-gray-800">Menunggu Penugasan</p>
                        @else
                            <p class="font-bold text-gray-800">Status diubah menjadi "{{ $row->perbaikan_status }}"</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">{{ $row->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada histori perbaikan.</p>
            @endforelse
        </div>

        <div class="hidden lg:block overflow-x-auto rounded-xl border">
            <table class="table w-full">
                <thead class="bg-base-200">
                <tr>
                    <th class="w-1/5">Tanggal</th>
                    <th class="w-1/5">Status</th>
                    <th class="w-3/5">Keterangan</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($histori as $row)
                    <tr>
                        <td>{{ $row->created_at->translatedFormat('d F Y, H:i') }}</td>
                        <td>
                            @if ($row->perbaikan_status == 'Menunggu')
                                <span class="badge badge-warning text-white">{{ $row->perbaikan_status }}</span>
                            @elseif ($row->perbaikan_status == 'Diproses')
                                <span class="badge badge-primary text-white">{{ $row->perbaikan_status }}</span>
                            @elseif ($row->perbaikan_status == 'Selesai')
                                <span class="badge badge-success text-white">{{ $row->perbaikan_status }}</span>
                            @else
                                <span class="badge badge-ghost">{{ $row->perbaikan_status }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->perbaikan_status == 'Menunggu')
                                <p>Penugasan dibuat dan menunggu teknisi untuk melakukan perbaikan.</p>
                            @elseif ($row->perbaikan_status == 'Diproses')
                                <p>Teknisi sedang melakukan perbaikan.</p>
                            @else
                                <p>Fasilitas telah selesai diperbaiki.</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6">
                            <p class="text-gray-500">Belum ada histori perbaikan.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk gambar tunggal -->
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black opacity-75" onclick="closeImageModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900" id="imageModalTitle">Foto Bukti</h3>
                <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <img id="modalImage" src="" alt="Bukti Foto" class="max-h-[70vh] object-contain mx-auto">
            </div>
        </div>
    </div>

    <!-- Modal untuk gallery foto -->
    <div id="imageGalleryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black opacity-75" onclick="closeImageGalleryModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-5xl w-full mx-4">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Gallery Foto Bukti Laporan</h3>
                <button type="button" onclick="closeImageGalleryModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <!-- Gallery Carousel -->
                <div class="carousel w-full" id="gallery-carousel">
                    @forelse($semuaFoto as $index => $foto)
                    <div id="gallerySlide{{ $index }}" class="carousel-item relative w-full" style="{{ $index === 0 ? 'display: block;' : 'display: none;' }}">
                        <div class="flex flex-col items-center justify-center w-full">
                            <div class="relative group w-full flex justify-center">
                                <img src="{{ $foto['path'] ?? '' }}" class="max-h-[70vh] object-contain mx-auto" alt="Bukti Foto" />
                            </div>
                            <p class="mt-2 text-center text-sm text-gray-600">
                                Laporan ID: {{ $foto['pelaporan_kode'] ?? '' }}<br>
                                Tanggal: {{ isset($foto['created_at']) ? \Carbon\Carbon::parse($foto['created_at'])->translatedFormat('d F Y, H:i') : '-' }}
                            </p>
                        </div>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-0 right-0 top-1/2 px-2">
                            <button type="button" class="btn btn-circle btn-sm bg-black/20 hover:bg-black/40 text-white border-0 shadow-lg" 
                                    onclick="showGallerySlide({{ $index == 0 ? count($semuaFoto) - 1 : $index - 1 }})">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-circle btn-sm bg-black/20 hover:bg-black/40 text-white border-0 shadow-lg" 
                                    onclick="showGallerySlide({{ $index == count($semuaFoto) - 1 ? 0 : $index + 1 }})">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-400 italic">Tidak ada foto ditemukan</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Gallery Controls -->
                @if(!empty($semuaFoto) && count($semuaFoto) > 0)
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500">
                            <i class="bi bi-info-circle"></i> Geser untuk navigasi
                        </span>
                    </div>
                    <div class="btn-group" id="gallery-carousel-indicators">
                        @foreach($semuaFoto as $index => $foto)
                        <button onclick="showGallerySlide({{ $index }})" class="btn btn-xs {{ $index === 0 ? 'btn-active' : '' }}">{{ $index + 1 }}</button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script Modal Gambar --}}
    <script>
    function openImageModal(src, title = 'Foto Bukti') {
        var modal = document.getElementById('imageModal');
        var img = document.getElementById('modalImage');
        var titleEl = document.getElementById('imageModalTitle');
        if (modal && img) {
            img.src = src;
            if (titleEl) titleEl.textContent = title;
            modal.classList.remove('hidden');
        }
    }
    function closeImageModal() {
        var modal = document.getElementById('imageModal');
        var img = document.getElementById('modalImage');
        if (modal && img) {
            img.src = '';
            modal.classList.add('hidden');
        }
    }
    function openImageGalleryModal(startIndex = 0) {
        var modal = document.getElementById('imageGalleryModal');
        if (modal) {
            modal.classList.remove('hidden');
            showGallerySlide(startIndex);
        }
    }
    function closeImageGalleryModal() {
        var modal = document.getElementById('imageGalleryModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    // Gallery carousel logic
    function showGallerySlide(idx) {
        var slides = document.querySelectorAll('#gallery-carousel .carousel-item');
        var total = slides.length;
        if (total === 0) return;
        if (idx < 0) idx = total - 1;
        if (idx >= total) idx = 0;
        slides.forEach(function(slide, i) {
            slide.style.display = (i === idx) ? 'block' : 'none';
        });
        // Update indicator
        var btns = document.querySelectorAll('#gallery-carousel-indicators button');
        btns.forEach(function(btn, i) {
            if (i === idx) btn.classList.add('btn-active');
            else btn.classList.remove('btn-active');
        });
        window.currentGallerySlide = idx;
    }
    // Inisialisasi gallery slide pertama saat modal dibuka
    window.currentGallerySlide = 0;
    </script>

    {{-- Script Carousel Info Laporan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hanya aktif jika laporan > 1
            var totalSlides = {{ !empty($pelaporanTerkait) && count($pelaporanTerkait) > 1 ? count($pelaporanTerkait) : 1 }};
            var maxWindow = 5;
            if (totalSlides > 1) {
                window.currentInfoSlide = 0;
                window.showInfoSlide = function(idx) {
                    var slides = [];
                    for (var i = 0; i < totalSlides; i++) {
                        var el = document.getElementById('infoSlide' + i);
                        if (el) slides.push(el);
                    }
                    if (idx < 0) idx = totalSlides - 1;
                    if (idx >= totalSlides) idx = 0;
                    window.currentInfoSlide = idx;
                    slides.forEach(function(slide, i) {
                        slide.style.display = (i === idx) ? 'block' : 'none';
                    });
                    // Update indicator window
                    var indicators = document.getElementById('info-carousel-indicators');
                    if (indicators) {
                        var start = 0;
                        var end = totalSlides > maxWindow ? maxWindow : totalSlides;
                        if (totalSlides > maxWindow) {
                            if (idx <= 2) {
                                start = 0;
                                end = maxWindow;
                            } else if (idx >= totalSlides - 3) {
                                start = totalSlides - maxWindow;
                                end = totalSlides;
                            } else {
                                start = idx - 2;
                                end = idx + 3;
                            }
                        }
                        var html = '';
                        for (var i = start; i < end; i++) {
                            html += '<button onclick="showInfoSlide(' + i + ')" class="btn btn-xs ' + (i === idx ? 'btn-active' : '') + '" data-index="' + i + '">' + (i + 1) + '</button>';
                        }
                        if (totalSlides > maxWindow && end < totalSlides) {
                            html += '<span class="mx-1 text-xs text-gray-400">...</span>';
                            html += '<button onclick="showInfoSlide(' + (totalSlides - 1) + ')" class="btn btn-xs ' + (idx === totalSlides - 1 ? 'btn-active' : '') + '" data-index="' + (totalSlides - 1) + '">' + totalSlides + '</button>';
                        }
                        indicators.innerHTML = html;
                    }
                };
                // Inisialisasi slide pertama
                window.showInfoSlide(0);
            }
        });
    </script>
</div>