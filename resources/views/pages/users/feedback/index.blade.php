@extends('layouts.main')
@section('judul', 'Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Umpan Balik</h1>
        </div>

        <div class="space-y-6">
            @forelse ($perbaikan as $items)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Gambar Thumbnail -->
                            <div class="w-full md:w-48 flex-shrink-0 relative">
                                @php
                                    // Ambil foto dari teknisi, bukan dari user
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
                                        <!-- Frame kosong untuk foto -->
                                        <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                             onclick="openPhotoModal('{{ $items->pelaporan_id }}')">
                                            <div class="text-center">
                                                <i class="bi bi-camera text-gray-400 text-2xl"></i>
                                                <p class="text-xs text-gray-500 mt-1">Foto belum tersedia</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status badge -->
                                @php
                                    $statusHistory = \App\Models\StatusPelaporanModel::where('pelaporan_id', $items->pelaporan_id)
                                                    ->orderBy('created_at')
                                                    ->get();

                                    $statusTerakhir = $statusHistory->last();
                                    $status = $statusTerakhir ? $statusTerakhir->status_pelaporan : 'MENUNGGU';
                                @endphp

                                <div class="absolute top-2 left-2
                                    {{ $status == 'SELESAI' ? 'bg-green-600' : 'bg-yellow-500' }}
                                    text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                    {{ strtoupper($status) }}
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
                                </div>

                                <!-- Tombol Penilaian -->
                                <div class="flex justify-end">
                                    <a href="{{ route('feedback-create', ['perbaikan_id' => $items->pelaporan_id]) }}"
                                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out">
                                        Beri Penilaian
                                    </a>
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
                <p class="text-gray-500 text-center">Belum ada pelaporan.</p>
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
    </script>

@endsection