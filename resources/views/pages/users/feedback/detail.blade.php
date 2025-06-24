@extends('layouts.main')
@section('judul', 'Detail Penilaian')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Penilaian</h1>
                <p class="text-gray-600">Detail umpan balik Anda untuk laporan perbaikan</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-8">
                <!-- Informasi Laporan -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-6 bg-blue-500 rounded-full mr-3"></div>
                        <h2 class="text-xl font-semibold text-gray-800">Laporan yang Dinilai</h2>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                            <!-- Foto Fasilitas -->
                            <div class="flex-shrink-0 w-48 h-48 relative">
                                @php
                                    $fotoTeknisi = $feedback->pelaporan->foto_teknisi ?? [];
                                    $fotoUtama = !empty($fotoTeknisi) ? $fotoTeknisi[0] : null;
                                @endphp

                                <div class="relative w-full h-full">
                                    @if ($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="Foto hasil perbaikan"
                                            class="w-full h-full object-cover rounded-md shadow cursor-pointer hover:opacity-80 transition-opacity"
                                            onclick="openPhotoModal('{{ $feedback->pelaporan_id }}')">

                                        @if (count($fotoTeknisi) > 1)
                                            <div class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                                +{{ count($fotoTeknisi) - 1 }}
                                            </div>
                                        @endif
                                    @else
                                        <!-- Frame kosong untuk foto -->
                                        <div class="w-full h-full border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                            onclick="openPhotoModal('{{ $feedback->pelaporan_id }}')">
                                            <div class="text-center">
                                                <i class="bi bi-camera text-gray-400 text-2xl"></i>
                                                <p class="text-xs text-gray-500 mt-1">Foto belum tersedia</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informasi Perbaikan -->
                            <div class="flex-1 bg-white p-4 rounded-lg border border-gray-200">
                                <div class="badge bg-gray-100 text-gray-700 border-none mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m0 0H5m2 0v-3a1 1 0 011-1h1a1 1 0 011 1v3m-4 0V9a1 1 0 011-1h1a1 1 0 011 1v10">
                                        </path>
                                    </svg>
                                    Perbaikan Fasilitas
                                </div>

                                <h4 class="font-semibold text-gray-800 mb-2">
                                    {{ $feedback->pelaporan->fasilitas->barang->barang_nama ?? 'Tidak tersedia' }}</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><span class="font-medium">Kode Laporan:</span> {{ $feedback->pelaporan->pelaporan_kode }}</p>
                                    <p><span class="font-medium">Gedung:</span>
                                        {{ $feedback->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}</p>
                                    <p><span class="font-medium">Lantai:</span>
                                        {{ $feedback->pelaporan->fasilitas->ruang->lantai->lantai_nama ?? '-' }}</p>
                                    <p><span class="font-medium">Ruang:</span>
                                        {{ $feedback->pelaporan->fasilitas->ruang->ruang_nama ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Informasi Waktu -->
                            <div class="flex-1 bg-white p-4 rounded-lg border border-gray-200">
                                <div class="badge bg-gray-100 text-gray-700 border-none mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Timeline Perbaikan
                                </div>

                                @php
                                    $statusSelesai = $feedback->pelaporan->statusPelaporan
                                        ->where('status_pelaporan', 'SELESAI')
                                        ->first();
                                    $tanggalDitangani = $statusSelesai
                                        ? $statusSelesai->created_at
                                        : $feedback->pelaporan->tanggal_ditangani ?? $feedback->pelaporan->updated_at;
                                @endphp

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-800">Tanggal Lapor:</span>
                                            <p class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($feedback->pelaporan->pelaporan_tanggal)->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-800">Ditangani pada:</span>
                                            <p class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($tanggalDitangani)->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Umpan Balik -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-6 bg-green-500 rounded-full mr-3"></div>
                        <h2 class="text-xl font-semibold text-gray-800">Umpan Balik Anda</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Rating -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 font-semibold mb-4 text-lg">Rating Kepuasan</label>
                            <div class="flex items-center space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="text-5xl {{ $feedback->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </div>

                        <!-- Komentar -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 font-semibold mb-4 text-lg">Komentar</label>
                            <p class="text-gray-600 p-4 bg-gray-50 rounded-lg">
                                {{ $feedback->feedback_text ?? 'Tidak ada komentar' }}
                            </p>
                        </div>

                        <!-- Tanggal -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 font-semibold mb-4 text-lg">Tanggal Penilaian</label>
                            <p class="text-gray-600">
                                {{ $feedback->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.feedback') }}"
                            class="btn btn-outline px-6 py-3 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <dialog id="photoModal" class="modal">
        <div class="modal-box max-w-5xl max-h-[90vh]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div id="photoContainer" class="text-center">
                <!-- Photos will be loaded here dynamically -->
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        // Photo Modal Functions
        function openPhotoModal(pelaporanId) {
            const modal = document.getElementById('photoModal');
            const photoContainer = document.getElementById('photoContainer');
            
            // Clear container
            photoContainer.innerHTML = '';
            
            // Get photos from hidden JSON element
            const photoData = document.getElementById('photos-' + pelaporanId);
            const photos = photoData ? JSON.parse(photoData.textContent) : [];
            
            if (photos.length > 0) {
                // Create photo gallery
                photos.forEach((photo, index) => {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'relative mb-4';
                    photoDiv.innerHTML = `
                        <img src="/storage/${photo}" 
                             alt="Foto hasil perbaikan ${index + 1}"
                             class="w-full h-auto max-h-[70vh] object-contain rounded-lg shadow-sm cursor-pointer hover:shadow-md transition mx-auto"
                             onclick="window.open('/storage/${photo}', '_blank')">
                        ${photos.length > 1 ? `<div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white text-sm px-3 py-1 rounded-full">
                                    ${index + 1} / ${photos.length}
                                </div>` : ''}
                    `;
                    photoContainer.appendChild(photoDiv);
                });
            } else {
                // No photos available
                photoContainer.innerHTML = `
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-lg p-8 inline-block">
                            <i class="bi bi-camera text-6xl text-gray-400 mb-4"></i>
                            <p class="text-xl mb-2">Foto Tidak Tersedia</p>
                            <p class="text-gray-500">Belum ada foto perbaikan yang diunggah</p>
                        </div>
                    </div>
                `;
            }

            modal.showModal();
        }
    </script>
@endpush