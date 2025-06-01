@extends('layouts.main')
@section('judul', 'Laporan Kerusakan Fasilitas')

@section('content')
    <div class="container mx-auto px-4 py-4">

        <!-- Main Card -->
        <div class="hidden md:block overflow-hidden border border-gray-200 shadow-md rounded-xl bg-white">
            <!-- Form -->
            <form id="pelaporanForm" enctype="multipart/form-data" class="px-6 pb-3 space-y-4">
                @csrf
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 ">Informasi Laporan</h2>

                <!-- Card Body -->
                <div class="space-y-4">

                    <!-- Lokasi Kerusakan -->
                    <div class="form-control w-full relative"> <!-- Lokasi Kerusakan -->
                        <label for="search-lokasi" class="label">
                            <span class="label-text text-base text-gray-700 font-semibold">Kerusakan Fasilitas</span>
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search-lokasi"
                                placeholder="Cari Fasilitas..."
                                autocomplete="off"
                                class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <input type="hidden" id="lokasi" name="lokasi"/>
                        </div>
                    </div> <!-- End of Lokasi Kerusakan -->

                    <!-- Dropdown -->
                    <div id="dropdown"
                         class="w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto hidden mt-1">
                        <ul id="lokasi-options" class="py-1 text-sm divide-y divide-gray-100"></ul>
                        <div id="not-found" class="px-4 py-3 text-sm text-gray-500 italic bg-gray-50 hidden">
                            Tidak ada lokasi yang cocok ditemukan
                        </div>
                    </div> <!-- End of Dropdown -->

                    <!-- Skala Kerusakan -->
                    <div class="space-y-3">
                        <label class="label-text text-base text-gray-700 font-semibold">Skala Kerusakan</label>
                        <div id="radio-group" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Ringan -->
                            <label class="relative cursor-pointer">
                                <input id="skala-ringan" type="radio" name="skala-kerusakan" value="Ringan"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                    transition-all duration-500 ease-in-out transform
                                    peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:scale-105
                                    border-gray-200 hover:border-gray-300 hover:bg-gray-50">
                                    <div
                                        class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-green-600 text-xl">1</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Ringan</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                    Kerusakan kecil, masih bisa digunakan
                                </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-green-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>

                            <!-- Sedang -->
                            <label class="relative cursor-pointer">
                                <input id="skala-sedang" type="radio" name="skala-kerusakan" value="Sedang"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                transition-all duration-500 ease-in-out transform
                                peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:scale-105
                                border-gray-200 hover:border-gray-300 hover:bg-gray-50">
                                    <div
                                        class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-yellow-600 text-xl">2</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Sedang</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                    Fungsi terganggu, perlu perbaikan
                                </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-yellow-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>

                            <!-- Berat -->
                            <label class="relative cursor-pointer">
                                <input id="skala-berat" type="radio" name="skala-kerusakan" value="Berat"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                transition-all duration-500 ease-in-out transform
                                peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:scale-105
                                border-gray-200 hover:border-gray-300 hover:bg-gray-50">
                                    <div
                                        class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-red-600 text-xl">3</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Berat</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                    Tidak berfungsi, butuh penggantian
                                </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-red-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div> <!-- End of Skala Kerusakan -->

                    <!-- Frekuensi Penggunaan -->
                    <div class="space-y-3">
                        <label class="label-text text-base text-gray-700 font-semibold">Frekuensi Penggunaan</label>
                        <div id="radio-group" class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                            <!-- Jarang -->
                            <label class="relative cursor-pointer">
                                <input id="frekuensi-jarang" type="radio" name="frekuensi-penggunaan" value="Jarang"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                    transition-all duration-500 ease-in-out transform
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:scale-105
                                    border-gray-200 hover:border-blue-300 hover:bg-blue-25">
                                    <div
                                        class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-blue-600 text-xl">1</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Jarang</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                      Digunakan sesekali saja
                                    </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-blue-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>

                            <!-- Sedang -->
                            <label class="relative cursor-pointer">
                                <input id="frekuensi-sedang" type="radio" name="frekuensi-penggunaan" value="Sedang"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                    transition-all duration-500 ease-in-out transform
                                    peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:scale-105
                                    border-gray-200 hover:border-purple-300 hover:bg-purple-25">
                                    <div
                                        class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-purple-600 text-xl">2</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Sedang</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                      Dipakai secara reguler
                                    </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-purple-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label> <!-- End of Sedang -->

                            <!-- Sering -->
                            <label class="relative cursor-pointer">
                                <input id="frekuensi-sering" type="radio" name="frekuensi-penggunaan" value="Sering"
                                       class="peer sr-only"/>
                                <div class="flex flex-col items-center p-4 rounded-lg border-2
                                    transition-all duration-500 ease-in-out transform
                                    peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:scale-105
                                    border-gray-200 hover:border-orange-300 hover:bg-orange-25">
                                    <div
                                        class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mb-2 transition-all duration-500 ease-in-out">
                                        <span class="text-orange-600 text-xl">3</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Sering</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">
                                      Digunakan setiap hari atau intensif
                                    </span>
                                    <svg
                                        class="absolute top-2 right-2 h-5 w-5 text-orange-500 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label> <!-- End of Sering -->

                        </div>
                    </div> <!-- End of Frekuensi Penggunaan -->

                    <!-- Deskripsi Kerusakan -->
                    <div class="grid gap-2">
                        <label for="deskripsi" class="label-text text-base text-gray-700 font-semibold">Deskripsi
                            Kerusakan</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            maxlength="1000"
                            placeholder="Contoh: AC tidak menyala, mengeluarkan suara berisik"
                            class="w-full min-h-[120px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                        <div class="text-sm text-gray-500 text-right"><span id="deskripsi-count">0</span> dari 1000
                        </div>
                    </div> <!-- End of Deskripsi Kerusakan -->

                    <!-- Upload Foto Kerusakan -->
                    <div class="grid gap-2"> <!-- Upload Foto Kerusakan -->
                        <label for="foto"
                               class="label-text text-base text-gray-700 font-semibold flex items-center gap-1">
                            Upload Foto Kerusakan
                            <i class="bi bi-info-circle text-gray-400 cursor-help"
                               title="Gunakan foto yang jelas agar proses perbaikan cepat diproses. Anda dapat mengunggah hingga 3 foto."></i>
                        </label>
                        <label
                            id="upload-area"
                            for="foto"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
                        >
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="bi bi-upload text-2xl text-gray-500 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 10MB)</p>
                                <p id="foto-counter" class="text-xs text-gray-500 italic mt-1">
                                    (Opsional, tapi sangat disarankan untuk mempercepat proses perbaikan)
                                </p>
                            </div>
                            <input
                                id="foto"
                                name="foto"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                multiple
                            />
                        </label>
                    </div> <!-- End of Upload Area -->

                    <!-- Preview Container -->
                    <div id="preview-grid" class="grid grid-cols-3 gap-4 mt-4">
                    </div> <!-- End of Preview Container -->

                    <!-- Submit Button -->
                    <div class="pt-4 pb-2 flex justify-end">
                        <button
                            type="submit"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-transform hover:scale-105">
                            <i class="bi bi-send"></i> Kirim Laporan
                        </button>
                    </div> <!--  End of Submit Button -->
                </div> <!-- End of Card Body -->
            </form> <!-- End of Form -->
        </div> <!-- End of Card Body -->
    </div> <!-- End of Main Card -->


    <!-- Modal Zoom Foto -->
    <div id="zoomModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <img id="zoomedImage" src="" class="max-w-full max-h-full rounded-lg shadow-lg"/>
    </div> <!-- End of Modal Zoom Foto -->
@endsection
@push('skrip')
    <script>
        // -----------------------------
        // Location input and dropdown variables
        // -----------------------------

        const searchInput = document.getElementById('search-lokasi');
        const lokasiHidden = document.getElementById('lokasi');
        const dropdown = document.getElementById('dropdown');
        const optionsList = document.getElementById('lokasi-options');
        const notFound = document.getElementById('not-found');

        // -----------------------------
        // Image upload and preview variables
        // -----------------------------

        const fotoInput = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const removePreviewBtn = document.getElementById('remove-preview');
        const uploadLabel = fotoInput.closest('label');
        const uploadArea = document.getElementById('upload-area');
        const previewGrid = document.getElementById('preview-grid');

        // -----------------------------
        // File upload configuration variables
        // -----------------------------

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxFileSize = 10 * 1024 * 1024;
        const maxFoto = 3;

        // -----------------------------
        // State management variables
        // -----------------------------

        let locations = [];
        let activeIndex = -1;
        let lastToastTime = 0;
        let currentOptions = [];
        let uploadedFiles = [];

        // -----------------------------
        // Lokasi: Dropdown handling
        // -----------------------------

        function showDropdown() {
            dropdown.classList.remove('hidden');
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
            activeIndex = -1;
        }

        function renderOptions(filter) {
            optionsList.innerHTML = '';
            const terms = filter.toLowerCase().split(/\s+/).filter(Boolean);

            currentOptions = locations.filter(loc => {
                const searchable = `${loc.label} ${loc.search || ''}`.toLowerCase();
                return terms.every(term => searchable.includes(term));
            });

            if (!currentOptions.length) {
                notFound.classList.remove('hidden');
            } else {
                notFound.classList.add('hidden');
                currentOptions.slice(0, 8).forEach((loc, i) => {
                    const li = document.createElement('li');
                    li.textContent = loc.label;
                    li.className = "px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors";
                    li.dataset.index = i;
                    li.onclick = () => selectOption(i);
                    optionsList.appendChild(li);
                });
            }
        }

        function selectOption(index) {
            if (index < 0 || index >= currentOptions.length) return;
            const selected = currentOptions[index];
            searchInput.value = selected.label;
            lokasiHidden.value = selected.id;
            hideDropdown();
        }

        function updateActiveOption() {
            optionsList.querySelectorAll('li').forEach((li, i) => {
                li.classList.toggle('bg-blue-100', i === activeIndex);
            });
        }

        // -----------------------------
        // Lokasi: Input event handlers
        // -----------------------------

        function initializeSearchInputEvents() {
            searchInput.addEventListener('input', function () {
                const filter = this.value.trim();
                filter ? (showDropdown(), renderOptions(filter)) : hideDropdown();
            });

            searchInput.addEventListener('keydown', function (e) {
                if (dropdown.classList.contains('hidden') || !currentOptions.length) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = (activeIndex + 1) % currentOptions.length;
                    updateActiveOption();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = (activeIndex - 1 + currentOptions.length) % currentOptions.length;
                    updateActiveOption();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    selectOption(activeIndex);
                }
            });

            searchInput.addEventListener('focus', function () {
                const value = this.value.trim();
                if (value) {
                    showDropdown();
                    renderOptions(value);
                }
            });

            document.addEventListener('click', e => {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    hideDropdown();
                }
            });
        }

        // -----------------------------
        // Lokasi: Fetch options
        // -----------------------------

        (async function fetchLocations() {
            try {
                const res = await fetch('/users/lokasi-options');
                if (!res.ok) throw new Error(`Fetch failed: ${res.status}`);
                locations = await res.json();
            } catch (err) {
                console.error('Fetch error:', err);
            }
        })();

        initializeSearchInputEvents();

        // -----------------------------
        // Form handling
        // -----------------------------

        document.getElementById('pelaporanForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);

            const skala = document.querySelector('input[name="skala-kerusakan"]:checked')?.value;
            const frekuensi = document.querySelector('input[name="frekuensi-penggunaan"]:checked')?.value;

            const values = {
                lokasi: form.querySelector('#lokasi').value.trim(),
                deskripsi: form.querySelector('#deskripsi').value.trim(),
                foto: uploadedFiles,
                skala,
                frekuensi
            };

            if (!validateForm(values)) return;

            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            showLoading(submitBtn);

            uploadedFiles.forEach((file, index) => {
                formData.append(`foto[${index}]`, file);
            });

            formData.append('skala', skala);
            formData.append('frekuensi', frekuensi);

            try {
                const res = await fetch('{{ route('store-pelaporan') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                });

                const data = await res.json();
                handleResponse(res, data, form);
            } catch (err) {
                console.error('Error:', err);
                showToast('Terjadi kesalahan pada sistem.', 'red');
            } finally {
                submitBtn.disabled = false;
                hideLoading(submitBtn, originalText);
            }
        });

        function validateForm({lokasi, deskripsi}) {
            const skalaChecked = document.querySelector('input[name="skala-kerusakan"]:checked');
            const frekuensiChecked = document.querySelector('input[name="frekuensi-penggunaan"]:checked');

            if (!lokasi) {
                showToast("Fasilitas harus dipilih.", "red");
                return false;
            }

            if (!skalaChecked) {
                showToast("Skala kerusakan harus dipilih.", "red");
                return false;
            }

            if (!frekuensiChecked) {
                showToast("Frekuensi penggunaan harus dipilih.", "red");
                return false;
            }

            if (!deskripsi) {
                showToast("Deskripsi harus diisi.", "red");
                return false;
            }

            if (deskripsi.length > 1000) {
                showToast("Deskripsi tidak boleh lebih dari 1000 karakter.", "red");
                return false;
            }

            return true;
        }

        function handleResponse(res, data, form) {
            if (res.ok) {
                form.reset();
                uploadedFiles = [];
                previewGrid.innerHTML = '';
                const fotoCounter = document.getElementById('foto-counter');
                if (fotoCounter) {
                    fotoCounter.textContent = "(Opsional, tapi sangat disarankan untuk mempercepat proses perbaikan)";
                }
                const dataTransfer = new DataTransfer();
                fotoInput.files = dataTransfer.files;
                fotoInput.disabled = false;
                uploadArea.classList.remove('opacity-50', 'cursor-not-allowed');

                // Render ulang preview (kosong + tombol tambah jika perlu)
                renderPreview();

                // Tampilkan toast dan reload halaman
                showToast(data.message || "Laporan berhasil dikirim.", "green", () => location.reload());
            } else if (data.errors) {
                for (const key in data.errors) showToast(`${key}: ${data.errors[key][0]}`, "red");
            } else {
                showToast(data.message || 'Terjadi kesalahan.', "red");
            }
        }

        // -----------------------------
        // Utilitas: Toast & Loading
        // -----------------------------

        function showToast(message, color = "green", onClick = null) {
            const now = Date.now();
            if (now - lastToastTime < 2000) return;
            lastToastTime = now;

            const icon = color === "green"
                ? '<i class="bi bi-check-circle-fill text-xl"></i>'
                : '<i class="bi bi-exclamation-circle-fill text-xl"></i>';

            const background = color === "green"
                ? "linear-gradient(to right, #00b09b, #96c93d)"
                : "linear-gradient(to right, #ff5f6d, #ffc371)";

            Toastify({
                text: `<div class="flex items-center gap-3">${icon}<span>${message}</span></div>`,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: background,
                className: "rounded-lg shadow-md",
                stopOnFocus: true,
                escapeMarkup: false,
                style: {
                    padding: "12px 20px",
                    fontWeight: "500",
                    minWidth: "300px"
                },
                onClick: onClick || function () {}
            }).showToast();
        }

        function showLoading(button) {
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"></path>
                </svg> Mengirim...
            `;
        }

        function hideLoading(button, originalText) {
            button.innerHTML = originalText;
        }

        // -----------------------------
        // UI: Deskripsi character count
        // -----------------------------

        document.addEventListener("DOMContentLoaded", function () {
            const deskripsiInput = document.getElementById("deskripsi");
            const deskripsiCount = document.getElementById("deskripsi-count");

            deskripsiInput.addEventListener("input", function () {
                const currentLength = deskripsiInput.value.length;
                deskripsiCount.textContent = currentLength;

                if (currentLength > 1000) {
                    deskripsiInput.value = deskripsiInput.value.substring(0, 1000);
                    deskripsiCount.textContent = 1000;
                }
            });
        });

        // -----------------------------
        // UI: Toggle radio button
        // -----------------------------

        document.addEventListener("DOMContentLoaded", function () {
            enableToggleRadio("skala-kerusakan");
            enableToggleRadio("frekuensi-penggunaan");
        });

        function enableToggleRadio(groupName) {
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            let lastChecked = null;

            radios.forEach(radio => {
                radio.addEventListener("click", function () {
                    if (this === lastChecked) {
                        this.checked = false;
                        lastChecked = null;
                        this.dispatchEvent(new Event('change', {bubbles: true}));
                    } else {
                        lastChecked = this;
                    }
                });
            });
        }

        // -----------------------------
        // UX: Handle Enter key submit
        // -----------------------------
        document.querySelectorAll('#search-lokasi, #deskripsi, #foto').forEach(field => {
            field.addEventListener('keydown', function (e) {
                const isSearchLokasi = field.id === 'search-lokasi';
                const dropdownIsVisible = !dropdown.classList.contains('hidden');

                if (e.key === 'Enter') {
                    // Special handling for search-lokasi input
                    if (isSearchLokasi) {
                        e.preventDefault();
                        if (dropdownIsVisible) {
                            selectOption(activeIndex); // hanya pilih lokasi
                        }
                        // Jika dropdown tidak terlihat, jangan submit atau lakukan apapun
                        return; // Hindari submit saat masih di search-lokasi
                    }

                    // Handle form submission for other fields
                    e.preventDefault();

                    const form = document.getElementById('pelaporanForm');
                    const lokasi = form.querySelector('#lokasi');
                    const deskripsi = form.querySelector('#deskripsi');
                    const skalaChecked = document.querySelector('input[name="skala-kerusakan"]:checked');
                    const firstSkala = document.querySelector('input[name="skala-kerusakan"]');
                    const frekuensiChecked = document.querySelector('input[name="frekuensi-penggunaan"]:checked');
                    const firstFrekuensi = document.querySelector('input[name="frekuensi-penggunaan"]');

                    if (!lokasi.value.trim()) {
                        showToast("Fasilitas harus dipilih.", "red");
                        document.querySelector('#search-lokasi').focus();
                        return;
                    }
                    if (!skalaChecked) {
                        showToast("Skala kerusakan harus dipilih.", "red");
                        if (firstSkala) firstSkala.focus();
                        return;
                    }
                    if (!frekuensiChecked) {
                        showToast("Frekuensi penggunaan harus dipilih.", "red");
                        if (firstFrekuensi) firstFrekuensi.focus();
                        return;
                    }
                    if (!deskripsi.value.trim()) {
                        showToast("Deskripsi harus diisi.", "red");
                        deskripsi.focus();
                        return;
                    }

                    // Submit form
                    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                }
            });
        });

        // -----------------------------
        // Upload foto dan preview
        // -----------------------------

        document.addEventListener('DOMContentLoaded', function () {
            fotoInput.addEventListener('change', handleFotoChange);

            ['dragenter', 'dragover'].forEach(evt => {
                uploadArea.addEventListener(evt, e => {
                    e.preventDefault();
                    uploadArea.classList.add('bg-blue-50', 'border-blue-300');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                uploadArea.addEventListener(evt, e => {
                    e.preventDefault();
                    uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
                });
            });

            uploadArea.addEventListener('drop', handleFileDrop);
        });

        function handleFotoChange(e) {
            const files = Array.from(e.target.files);
            addFiles(files);
        }

        function handleFileDrop(e) {
            e.preventDefault();
            const droppedFiles = [...e.dataTransfer.files];

            const totalFiles = uploadedFiles.length + droppedFiles.length;

            if (totalFiles > maxFoto) {
                showToast(`Maksimal ${maxFoto} foto dapat diupload.`, "red");
                return;
            }

            for (const file of droppedFiles) {
                if (uploadedFiles.length >= maxFoto) break;
                if (validateFile(file)) {
                    uploadedFiles.push(file);
                    renderPreview();
                }
            }

            updateInputFiles(); // sinkronkan input file
        }

        function addFiles(files) {
            const totalFiles = uploadedFiles.length + files.length;

            if (totalFiles > maxFoto) {
                showToast(`Maksimal ${maxFoto} foto dapat diupload.`, "red");
                return;
            }

            for (const file of files) {
                if (uploadedFiles.length >= maxFoto) break;
                if (validateFile(file)) {
                    uploadedFiles.push(file);
                    renderPreview();
                }
            }

            updateInputFiles();
        }

        function renderPreview() {
            previewGrid.innerHTML = "";

            if (uploadedFiles.length === 0) {
                return;
            }

            if (uploadedFiles.length === 0 && maxFoto > 0) {
                const div = document.createElement('div');
                div.className = "relative border rounded-lg overflow-hidden w-full h-32 flex items-center justify-center bg-gray-50";
                div.innerHTML = `
                    <button type="button" class="flex flex-col items-center text-gray-400 w-full h-full justify-center" id="add-foto-button-empty">
                        <i class="bi bi-image text-2xl"></i>
                        <span class="text-sm mt-1">Tambah foto</span>
                    </button>
                `;
                previewGrid.appendChild(div);

                div.querySelector('#add-foto-button-empty').addEventListener('click', () => {
                    fotoInput.click();
                });

                return;
            }

            // Render uploaded files
            uploadedFiles.forEach((file, i) => {
                const div = document.createElement('div');
                div.className = "relative border rounded-lg overflow-hidden w-full h-32 flex items-center justify-center bg-gray-50";

                const reader = new FileReader();
                reader.onload = function (e) {
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-contain bg-white" />
                        <button type="button" class="absolute top-1 right-1 text-red-500 text-sm bg-white rounded-full p-1" title="Hapus">&times;</button>
                    `;

                    div.querySelector('button').addEventListener('click', () => {
                        uploadedFiles.splice(i, 1);
                        updateInputFiles();
                        renderPreview();
                    });
                };
                reader.readAsDataURL(file);

                previewGrid.appendChild(div);
            });

            // Add "Tambah foto" button if less than maxFoto
            if (uploadedFiles.length < maxFoto) {
                const div = document.createElement('div');
                div.className = "relative border rounded-lg overflow-hidden w-full h-32 flex items-center justify-center bg-gray-50";
                div.innerHTML = `
                    <button type="button" class="flex flex-col items-center text-gray-400 w-full h-full justify-center" id="add-foto-button-${uploadedFiles.length}">
                        <i class="bi bi-image text-2xl"></i>
                        <span class="text-sm mt-1">Tambah foto</span>
                    </button>
                `;
                previewGrid.appendChild(div);

                div.querySelector(`#add-foto-button-${uploadedFiles.length}`).addEventListener('click', () => {
                    fotoInput.click();
                });
            }
        }

        function validateFile(file) {
            if (!allowedTypes.includes(file.type)) {
                showToast("Jenis file tidak didukung. Gunakan PNG, JPG, atau JPEG.", "red");
                return false;
            }
            if (file.size > maxFileSize) {
                showToast("Ukuran file maksimal 10MB.", "red");
                return false;
            }
            return true;
        }

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();
            uploadedFiles.forEach(file => dataTransfer.items.add(file));
            fotoInput.files = dataTransfer.files;

            // Handle disable input
            if (uploadedFiles.length >= maxFoto) {
                fotoInput.disabled = true;
                uploadArea.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                fotoInput.disabled = false;
                uploadArea.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Update teks jumlah foto
            const fotoCounter = document.getElementById('foto-counter');
            if (uploadedFiles.length > 0) {
                fotoCounter.textContent = `(${uploadedFiles.length} / ${maxFoto} foto terunggah)`;
            } else {
                fotoCounter.textContent = "(Opsional, tapi sangat disarankan untuk mempercepat proses perbaikan)";
            }
        }
    </script>
@endpush
