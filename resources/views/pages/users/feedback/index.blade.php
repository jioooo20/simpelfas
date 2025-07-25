@extends('layouts.main')
@section('judul', 'Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Filter Section -->
        <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Umpan Balik</h1>
                
                <div class="flex gap-2">
                    <button onclick="filterReports('all')" 
                            class="filter-btn px-4 py-2 rounded-lg font-medium transition-colors bg-blue-600 text-white" 
                            data-filter="all">
                        Semua
                    </button>
                    <button onclick="filterReports('unrated')" 
                            class="filter-btn px-4 py-2 rounded-lg font-medium transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300" 
                            data-filter="unrated">
                        Belum Dinilai
                    </button>
                    <button onclick="filterReports('rated')" 
                            class="filter-btn px-4 py-2 rounded-lg font-medium transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300" 
                            data-filter="rated">
                        Sudah Dinilai
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($perbaikan as $items)
                <div class="report-item bg-white rounded-lg shadow-md overflow-hidden {{ $items['is_rated'] ? 'rated-report' : 'unrated-report' }}" 
                     data-status="{{ $items['is_rated'] ? 'rated' : 'unrated' }}">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Gambar Thumbnail -->
                            <div class="w-full md:w-48 flex-shrink-0 relative">
                                @php
                                    $fotoTeknisi = $items['foto_teknisi'] ?? [];
                                    $fotoUtama = !empty($fotoTeknisi) ? $fotoTeknisi[0] : null;
                                @endphp
                                
                                <div class="relative">
                                    @if($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}"
                                             alt="Foto hasil perbaikan"
                                             class="w-32 h-32 object-cover rounded-md shadow cursor-pointer hover:opacity-80 transition-opacity"
                                             onclick="openPhotoModal('{{ $items->pelaporan_id }}')">
                                        
                                        @if(count($fotoTeknisi) > 1)
                                            <div class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                                +{{ count($fotoTeknisi) - 1 }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                             onclick="openPhotoModal('{{ $items->pelaporan_id }}')">
                                            <div class="text-center">
                                                <i class="bi bi-camera text-gray-400 text-2xl"></i>
                                                <p class="text-xs text-gray-500 mt-1">Foto belum tersedia</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Enhanced Status badge -->
                                @php
                                    $statusHistory = \App\Models\StatusPelaporanModel::where('pelaporan_id', $items->pelaporan_id)
                                                    ->orderBy('created_at')
                                                    ->get();
                                    $statusTerakhir = $statusHistory->last();
                                    $status = $statusTerakhir ? $statusTerakhir->status_pelaporan : 'MENUNGGU';
                                @endphp

                                <div class="absolute top-2 left-2 flex flex-col gap-1">
                                    <div class="bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                        {{ strtoupper($status) }}
                                    </div>
                                    @if($items['is_rated'])
                                        <div class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                            <i class="bi bi-star-fill mr-1"></i>DINILAI
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Konten -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <h3 class="font-bold text-xl text-gray-800">
                                        Kode: {{ strtoupper($items->pelaporan_kode) }}
                                    </h3>
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="bi bi-building"></i>
                                        Fasilitas: {{ $items['fasilitas_label'] ?? '-' }}
                                    </p>

                                    <h4 class="font-medium text-gray-700 mt-6 mb-2">Deskripsi Kerusakan:</h4>
                                    <p class="text-gray-600">{{ $items->pelaporan_deskripsi }}</p>
                                    
                                    <!-- Info foto teknisi -->
                                    <div class="mt-4 flex items-center gap-2 text-sm text-gray-600">
                                        <i class="bi bi-camera"></i>
                                        @if(!empty($fotoTeknisi))
                                            <span>{{ count($fotoTeknisi) }} foto hasil perbaikan</span>
                                            <button onclick="openPhotoModal('{{ $items->pelaporan_id }}')" 
                                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                                Lihat semua foto
                                            </button>
                                        @else
                                            <span class="text-gray-500">Foto hasil perbaikan belum tersedia</span>
                                        @endif
                                    </div>

                                    <!-- Rating Display for Rated Reports -->
                                    @if($items['is_rated'])
                                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                            <h5 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                                <i class="bi bi-star-fill text-yellow-500"></i>
                                                Penilaian Anda
                                            </h5>
                                            
                                            <!-- Star Rating Display -->
                                            <div class="flex items-center gap-2 mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= ($items['user_rating'] ?? 0) ? '-fill' : '' }} 
                                                       text-{{ $i <= ($items['user_rating'] ?? 0) ? 'yellow' : 'gray' }}-400 text-lg"></i>
                                                @endfor
                                                <span class="text-sm text-gray-600 ml-2">
                                                    ({{ $items['user_rating'] ?? 0 }}/5)
                                                </span>
                                            </div>

                                            @if($items['user_comment'])
                                                <div class="mt-3">
                                                    <p class="text-sm font-medium text-gray-700 mb-1">Komentar:</p>
                                                    <p class="text-sm text-gray-600 italic">{{ $items['user_comment'] }}</p>
                                                </div>
                                            @endif

                                            <div class="mt-3 text-xs text-gray-500">
                                                Dinilai pada: {{ \Carbon\Carbon::parse($items['feedback_date'])->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button - Only for Unrated Reports -->
                                <div class="flex justify-end">
                                    @if(!$items['is_rated'])
                                        <a href="{{ route('feedback-create', ['perbaikan_id' => $items->pelaporan_id]) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out">
                                            Beri Penilaian
                                        </a>
                                    @else
                                        <div class="text-green-600 font-medium text-sm flex items-center gap-2">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Sudah Dinilai
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden data untuk modal -->
                <script type="application/json" id="photos-{{ $items->pelaporan_id }}">
                    @json($fotoTeknisi)
                </script>

            @empty
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <i class="bi bi-chat-dots text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Laporan Selesai</h3>
                        <p class="text-gray-500">
                            Laporan yang telah selesai diperbaiki akan muncul di sini untuk diberi penilaian.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal untuk menampilkan foto - Style sesuai screenshot -->
    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[80vh] overflow-hidden shadow-2xl">
                <!-- Header Modal -->
                <div class="flex justify-between items-center p-6 border-b bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-800">Foto Hasil Perbaikan</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Body Modal -->
                <div class="p-6">
                    <div id="photoContainer" class="space-y-4">
                        <!-- Photos will be loaded here -->
                    </div>
                    
                    <!-- Placeholder ketika tidak ada foto -->
                    <div id="noPhotosMessage" class="hidden">
                        <div class="flex items-center justify-center bg-gray-100 rounded-lg" style="height: 400px;">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-6xl font-light">600 × 400</p>
                                <p class="text-sm mt-2">Foto hasil perbaikan belum tersedia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPhotoModal(pelaporanId) {
            const modal = document.getElementById('photoModal');
            const photoContainer = document.getElementById('photoContainer');
            const noPhotosMessage = document.getElementById('noPhotosMessage');
            
            // Ambil data foto dari script tag
            const photoData = document.getElementById('photos-' + pelaporanId);
            const photos = photoData ? JSON.parse(photoData.textContent) : [];
            
            // Clear container
            photoContainer.innerHTML = '';
            
            if (photos.length > 0) {
                // Tampilkan foto dalam style yang lebih clean
                photos.forEach((photo, index) => {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'relative';
                    photoDiv.innerHTML = `
                        <img src="/storage/${photo}" 
                             alt="Foto hasil perbaikan ${index + 1}"
                             class="w-full h-auto max-h-96 object-contain rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow bg-gray-50"
                             onclick="viewFullPhoto('/storage/${photo}')">
                        ${photos.length > 1 ? `<div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white text-sm px-3 py-1 rounded-full">
                            ${index + 1} / ${photos.length}
                        </div>` : ''}
                    `;
                    photoContainer.appendChild(photoDiv);
                });
                
                photoContainer.classList.remove('hidden');
                noPhotosMessage.classList.add('hidden');
            } else {
                // Tampilkan placeholder seperti screenshot
                photoContainer.classList.add('hidden');
                noPhotosMessage.classList.remove('hidden');
            }
            
            // Tampilkan modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function viewFullPhoto(photoUrl) {
            window.open(photoUrl, '_blank');
        }
        
        // Tutup modal ketika klik di luar
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });
        
        // Tutup modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhotoModal();
            }
        });
        
        // Filter functionality
        function filterReports(filterType) {
            const reports = document.querySelectorAll('.report-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update button styles
            filterButtons.forEach(btn => {
                const btnFilter = btn.getAttribute('data-filter');
                if (btnFilter === filterType) {
                    btn.className = 'filter-btn px-4 py-2 rounded-lg font-medium transition-colors bg-blue-600 text-white';
                } else {
                    btn.className = 'filter-btn px-4 py-2 rounded-lg font-medium transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300';
                }
            });
            
            // Filter reports
            reports.forEach(report => {
                const reportStatus = report.getAttribute('data-status');
                
                if (filterType === 'all') {
                    report.style.display = 'block';
                } else if (filterType === 'unrated' && reportStatus === 'unrated') {
                    report.style.display = 'block';
                } else if (filterType === 'rated' && reportStatus === 'rated') {
                    report.style.display = 'block';
                } else {
                    report.style.display = 'none';
                }
            });
            
            // Update empty state visibility
            updateEmptyState(filterType);
        }
        
        function updateEmptyState(filterType) {
            const reports = document.querySelectorAll('.report-item');
            const visibleReports = Array.from(reports).filter(report => 
                report.style.display !== 'none'
            );
            
            // You can add custom empty state messages for different filters here
            if (visibleReports.length === 0) {
                console.log(`No reports found for filter: ${filterType}`);
                // You could show different empty state messages based on filterType
            }
        }
    </script>

@endsection