@extends('layouts.main')
@section('judul', 'Statistik Fasilitas')
@section('content')
    <div class="p-6 bg-white min-h-screen">

        <!-- Section Judul -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 w-full">
            <!-- Total Laporan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Total Laporan</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ number_format($total ?? 0) }}</h2>
                <p class="text-sm text-gray-400">{{ $pending }} pending • {{ $selesai }} selesai</p>
            </div> <!-- End Total Laporan -->

            <!-- Laporan Hari Ini -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Hari Ini</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ $laporan_pending_hari_ini }}</h2>
                <p class="text-sm text-gray-400">Laporan masuk</p>
            </div> <!-- End Laporan Hari Ini -->

            <!-- Kepuasan Pengguna Card -->
            <div id="kepuasan-pengguna-card-js-logic"
                 class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between"
                 data-rating="{{ $kepuasan }}">
                <p class="text-gray-500 text-sm mb-2">Kepuasan Pengguna</p>

                <div class="flex items-center gap-2">
                    {{-- Diberi ID agar warnanya bisa diubah oleh JavaScript --}}
                    <h2 id="satisfaction-score" class="text-xl font-semibold">
                        {{ number_format($kepuasan, 2, ',', '.') }}
                    </h2>

                    {{-- Kontainer untuk menampung tiga ikon (hanya satu yang akan tampil) --}}
                    <div id="satisfaction-icon-container">
                        {{-- Ikon Senyum (Happy) --}}
                        <svg id="icon-happy" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden text-green-500"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                        {{-- Ikon Netral (Neutral) - dengan mulut datar --}}
                        <svg id="icon-neutral" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden text-yellow-500"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 14h6M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                        {{-- Ikon Sedih (Sad) --}}
                        <svg id="icon-sad" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden text-red-500"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                    </div>
                </div>

                <div id="kepuasan-stars-container-js-logic" class="flex text-yellow-400 mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current star-item"
                             viewBox="0 0 24 24">
                            <path
                                d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    @endfor
                </div>
            </div> <!-- End Kepuasan Pengguna Card -->

            <!-- Waktu Respon -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Waktu Respon</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ $averageResponseDays }} hari</h2>
                <p class="text-sm text-gray-400">Rata-rata penyelesaian</p>
            </div> <!-- End Waktu Respon -->
        </div> <!-- End Section Judul -->

        <!-- Section Tab -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full">
            <!-- Container Tab -->
            <div class="md:col-span-4">
                <!-- Tab Navigation -->
                <div class="inline-flex w-full bg-gray-100 p-1 rounded-lg justify-between" role="tablist"
                     aria-label="Data Filters">

                    <!-- Analisis Tab -->
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Analisis
                    </button> <!-- End Analisis Tab -->

                    <!-- Frekuensi Tab -->
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Frekuensi
                    </button> <!-- End Frekuensi Tab -->

                    <!-- Kepuasan Tab -->
                    <button type="button" role="tab" aria-selected="true"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Kepuasan
                    </button> <!-- End Kepuasan Tab -->

                    <!-- Perencanaan Tab -->
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Perencanaan
                    </button> <!-- End Perencanaan Tab -->
                </div> <!-- End Tab Navigation -->
            </div> <!-- End Container Tab -->
        </div> <!-- End Section Tab -->

        <!-- Tab Contents -->
        <div id="tab-contents" class="mt-6">
            <div data-tab="Analisis"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.analisis')</div>
            <div data-tab="Frekuensi"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.frekuensi')</div>
            <div data-tab="Kepuasan"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.kepuasan')</div>
            <div data-tab="Perencanaan"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.perencanaan')</div>
        </div> <!-- End Tab Contents -->
    </div>
@endsection
@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            (function TabSystem() {
                const tabsContainer = document.querySelector('[role="tablist"]');
                if (!tabsContainer) {
                    return;
                }

                const tabs = Array.from(tabsContainer.querySelectorAll('[role="tab"]'));
                const tabPanels = document.querySelectorAll('.tab-panel');

                if (tabs.length === 0) {
                    return;
                }

                function updateTabVisuals(targetTab) {
                    tabs.forEach(tab => {
                        const isActive = tab === targetTab;
                        tab.setAttribute('aria-selected', isActive.toString());
                        tab.classList.toggle('bg-white', isActive);
                        tab.classList.toggle('shadow', isActive);
                        tab.classList.toggle('text-gray-800', isActive);
                        tab.classList.toggle('text-gray-500', !isActive);
                        tab.classList.toggle('hover:text-gray-700', !isActive);
                    });
                }

                function showTabContent(tabName) {
                    tabPanels.forEach(panel => {
                        panel.classList.toggle('hidden', panel.getAttribute('data-tab') !== tabName);
                    });
                }

                function activateTab(targetTab) {
                    if (!targetTab) return;
                    const tabName = targetTab.textContent.trim();
                    updateTabVisuals(targetTab);
                    showTabContent(tabName);
                    try {
                        localStorage.setItem('activeTabName', tabName);
                    } catch (e) {
                        console.warn('Sistem Tab: Gagal menyimpan tab aktif ke localStorage.', e);
                    }
                }

                function initializeTabs() {
                    let activeTabToSet = tabs[0]; // Default ke tab pertama
                    try {
                        const savedTabName = localStorage.getItem('activeTabName');
                        if (savedTabName) {
                            const savedActiveTab = tabs.find(tab => tab.textContent.trim() === savedTabName);
                            if (savedActiveTab) {
                                activeTabToSet = savedActiveTab;
                            }
                        }
                    } catch (e) {
                        console.warn('Sistem Tab: Gagal membaca tab aktif dari localStorage.', e);
                    }
                    activateTab(activeTabToSet);
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', (event) => {
                        activateTab(event.currentTarget);
                    });
                });

                initializeTabs();
            })();

            (function StarRatingSystem() {
                const kepuasanCard = document.getElementById('kepuasan-pengguna-card-js-logic');
                if (!kepuasanCard) return;

                const ratingValueString = kepuasanCard.dataset.rating;
                const ratingValue = parseFloat(ratingValueString);
                const starsContainer = kepuasanCard.querySelector('#kepuasan-stars-container-js-logic');
                const scoreEl = kepuasanCard.querySelector('#satisfaction-score');
                const iconContainer = kepuasanCard.querySelector('#satisfaction-icon-container');

                // Cek jika nilai tidak valid (bukan angka)
                if (isNaN(ratingValue)) {
                    console.error('Rating: Nilai tidak valid:', ratingValueString);
                    if (scoreEl) scoreEl.textContent = 'Error';
                    if (starsContainer) starsContainer.innerHTML = '<span class="text-xs text-red-500">Data tidak valid.</span>';
                    if (iconContainer) iconContainer.style.display = 'none';
                    return;
                }

                // [PERUBAHAN UTAMA] Tangani kasus jika belum ada feedback (skor = 0)
                if (ratingValue === 0) {
                    // Ubah skor menjadi 'N/A' dengan warna netral
                    scoreEl.textContent = 'N/A';
                    scoreEl.className = 'text-xl font-semibold text-gray-500';

                    // Sembunyikan ikon wajah
                    iconContainer.style.display = 'none';

                    // Ganti bintang dengan pesan informatif
                    starsContainer.innerHTML = '<span class="text-xs text-gray-400">Belum ada feedback</span>';

                } else {
                    // Jika ada skor (ratingValue > 0), jalankan logika seperti biasa
                    applyStarStyling(ratingValue, kepuasanCard);
                    updateSatisfactionIconAndColor(ratingValue, kepuasanCard);
                }

                /**
                 * Fungsi untuk styling bintang
                 */
                function applyStarStyling(kepuasanScore, containerElement) {
                    const starsContainer = containerElement.querySelector('#kepuasan-stars-container-js-logic');
                    if (!starsContainer) return;

                    const totalStars = 5;
                    const starElements = starsContainer.querySelectorAll('svg.star-item');

                    if (starElements.length !== totalStars) return;

                    const fullStars = Math.floor(kepuasanScore);
                    const hasHalfStar = (kepuasanScore - fullStars) >= 0.5;

                    starElements.forEach((svgElement, index) => {
                        const starNumber = index + 1;
                        svgElement.classList.remove('opacity-50', 'text-gray-300');

                        if (starNumber > fullStars) {
                            if (starNumber === fullStars + 1 && hasHalfStar) {
                                svgElement.classList.add('opacity-50');
                            } else {
                                svgElement.classList.add('text-gray-300');
                            }
                        }
                    });
                }

                /**
                 * Fungsi untuk mengubah ikon dan warna skor
                 */
                function updateSatisfactionIconAndColor(score, container) {
                    const scoreEl = container.querySelector('#satisfaction-score');
                    const iconHappy = container.querySelector('#icon-happy');
                    const iconNeutral = container.querySelector('#icon-neutral');
                    const iconSad = container.querySelector('#icon-sad');

                    if (!scoreEl || !iconHappy || !iconNeutral || !iconSad) return;

                    scoreEl.classList.remove('text-green-500', 'text-yellow-500', 'text-red-500');
                    [iconHappy, iconNeutral, iconSad].forEach(icon => icon.classList.add('hidden'));

                    if (score >= 3.5) {
                        scoreEl.classList.add('text-green-500');
                        iconHappy.classList.remove('hidden');
                    } else if (score >= 2.5) {
                        scoreEl.classList.add('text-yellow-500');
                        iconNeutral.classList.remove('hidden');
                    } else {
                        scoreEl.classList.add('text-red-500');
                        iconSad.classList.remove('hidden');
                    }
                }
            })();
        });
    </script>
@endpush
