@extends('layouts.main')
@section('judul', 'Beri Umpan Balik')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="space-y-6">

            <!-- Report Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-1 h-8 bg-blue-500 rounded-full mr-4"></div>
                    <h2 class="text-xl font-semibold text-gray-900">Laporan yang Ditangani</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Photo Section -->
                    <div class="lg:col-span-1">
                        <div class="aspect-square relative">
                            @php
                                $fotoUtama = !empty($fotoTeknisi) ? $fotoTeknisi[0] : null;
                            @endphp
                            
                            @if ($fotoUtama)
                                <img src="{{ asset('storage/' . $fotoUtama) }}" alt="Foto hasil perbaikan"
                                    class="w-full h-full object-cover rounded-xl cursor-pointer hover:opacity-90 transition-all duration-300 shadow-sm"
                                    onclick="openPhotoModal('{{ $laporan->pelaporan_id }}')">
                                @if (count($fotoTeknisi) > 1)
                                    <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-sm rounded-full w-8 h-8 flex items-center justify-center font-semibold shadow-lg">
                                        +{{ count($fotoTeknisi) - 1 }}
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                    onclick="openPhotoModal('{{ $laporan->pelaporan_id }}')">
                                    <div class="text-center">
                                        <i class="bi bi-camera text-gray-400 text-3xl mb-2"></i>
                                        <p class="text-sm text-gray-500">Tidak ada foto</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Facility Details -->
                    <div class="lg:col-span-1 space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="bi bi-tools text-blue-600 text-lg mr-2"></i>
                                <span class="text-sm font-medium text-gray-600">FASILITAS</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $laporan->fasilitas->barang->barang_nama ?? 'Tidak tersedia' }}
                            </h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><i class="bi bi-building mr-2"></i>{{ $laporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }}</p>
                                <p><i class="bi bi-layers mr-2"></i>{{ $laporan->fasilitas->ruang->lantai->lantai_nama ?? '-' }}</p>
                                <p><i class="bi bi-door-open mr-2"></i>{{ $laporan->fasilitas->ruang->ruang_nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="lg:col-span-1 space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="bi bi-clock text-green-600 text-lg mr-2"></i>
                                <span class="text-sm font-medium text-gray-600">TIMELINE</span>
                            </div>
                            
                            @php
                                $statusSelesai = $laporan->statusPelaporan->where('status_pelaporan', 'SELESAI')->first();
                                $tanggalDitangani = $statusSelesai ? $statusSelesai->created_at : $laporan->tanggal_ditangani ?? $laporan->updated_at;
                            @endphp
                            
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 mt-1 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Dilaporkan</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($laporan->pelaporan_tanggal)->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3 mt-1 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Selesai</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($tanggalDitangani)->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-1 h-8 bg-green-500 rounded-full mr-4"></div>
                    <h2 class="text-xl font-semibold text-gray-900">Penilaian Anda <span class="text-red-500">*</span></h2>
                </div>

                <form id="feedbackForm" action="{{ route('feedback-store') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="report_id" value="{{ $laporan->pelaporan_id }}">

                    <!-- Rating Section -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <label class="block text-lg font-semibold text-gray-900 mb-4">
                            Rating Kepuasan <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ rating: 0 }" class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex items-center space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden rating-input" 
                                        x-model="rating" />
                                    <button type="button" @click="rating = {{ $i }}; document.querySelector('input[name=rating][value=\'{{ $i }}\']').checked = true;"
                                        x-bind:class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                        class="text-3xl hover:text-yellow-400 transition-colors focus:outline-none hover:scale-110 transform star-button"
                                        data-rating="{{ $i }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">1 = Buruk</span> • 
                                <span class="font-medium">5 = Sangat Puas</span>
                            </div>
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                        @enderror
                        <div id="rating-error" class="text-red-500 text-sm mt-3 hidden">
                            Rating kepuasan harus diisi!
                        </div>
                    </div>

                    <!-- Comment Section -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <label class="block text-lg font-semibold text-gray-900 mb-4">
                            Komentar & Saran <span class="text-gray-400 text-sm font-normal">(Opsional)</span>
                        </label>
                        <textarea name="comment" rows="6"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-gray-900 placeholder-gray-500"
                            placeholder="Ceritakan pengalaman Anda tentang penanganan perbaikan ini. Apakah teknisi datang tepat waktu? Apakah perbaikan dilakukan dengan baik? Saran untuk perbaikan selanjutnya?"></textarea>
                        
                        <div class="mt-3 text-sm text-gray-500 bg-blue-50 rounded-lg p-3">
                            <i class="bi bi-lightbulb text-blue-600 mr-2"></i>
                            <strong>Tips:</strong> Berikan detail yang membantu untuk meningkatkan pelayanan kami
                        </div>
                        
                        @error('comment')
                            <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.feedback') }}"
                            class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 font-medium text-center">
                            <i class="bi bi-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all duration-200 font-medium hover:shadow-lg transform hover:scale-105">
                            <i class="bi bi-send mr-2"></i>Kirim Umpan Balik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <dialog id="photoModal" class="modal">
        <div class="modal-box max-w-4xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div id="photoContainer" class="text-center">
                <!-- Photos will be loaded here -->
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Confirmation Modal -->
    <div id="konfirmasiKirimModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-lg shadow-xl">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Pengiriman</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin mengirim umpan balik ini?</p>
                
                <div class="flex justify-end space-x-3">
                    <button id="batalKirimBtn" type="button"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button id="lanjutKirimBtn" type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Ya, Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300">
        <div id="toastContent" class="px-4 py-3 rounded-lg shadow-lg text-white font-medium">
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
                alert(message; // Fallback
                return;
            }
            
            // Set message
            toastMessage.textContent = message;
            
            // Set color based on type
            const colorClass = type === 'green' ? 'bg-green-500' : 
                              type === 'red' ? 'bg-red-500' : 'bg-blue-500';
            
            toastContent.className = `px-4 py-3 rounded-lg shadow-lg text-white font-medium ${colorClass}`;
            
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

        // Enhanced form validation function
        function validateForm() {
            const form = document.getElementById('feedbackForm');
            const rating = form.querySelector('input[name="rating"]:checked');
            const ratingError = document.getElementById('rating-error');
            
            // Hide previous error
            ratingError.classList.add('hidden');
            
            if (!rating) {
                // Show error message
                ratingError.classList.remove('hidden');
                ratingError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Show toast as well
                showToast('Rating kepuasan harus diisi!', 'red');
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
            
            // Enhanced star rating handler
            const starButtons = document.querySelectorAll('.star-button');
            starButtons.forEach((button) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const ratingValue = this.getAttribute('data-rating');
                    const ratingInput = document.querySelector(`input[name="rating"][value="${ratingValue}"]`);
                    
                    if (ratingInput) {
                        // Clear all other radio buttons
                        document.querySelectorAll('input[name="rating"]').forEach(input => {
                            input.checked = false;
                        });
                        
                        // Set the selected one
                        ratingInput.checked = true;
                        
                        // Hide error message if visible
                        const ratingError = document.getElementById('rating-error');
                        if (ratingError) {
                            ratingError.classList.add('hidden');
                        }
                        
                        console.log('Rating selected:', ratingValue);
                    }
                });
            });
            
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