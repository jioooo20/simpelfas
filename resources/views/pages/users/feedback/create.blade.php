@extends('layouts.main')
@section('judul', 'Beri Umpan Balik')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Beri Umpan Balik</h1>
                <p class="text-gray-600">Bagikan pengalaman Anda tentang penanganan perbaikan fasilitas</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow p-8">
                <!-- Informasi Laporan -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-6 bg-blue-500 rounded-full mr-3"></div>
                        <h2 class="text-xl font-semibold text-gray-800">Laporan yang Ditangani</h2>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                            <!-- Foto Fasilitas -->
                            <div class="flex-shrink-0 w-48 h-48 relative">
                                @php
                                    $fotoUtama = !empty($fotoTeknisi) ? $fotoTeknisi[0] : null;
                                @endphp

                                <div class="relative w-full h-full">
                                    @if ($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="Foto hasil perbaikan"
                                            class="w-full h-full object-cover rounded-md shadow cursor-pointer hover:opacity-80 transition-opacity"
                                            onclick="openPhotoModal('{{ $laporan->pelaporan_id }}')">

                                        @if (count($fotoTeknisi) > 1)
                                            <div
                                                class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                                +{{ count($fotoTeknisi) - 1 }}
                                            </div>
                                        @endif
                                    @else
                                        <!-- Frame kosong untuk foto -->
                                        <div class="w-full h-full border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                            onclick="openPhotoModal('{{ $laporan->pelaporan_id }}')">
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
                                    {{ $laporan->fasilitas->barang->barang_nama ?? 'Tidak tersedia' }}</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><span class="font-medium">Gedung:</span>
                                        {{ $laporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}</p>
                                    <p><span class="font-medium">Lantai:</span>
                                        {{ $laporan->fasilitas->ruang->lantai->lantai_nama ?? '-' }}</p>
                                    <p><span class="font-medium">Ruang:</span>
                                        {{ $laporan->fasilitas->ruang->ruang_nama ?? '-' }}</p>
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
                                    $statusSelesai = $laporan->statusPelaporan
                                        ->where('status_pelaporan', 'SELESAI')
                                        ->first();
                                    $tanggalDitangani = $statusSelesai
                                        ? $statusSelesai->created_at
                                        : $laporan->tanggal_ditangani ?? $laporan->updated_at;
                                @endphp

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-800">Tanggal Lapor:</span>
                                            <p class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($laporan->pelaporan_tanggal)->format('d M Y, H:i') }}
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

                <!-- Form Umpan Balik -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-6 bg-green-500 rounded-full mr-3"></div>
                        <h2 class="text-xl font-semibold text-gray-800">Berikan Penilaian Anda</h2>
                    </div>

                    <form id="feedbackForm" action="{{ route('feedback-store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="report_id" value="{{ $laporan->pelaporan_id }}">

                        <!-- Rating Section -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 font-semibold mb-4 text-lg">Rating Kepuasan</label>

                            <div class="mb-6">
                                <div x-data="{ rating: 0 }" class="flex flex-col items-start space-y-2">
                                    <div class="rating rating-lg">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}"
                                                class="mask mask-star-2" x-model="rating"
                                                x-bind:class="rating >= {{ $i }} ? 'bg-yellow-400': 'bg-gray-300'"
                                                @click="rating = {{ $i }}" />
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-gray-600 text-sm">(1 = Buruk, 5 = Sangat Puas)</span>
                                </div>
                                @error('rating')More actions
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 font-semibold mb-4 text-lg">Komentar & Saran</label>
                            <textarea name="comment" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition"
                                placeholder="Ceritakan pengalaman Anda tentang penanganan perbaikan ini. Apakah teknisi datang tepat waktu? Apakah perbaikan dilakukan dengan baik? Saran untuk perbaikan selanjutnya?"></textarea>

                            <div class="text-sm text-gray-500 mt-2">
                                <p>💡 <strong>Tips:</strong> Berikan detail yang membantu untuk meningkatkan pelayanan kami
                                </p>
                            </div>

                            @error('comment')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('users.feedback') }}"
                                class="btn btn-outline px-6 py-3 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="submitBtn" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-transform hover:scale-105">
                                <i class="bi bi-send"></i>Kirim Umpan Balik
                            </button>
                        </div>
                    </form>
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

    <!-- Modal Konfirmasi Kirim - Fixed Structure-->
    <div id="konfirmasiKirimModal"
         class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <!-- Modal Content -->
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    Konfirmasi Pengiriman
                </h2>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-gray-600">
                    Apakah Anda yakin ingin mengirim umpan balik ini?
                </p>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button id="batalKirimBtn"
                        type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <button id="lanjutKirimBtn"
                        type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Ya, Kirim
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification - Fixed Structure -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300">
        <div id="toastContent" class="px-6 py-3 rounded-lg shadow-lg text-white font-medium max-w-sm">
            <span id="toastMessage"></span>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Global variables
        let isSubmitting = false;
        const PHOTOS = @json($fotoTeknisi ?? []);
        
        // Photo Modal Functions
        function openPhotoModal(pelaporanId) {
            const modal = document.getElementById('photoModal');
            const photoContainer = document.getElementById('photoContainer');

            // Clear container
            photoContainer.innerHTML = '';

            if (PHOTOS && PHOTOS.length > 0) {
                // Create photo gallery
                PHOTOS.forEach((photo, index) => {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'relative mb-4';
                    photoDiv.innerHTML = `
                        <img src="{{ asset('storage/') }}/${photo}" 
                             alt="Foto perbaikan ${index + 1}"
                             class="w-full h-auto max-h-[70vh] object-contain rounded-lg shadow-sm cursor-pointer hover:shadow-md transition mx-auto"
                             onclick="window.open('{{ asset('storage/') }}/${photo}', '_blank')">
                        ${PHOTOS.length > 1 ? `<div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white text-sm px-3 py-1 rounded-full">
                                ${index + 1} / ${PHOTOS.length}
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

        // Toast Notification Function
        function showToast(message, type = 'green', callback = null) {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toastContent');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast || !toastContent || !toastMessage) {
                console.error('Toast elements not found');
                alert(message); // Fallback
                return;
            }
            
            // Set message
            toastMessage.textContent = message;
            
            // Set color based on type
            const colorClass = type === 'green' ? 'bg-green-500' : 
                              type === 'red' ? 'bg-red-500' : 'bg-blue-500';
            
            toastContent.className = `px-6 py-3 rounded-lg shadow-lg text-white font-medium max-w-sm ${colorClass}`;
            
            // Show toast with animation
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('transform', 'translate-x-0');
            }, 10);
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('transform', 'translate-x-full');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('transform', 'translate-x-full', 'translate-x-0');
                    if (callback) callback();
                }, 300);
            }, 3000);
        }

        // Form validation function
        function validateForm() {
            const form = document.getElementById('feedbackForm');
            const rating = form.querySelector('input[name="rating"]:checked');
            
            if (!rating) {
                showToast('Mohon berikan rating kepuasan terlebih dahulu', 'red');
                return false;
            }
            
            return true;
        }

        // Main DOMContentLoaded event
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Starting initialization');
            
            const feedbackForm = document.getElementById('feedbackForm');
            const konfirmasiModal = document.getElementById('konfirmasiKirimModal');
            const batalKirimBtn = document.getElementById('batalKirimBtn');
            const lanjutKirimBtn = document.getElementById('lanjutKirimBtn');
            
            // Debug: Check if elements exist
            console.log('Elements check:', {
                form: !!feedbackForm,
                modal: !!konfirmasiModal,
                batalBtn: !!batalKirimBtn,
                lanjutBtn: !!lanjutKirimBtn
            });
            
            if (!feedbackForm || !konfirmasiModal || !batalKirimBtn || !lanjutKirimBtn) {
                console.error('Required elements not found!');
                return;
            }
            
            // Prevent default form submission and show confirmation modal
            feedbackForm.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                e.preventDefault();
                e.stopPropagation();
                
                // Prevent double submission
                if (isSubmitting) {
                    console.log('Already submitting, prevented double submit');
                    return false;
                }
                
                // Validate form
                if (!validateForm()) {
                    return false;
                }
                
                // Show confirmation modal
                console.log('Showing confirmation modal');
                konfirmasiModal.classList.remove('hidden');
                
                // Focus on modal for accessibility
                setTimeout(() => {
                    lanjutKirimBtn.focus();
                }, 100);
                
                return false;
            });
            
            // Handle modal buttons
            batalKirimBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Batal button clicked');
                konfirmasiModal.classList.add('hidden');
            });
            
            lanjutKirimBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Lanjut button clicked');
                
                // Hide modal and submit form
                konfirmasiModal.classList.add('hidden');
                isSubmitting = true;
                submitFeedbackForm();
            });
            
            // Close modal when clicking outside
            konfirmasiModal.addEventListener('click', function(e) {
                if (e.target === konfirmasiModal) {
                    console.log('Modal backdrop clicked');
                    konfirmasiModal.classList.add('hidden');
                }
            });
            
            // Handle ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !konfirmasiModal.classList.contains('hidden')) {
                    console.log('ESC key pressed - closing modal');
                    konfirmasiModal.classList.add('hidden');
                }
            });
            
            // Enhanced rating system
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    console.log('Rating selected:', this.value);
                    // Visual feedback for rating selection
                    const ratingContainer = this.closest('.rating');
                    ratingContainer.classList.add('opacity-75');
                    setTimeout(() => {
                        ratingContainer.classList.remove('opacity-75');
                    }, 200);
                });
            });
            
            console.log('Initialization complete');
        });

        // Submit feedback form via AJAX
        async function submitFeedbackForm() {
            const form = document.getElementById('feedbackForm');
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            
            console.log('Submitting form via AJAX');
            
            // Disable submit button and show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Mengirim...';
            submitBtn.classList.add('opacity-75');
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);
                
                if (response.ok) {
                    // Success
                    showToast(data.message || "Umpan balik berhasil dikirim.", "green", () => {
                        window.location.href = "{{ route('users.feedback') }}";
                    });
                } else if (data.errors) {
                    // Validation errors
                    let errorMessage = 'Validasi gagal: ';
                    for (const key in data.errors) {
                        errorMessage += data.errors[key][0];
                        break; // Show only first error
                    }
                    showToast(errorMessage, "red");
                } else {
                    // Other errors
                    showToast(data.message || 'Terjadi kesalahan.', "red");
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                showToast('Terjadi kesalahan saat mengirim umpan balik.', "red");
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('opacity-75');
                isSubmitting = false;
            }
        }
    </script>
@endpush