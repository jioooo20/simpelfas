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
                            <span class="label-text text-base text-gray-700 font-semibold">
                                Kerusakan Fasilitas <span class="text-red-500 text-sm" title="Wajib diisi">*</span>
                            </span>
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
                        <label class="label-text text-base text-gray-700 font-semibold">Skala Kerusakan
                            <span class="text-red-500 text-sm" title="Wajib diisi">*</span></label>
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
                        <label class="label-text text-base text-gray-700 font-semibold">Frekuensi Penggunaan
                            <span class="text-red-500 text-sm" title="Wajib diisi">*</span></label>
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
                            Kerusakan <span class="text-red-500 text-sm" title="Wajib diisi">*</span>
                        </label>
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
                                <p class="text-xs text-gray-500">Upload hingga 3 file (PNG, JPG, JPEG), total ukuran
                                    maksimal 10 MB</p>
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

    <div id="konfirmasiKirimModal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-title"
         aria-describedby="modal-description"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">

        <div
            class="w-11/12 max-w-md bg-white rounded-2xl shadow-xl transform transition-all duration-300 ease-in-out scale-95 opacity-0">
            <div class="p-6 text-center">

                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>

                <h2 id="modal-title" class="text-xl font-semibold text-gray-900">
                    Konfirmasi Pengiriman
                </h2>

                <p id="modal-description" class="text-sm text-gray-500 mt-2 mb-6">
                    Apakah Anda yakin ingin mengirim laporan ini? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-center gap-4">
                    <button id="batalKirimBtn"
                            type="button"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all">
                        Batal
                    </button>
                    <button id="lanjutKirimBtn"
                            type="button"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        Ya, Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        // Modal confirmation variables
        // -----------------------------
        const konfirmasiKirimModal = document.getElementById('konfirmasiKirimModal');
        const modalContent = konfirmasiKirimModal ? konfirmasiKirimModal.querySelector('.transform') : null;
        const batalKirimBtn = document.getElementById('batalKirimBtn');
        const lanjutKirimBtn = document.getElementById('lanjutKirimBtn');
        let currentFormToSubmit = null;

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
        // Lokasi: UI helpers
        // -----------------------------

        function showDropdown() {
            dropdown.classList.remove('hidden');
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
            activeIndex = -1;
        }

        // -----------------------------
        // Lokasi: Filter helpers
        // -----------------------------

        function parseFilter(filter = '') {
            return filter.toLowerCase().split(/\s+/).filter(Boolean);
        }

        function getFilteredLocations(filter, locations) {
            const terms = parseFilter(filter);

            return locations.filter(loc => {
                if (!terms.length && filter === '') return true;
                if (!terms.length && filter !== '') return false;

                const searchable = `${loc.label} ${loc.search || ''} ${loc.statusText || ''}`.toLowerCase();

                return terms.every(term =>
                    searchable.includes(term) ||
                    searchable.split(/[\s\-]+/).some(word => word.startsWith(term))
                );
            });
        }

        // -----------------------------
        // Lokasi: Option element helpers
        // -----------------------------

        function createStatusBadge(statusCode = '', statusText = '') {
            if (!statusText || statusCode === 'BAIK') return null;

            const badge = document.createElement('span');
            badge.textContent = statusText;
            badge.className = 'px-2 py-0.5 text-xs font-semibold rounded-full flex-shrink-0';

            if (statusCode === 'RUSAK') {
                badge.classList.add('bg-red-100', 'text-red-800');
            } else if (statusCode === 'DALAM PERBAIKAN') {
                badge.classList.add('bg-blue-100', 'text-blue-800');
            } else {
                badge.classList.add('bg-gray-100', 'text-gray-800');
            }

            return badge;
        }

        function createOptionItem(loc, index) {
            const li = document.createElement('li');
            const statusCode = loc.statusCode || '';
            const isSelectable = !(statusCode === 'RUSAK' || statusCode === 'DALAM PERBAIKAN');

            let liClasses = 'px-4 py-2 transition-colors flex justify-between items-center';
            li.dataset.originalIndex = index;

            if (isSelectable) {
                liClasses += ' hover:bg-blue-50 cursor-pointer';
                li.onclick = () => selectOption(index);
            } else {
                liClasses += ' text-gray-500 cursor-not-allowed opacity-75';
                li.onclick = event => {
                    event.stopPropagation();
                    console.warn(`Klik pada item yang dinonaktifkan: ${loc.label}`);
                };
            }
            li.className = liClasses;

            const labelTextSpan = document.createElement('span');
            labelTextSpan.textContent = loc.label;
            labelTextSpan.className = 'flex-grow mr-2 overflow-hidden overflow-ellipsis whitespace-nowrap';
            li.appendChild(labelTextSpan);

            const badge = createStatusBadge(statusCode, loc.statusText);
            if (badge) li.appendChild(badge);

            return li;
        }

        // -----------------------------
        // Lokasi: Rendering & selection
        // -----------------------------

        function isOptionSelectable(index) {
            if (!currentOptions || index < 0 || index >= currentOptions.length) {
                return false;
            }
            const option = currentOptions[index];
            if (!option) return false;

            const statusCode = option.statusCode || '';
            return !(statusCode === 'RUSAK' || statusCode === 'DALAM PERBAIKAN');
        }

        function selectOption(index) {
            if (index < 0 || index >= currentOptions.length) return;

            const selected = currentOptions[index];
            const statusCode = selected.statusCode || '';
            const isSelectable = !(statusCode === 'RUSAK' || statusCode === 'DALAM PERBAIKAN');

            if (!isSelectable) return;

            searchInput.value = selected.label;
            lokasiHidden.value = selected.id;
            hideDropdown();
        }

        function updateActiveOption() {
            optionsList.querySelectorAll('li').forEach((li, i) => {
                const isActive = i === activeIndex;
                li.classList.toggle('bg-blue-100', isActive);
                if (isActive) {
                    li.scrollIntoView({block: 'nearest', inline: 'nearest'});
                }
            });
        }

        function renderOptions(filter = '') {
            optionsList.innerHTML = '';
            currentOptions = getFilteredLocations(filter, locations);

            if (!currentOptions.length) {
                notFound.classList.remove('hidden');
                activeIndex = -1;
                return;
            }

            notFound.classList.add('hidden');
            currentOptions.forEach((loc, i) => optionsList.appendChild(createOptionItem(loc, i)));

            if (filter) activeIndex = -1;
            updateActiveOption();
        }

        // -----------------------------
        // Lokasi: Navigasi keyboard & input event handlers
        // -----------------------------

        function debounce(fn, delay = 200) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(null, args), delay);
            };
        }

        function initializeSearchInputEvents() {
            searchInput.addEventListener('input', function () {
                const filter = this.value.trim();
                if (filter) {
                    renderOptions(filter);
                    showDropdown();
                } else {
                    hideDropdown();
                }
            });

            /* ----------  N A V I G A S I   P A N A H  ---------- */
            searchInput.addEventListener('keydown', function (e) {
                if (dropdown.classList.contains('hidden') || !currentOptions.length) return;

                const nextSelectable = (start, step) => {
                    for (let i = start; i >= 0 && i < currentOptions.length; i += step) {
                        if (isOptionSelectable(i)) return i;
                    }
                    return -1;
                };

                if (e.key === 'ArrowDown') {
                    e.preventDefault();

                    const begin = activeIndex === -1 ? 0 : activeIndex + 1;
                    const candidate = nextSelectable(begin, +1);

                    if (candidate !== -1) {
                        activeIndex = candidate;
                        updateActiveOption();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();

                    const begin = activeIndex === -1 ? currentOptions.length - 1 : activeIndex - 1;
                    const candidate = nextSelectable(begin, -1);

                    if (candidate !== -1) {
                        activeIndex = candidate;
                        updateActiveOption();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeIndex !== -1 && isOptionSelectable(activeIndex)) {
                        selectOption(activeIndex);
                    }
                }
            });

            searchInput.addEventListener('focus', function () {
                const value = this.value.trim();
                renderOptions(value);
                showDropdown();
            });

            document.addEventListener('click', e => {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) hideDropdown();
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
        // Form: Event handling
        // -----------------------------

        document.getElementById('pelaporanForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;

            const lokasi = form.querySelector('#lokasi').value.trim();
            const deskripsi = form.querySelector('#deskripsi').value.trim();
            const skalaChecked = document.querySelector('input[name="skala-kerusakan"]:checked');
            const frekuensiChecked = document.querySelector('input[name="frekuensi-penggunaan"]:checked');

            if (!validateForm({lokasi, deskripsi, skalaChecked, frekuensiChecked, uploadedFiles})) {
                return;
            }

            showKonfirmasiModal(form);
        });

        async function kirimForm(form) {
            if (!form) {
                showToast("Terjadi kesalahan internal saat mencoba mengirim form.", "red");
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn) {
                showToast("Terjadi kesalahan: tombol submit tidak ditemukan.", "red");
                return;
            }

            const formData = new FormData(form);

            const skala = document.querySelector('input[name="skala-kerusakan"]:checked')?.value;
            const frekuensi = document.querySelector('input[name="frekuensi-penggunaan"]:checked')?.value;

            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            showLoading(submitBtn);

            uploadedFiles.forEach((file, index) => {
                formData.append(`foto[${index}]`, file);
            });

            if (skala) formData.append('skala', skala);
            if (frekuensi) formData.append('frekuensi', frekuensi);

            try {
                const res = await fetch('{{ route('store-pelaporan') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                handleResponse(res, data, form);
            } catch (err) {
                console.error('Error submitting form:', err);
                showToast('Terjadi kesalahan pada sistem saat mengirim.', 'red');
            } finally {
                submitBtn.disabled = false;
                hideLoading(submitBtn, originalText);
            }
        }

        // -----------------------------
        // Form: Validation
        // -----------------------------

        function validateForm({lokasi, deskripsi, skalaChecked, frekuensiChecked, uploadedFiles}) {
            const MAX_FOTO = 3;
            if (uploadedFiles && uploadedFiles.length > MAX_FOTO) {
                showToast(`Anda hanya dapat mengunggah maksimal ${MAX_FOTO} foto.`, "red");
                return false;
            }

            if (!lokasi) {
                showToast("Fasilitas harus dipilih.", "red");
                document.getElementById('search-lokasi')?.focus();
                return false;
            }

            if (!skalaChecked) {
                showToast("Skala kerusakan harus dipilih.", "red");
                document.getElementById('skala-kerusakan')?.focus();
                return false;
            }

            if (!frekuensiChecked) {
                showToast("Frekuensi penggunaan harus dipilih.", "red");
                document.getElementById('frekuensi-penggunaan')?.focus();
                return false;
            }

            if (!deskripsi) {
                showToast("Deskripsi harus diisi.", "red");
                document.getElementById('deskripsi')?.focus();
                return false;
            }

            if (deskripsi.length > 1000) {
                showToast("Deskripsi tidak boleh lebih dari 1000 karakter.", "red");
                document.getElementById('deskripsi')?.focus();
                return false;
            }

            return true;
        }

        // -----------------------------
        // Form: Response Handling
        // -----------------------------

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
                renderPreview();
                showToast(data.message || "Laporan berhasil dikirim.", "green", () => location.reload());
            } else if (data.errors) {
                for (const key in data.errors) showToast(`${key}: ${data.errors[key][0]}`, "red");
            } else {
                showToast(data.message || 'Terjadi kesalahan.', "red");
            }
        }

        // -----------------------------
        // Form: Modal Konfirmasi
        // -----------------------------

        function showKonfirmasiModal(formElement) {
            currentFormToSubmit = formElement;
            if (konfirmasiKirimModal && modalContent) {
                // 1. Buat modal terlihat di DOM tapi masih transparan
                konfirmasiKirimModal.classList.remove('pointer-events-none');
                konfirmasiKirimModal.classList.add('opacity-100');

                // 2. Aktifkan transisi untuk konten modal
                modalContent.classList.remove('opacity-0', 'scale-95');
                modalContent.classList.add('opacity-100', 'scale-100');

            } else {
                console.error("Elemen modal 'konfirmasiKirimModal' atau kontennya tidak ditemukan.");
            }
        }

        function hideKonfirmasiModal() {
            if (konfirmasiKirimModal && modalContent) {
                konfirmasiKirimModal.classList.remove('opacity-100');
                konfirmasiKirimModal.classList.add('opacity-0');
                modalContent.classList.remove('opacity-100', 'scale-100');
                modalContent.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    konfirmasiKirimModal.classList.add('pointer-events-none');
                }, 300);
            }
            currentFormToSubmit = null;
        }

        function initKonfirmasiModalHandlers() {
            if (!konfirmasiKirimModal) return;

            if (batalKirimBtn) {
                batalKirimBtn.addEventListener('click', hideKonfirmasiModal);
            }

            if (lanjutKirimBtn) {
                lanjutKirimBtn.addEventListener('click', () => {
                    const formToProcess = currentFormToSubmit;
                    if (formToProcess) {
                        hideKonfirmasiModal();
                        kirimForm(formToProcess);
                    } else {
                        console.error("Tidak ada form yang akan diproses setelah konfirmasi.");
                    }
                });
            }

            konfirmasiKirimModal.addEventListener('click', (event) => {
                if (event.target === konfirmasiKirimModal) {
                    hideKonfirmasiModal();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initKonfirmasiModalHandlers();
        });

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
                onClick: onClick || function () {
                }
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
                    if (isSearchLokasi) {
                        e.preventDefault();
                        if (dropdownIsVisible) {
                            selectOption(activeIndex);
                        }
                        return;
                    }

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
                    form.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}));
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

            const totalSize = getTotalSize([...uploadedFiles, ...droppedFiles]);
            if (totalSize > maxFileSize) {
                showToast("Total ukuran file tidak boleh lebih dari 10MB.", "red");
                return;
            }

            for (const file of droppedFiles) {
                if (uploadedFiles.length >= maxFoto) break;
                if (validateFile(file)) {
                    uploadedFiles.push(file);
                    renderPreview();
                }
            }

            updateInputFiles();
        }

        function addFiles(files) {
            const totalFiles = uploadedFiles.length + files.length;
            if (totalFiles > maxFoto) {
                showToast(`Maksimal ${maxFoto} foto dapat diupload.`, "red");
                return;
            }

            const totalSize = getTotalSize([...uploadedFiles, ...files]);
            if (totalSize > maxFileSize) {
                showToast("Total ukuran file tidak boleh lebih dari 10MB.", "red");
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

        function getTotalSize(files) {
            return files.reduce((acc, file) => acc + file.size, 0);
        }
    </script>
@endpush
