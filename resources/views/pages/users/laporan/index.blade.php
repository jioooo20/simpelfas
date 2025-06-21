@extends('layouts.main')
@section('judul', 'Laporan Kerusakan Fasilitas')

@section('content')
    <!-- Pelaporan Card -->
    <div class="overflow-hidden border border-gray-200 shadow-md rounded-xl bg-white">

        <!-- Laporan form -->
        <form id="pelaporanForm" enctype="multipart/form-data" class="p-4 md:p-6 space-y-4">
            @csrf
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Laporan</h2>

            <!-- Laporan Container -->
            <div class="space-y-6">

                <!-- Lokasi input -->
                <div class="form-control w-full relative">

                    <!-- Label -->
                    <label for="search-lokasi" class="label">
                        <span class="label-text text-base text-gray-700 font-semibold">
                            Kerusakan Fasilitas <span class="text-red-500 text-sm" title="Wajib diisi">*</span>
                        </span>
                    </label> <!-- End of Label -->

                    <!-- Search input with icon -->
                    <div class="relative">
                        <!-- Icon -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z"/>
                            </svg>
                        </div> <!-- End of Icon -->

                        <!-- Input field -->
                        <input
                            type="text"
                            id="search-lokasi"
                            placeholder="Cari Fasilitas..."
                            autocomplete="off"
                            class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <input type="hidden" id="lokasi" name="lokasi"/>
                    </div> <!-- End of Search input with icon -->
                </div> <!-- End of Lokasi input -->

                <!-- Dropdown for lokasi options -->
                <div id="dropdown"
                     class="w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto hidden mt-1">
                    <ul id="lokasi-options" class="py-1 text-sm divide-y divide-gray-100"></ul>
                    <div id="not-found" class="px-4 py-3 text-sm text-gray-500 italic bg-gray-50 hidden">
                        Tidak ada lokasi yang cocok ditemukan
                    </div>
                </div> <!-- End of Dropdown for lokasi options -->

                <!-- Skala Kerusakan -->
                <div class="space-y-3">
                    <label class="label-text text-base text-gray-700 font-semibold">Skala Kerusakan
                        <span class="text-red-500 text-sm" title="Wajib diisi">*</span></label>

                    <!-- Radio buttons for skala kerusakan -->
                    <div id="radio-group" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @php
                            $skalaOptions = [
                                ['value' => 'Ringan', 'id' => 'skala-ringan', 'level' => 1, 'color' => 'green', 'desc' => 'Kerusakan kecil, masih bisa digunakan'],
                                ['value' => 'Sedang', 'id' => 'skala-sedang', 'level' => 2, 'color' => 'yellow', 'desc' => 'Fungsi terganggu, perlu perbaikan'],
                                ['value' => 'Berat', 'id' => 'skala-berat', 'level' => 3, 'color' => 'red', 'desc' => 'Tidak berfungsi, butuh penggantian'],
                            ];
                        @endphp
                        @foreach ($skalaOptions as $option)
                            <label class="relative cursor-pointer">
                                <input id="{{ $option['id'] }}" type="radio" name="skala-kerusakan"
                                       value="{{ $option['value'] }}"
                                       class="peer sr-only"/>

                                <!-- Radio button container -->
                                <div @class([
                                        'flex flex-col items-center p-4 rounded-lg border-2 transition-all duration-300 ease-in-out transform hover:bg-gray-50',
                                        'peer-checked:scale-105 border-gray-200 hover:border-gray-300',
                                        'peer-checked:border-green-500 peer-checked:bg-green-50' => $option['color'] === 'green',
                                        'peer-checked:border-yellow-500 peer-checked:bg-yellow-50' => $option['color'] === 'yellow',
                                        'peer-checked:border-red-500 peer-checked:bg-red-50' => $option['color'] === 'red',
                                        ])>

                                    <!-- Level indicator -->
                                    <div @class([
                                        'w-12 h-12 rounded-full flex items-center justify-center mb-2 transition-all duration-500 ease-in-out',
                                        'bg-green-100' => $option['color'] === 'green',
                                        'bg-yellow-100' => $option['color'] === 'yellow',
                                        'bg-red-100' => $option['color'] === 'red',
                                        ])>
                                        <span @class([
                                            'text-xl',
                                            'text-green-600' => $option['color'] === 'green',
                                            'text-yellow-600' => $option['color'] === 'yellow',
                                            'text-red-600' => $option['color'] === 'red',
                                        ])>{{ $option['level'] }}</span>
                                    </div> <!-- End of Level indicator -->
                                    <span class="font-medium text-gray-800">{{ $option['value'] }}</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">{{ $option['desc'] }}</span>
                                    <svg @class([
                                        'absolute top-2 right-2 h-5 w-5 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform',
                                        'text-green-500' => $option['color'] === 'green',
                                        'text-yellow-500' => $option['color'] === 'yellow',
                                        'text-red-500' => $option['color'] === 'red',
                                        ]) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div> <!-- End of Radio button container -->
                            </label>
                        @endforeach
                    </div> <!-- End of Radio buttons for skala kerusakan -->
                </div> <!-- End of Skala Kerusakan -->

                <!-- Frekuensi Penggunaan -->
                <div class="space-y-3">
                    <label class="label-text text-base text-gray-700 font-semibold">Frekuensi Penggunaan
                        <span class="text-red-500 text-sm" title="Wajib diisi">*</span></label>

                    <!-- Radio buttons for frekuensi penggunaan -->
                    <div id="radio-group-frekuensi" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @php
                            $frekuensiOptions = [
                                ['value' => 'Jarang', 'id' => 'frekuensi-jarang', 'level' => 1, 'color' => 'blue', 'desc' => 'Digunakan sesekali saja'],
                                ['value' => 'Sedang', 'id' => 'frekuensi-sedang', 'level' => 2, 'color' => 'purple', 'desc' => 'Dipakai secara reguler'],
                                ['value' => 'Sering', 'id' => 'frekuensi-sering', 'level' => 3, 'color' => 'orange', 'desc' => 'Digunakan setiap hari atau intensif'],
                            ];
                        @endphp
                        @foreach ($frekuensiOptions as $option)
                            <label class="relative cursor-pointer">
                                <input id="{{ $option['id'] }}" type="radio" name="frekuensi-penggunaan"
                                       value="{{ $option['value'] }}"
                                       class="peer sr-only"/>

                                <!-- Radio button container -->
                                <div @class([
                                       'flex flex-col items-center p-4 rounded-lg border-2 transition-all duration-300 ease-in-out transform hover:bg-gray-50',
                                       'peer-checked:scale-105 border-gray-200',
                                       'hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50' => $option['color'] === 'blue',
                                       'hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50' => $option['color'] === 'purple',
                                       'hover:border-orange-300 peer-checked:border-orange-500 peer-checked:bg-orange-50' => $option['color'] === 'orange',
                                       ])>

                                    <!-- Level indicator -->
                                    <div @class([
                                           'w-12 h-12 rounded-full flex items-center justify-center mb-2 transition-all duration-500 ease-in-out',
                                            'bg-blue-100' => $option['color'] === 'blue',
                                            'bg-purple-100' => $option['color'] === 'purple',
                                            'bg-orange-100' => $option['color'] === 'orange',
                                        ])>
                                        <span @class([
                                               'text-xl',
                                               'text-blue-600' => $option['color'] === 'blue',
                                               'text-purple-600' => $option['color'] === 'purple',
                                               'text-orange-600' => $option['color'] === 'orange',
                                           ])>{{ $option['level'] }}
                                        </span>
                                    </div> <!-- End of Level indicator -->
                                    <span class="font-medium text-gray-800">{{ $option['value'] }}</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">{{ $option['desc'] }}</span>
                                    <svg @class([
                                   'absolute top-2 right-2 h-5 w-5 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-in-out transform',
                                    'text-blue-500' => $option['color'] === 'blue',
                                    'text-purple-500' => $option['color'] === 'purple',
                                    'text-orange-500' => $option['color'] === 'orange',
                                    ]) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                </div> <!-- End of Radio button container -->
                            </label>
                        @endforeach
                    </div> <!-- End of Radio buttons for frekuensi penggunaan -->
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

                <!-- Foto Kerusakan -->
                <div class="grid gap-2">
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
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-2">
                            <i class="bi bi-upload text-2xl text-gray-500 mb-2"></i>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                            </p>
                            <p class="text-xs text-gray-500">Upload hingga 3 file (PNG, JPG, JPEG), total maks 10 MB</p>
                        </div>
                        <input
                            id="foto"
                            name="foto[]" {{-- Tambahkan [] agar bisa multiple upload di backend --}}
                            type="file"
                            accept="image/png, image/jpeg, image/jpg"
                            class="hidden"
                            multiple
                        />
                    </label>
                    <p id="foto-counter" class="text-xs text-gray-500 italic">
                        (Opsional, tapi sangat disarankan untuk mempercepat proses perbaikan)
                    </p>
                </div> <!-- End of Foto Kerusakan -->

                <!-- Preview area for uploaded images -->
                <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                </div> <!-- End of Preview area for uploaded images -->

                <!-- Submit Button -->
                <div class="pt-4 pb-2 flex justify-end">
                    <button
                        type="submit"
                        class="w-full md:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-transform hover:scale-105">
                        <i class="bi bi-send"></i> Kirim Laporan
                    </button>
                </div> <!-- End of Submit Button -->
            </div> <!-- End of Laporan Container -->
        </form> <!-- End of Laporan form -->
    </div> <!-- End of Pelaporan Card -->
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
                <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Konfirmasi Pengiriman</h2>
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

        const abbreviationMap = {
            'Gedung': 'Gd.',
            'Lantai': 'Lt.',
            'Ruang': 'R.',
            'Teori': 'T.',
            'Sipil': 'Sip.',
            'Proyektor': 'Proyektor'
        };

        function abbreviateLabel(label) {
            let abbreviated = label;
            for (const word in abbreviationMap) {
                const regex = new RegExp(`\\b${word}\\b`, 'gi');
                abbreviated = abbreviated.replace(regex, abbreviationMap[word]);
            }
            return abbreviated;
        }

        function createStatusBadge(statusCode, statusText) {
            const badgeStyles = {
                'RUSAK': 'bg-red-100 text-red-800',
                'DALAM PERBAIKAN': 'bg-yellow-100 text-yellow-800'
            };

            if (!statusCode || !badgeStyles[statusCode]) {
                return null;
            }

            const badge = document.createElement('span');
            const baseClasses = 'text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap';
            badge.className = `${baseClasses} ${badgeStyles[statusCode]}`;
            badge.textContent = statusText || statusCode;
            return badge;
        }

        function formatLocationLabel(label) {
            const abbreviated = abbreviateLabel(label);

            const parts = abbreviated.split(' - ');

            if (parts.length <= 2) {
                return {main: abbreviated, sub: ''};
            }

            const mainPartsCount = 3;
            const main = parts.slice(-mainPartsCount).join(' - ');
            const sub = parts.slice(0, -mainPartsCount).join(', ');

            return {main, sub};
        }

        function createOptionItem(loc, index) {
            const li = document.createElement('li');
            const statusCode = loc.statusCode || '';
            const isSelectable = !(statusCode === 'DALAM PERBAIKAN');
            const isDesktop = window.innerWidth >= 1024;
            let liClasses = 'px-4 py-3 transition-colors border-b border-gray-100';
            li.dataset.originalIndex = index;

            if (isSelectable) {
                liClasses += ' hover:bg-blue-50 cursor-pointer';
                li.onclick = () => selectOption(index);
            } else {
                liClasses += ' text-gray-400 cursor-not-allowed opacity-75';
                li.onclick = event => {
                    event.stopPropagation();
                    console.warn(`Klik pada item yang dinonaktifkan: ${loc.label}`);
                };
            }
            li.className = liClasses;

            const textContainer = document.createElement('div');
            textContainer.className = 'flex-grow mr-2 overflow-hidden';
            if (isDesktop) {
                const fullTextSpan = document.createElement('span');
                fullTextSpan.textContent = loc.label;
                fullTextSpan.className = 'text-sm text-gray-800 font-medium overflow-hidden overflow-ellipsis whitespace-nowrap';
                textContainer.appendChild(fullTextSpan);

            } else {

                textContainer.classList.add('flex', 'flex-col');
                const formattedLabel = formatLocationLabel(loc.label);
                const mainTextSpan = document.createElement('span');
                mainTextSpan.textContent = formattedLabel.main;
                mainTextSpan.className = 'text-sm text-gray-800 font-medium overflow-hidden overflow-ellipsis whitespace-nowrap';
                textContainer.appendChild(mainTextSpan);

                if (formattedLabel.sub) {
                    const subTextSpan = document.createElement('span');
                    subTextSpan.textContent = formattedLabel.sub;
                    subTextSpan.className = 'text-xs text-gray-500 mt-1 overflow-hidden overflow-ellipsis whitespace-nowrap';
                    textContainer.appendChild(subTextSpan);
                }
            }

            const contentWrapper = document.createElement('div');
            contentWrapper.className = 'flex justify-between items-center w-full';
            contentWrapper.appendChild(textContainer);

            const badge = createStatusBadge(statusCode, loc.statusText);
            if (badge) {
                contentWrapper.appendChild(badge);
            }

            li.appendChild(contentWrapper);

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
            return !(statusCode === 'DALAM PERBAIKAN');
        }

        function selectOption(index) {
            if (index < 0 || index >= currentOptions.length) return;

            const selected = currentOptions[index];
            const statusCode = selected.statusCode || '';
            const isSelectable = !(statusCode === 'DALAM PERBAIKAN');

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
                konfirmasiKirimModal.classList.remove('pointer-events-none');
                konfirmasiKirimModal.classList.add('opacity-100');

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

            const isMobile = window.innerWidth < 768;

            Toastify({
                text: `<div class="flex items-center gap-3">${icon}<span>${message}</span></div>`,
                duration: 3000,
                gravity: "top",
                position: isMobile ? "center" : "right",
                backgroundColor: background,
                className: "rounded-lg shadow-md",
                stopOnFocus: true,
                escapeMarkup: false,
                style: {
                    padding: "12px 20px",
                    fontWeight: "500",
                    minWidth: isMobile ? "auto" : "300px",
                    textAlign: "left"
                },
                onClick: onClick || function () {
                }
            }).showToast();
        }

        function showLoading(button) {
            if (!button.dataset.originalHtml) {
                button.dataset.originalHtml = button.innerHTML;
            }

            button.disabled = true;
            button.classList.add('cursor-not-allowed', 'opacity-75');
            button.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V4a10 10 0 00-10 10h2zm2 5.291A7.962 7.962 0 014 12H2c0 3.042 1.135 5.824 3 7.938l1-1.647z"></path>
                    </svg>
                    Mengirim...
                </span>
            `;
        }

        function hideLoading(button) {
            if (button.dataset.originalHtml) {
                button.innerHTML = button.dataset.originalHtml;
            }

            button.disabled = false;
            button.classList.remove('cursor-not-allowed', 'opacity-75');
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
