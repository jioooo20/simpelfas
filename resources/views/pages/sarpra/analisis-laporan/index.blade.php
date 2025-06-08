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
                <!-- Skor Kepuasan Pengguna -->
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-semibold text-yellow-500">
                        {{ number_format($kepuasan, 2, ',', '.') }}
                    </h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                    </svg>
                </div> <!-- End Skor Kepuasan Pengguna -->

                <!-- Bintang Kepuasan Pengguna -->
                <div id="kepuasan-stars-container-js-logic" class="flex text-yellow-400 mt-1">
                    {{-- Blade akan merender 5 SVG bintang di sini TANPA logika kelas dinamis --}}
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 fill-current star-item" {{-- Tambahkan kelas 'star-item' --}}
                             viewBox="0 0 24 24">
                            <path
                                d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    @endfor
                </div> <!-- End Bintang Kepuasan Pengguna -->
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

                if (!kepuasanCard) {
                    return;
                }

                const ratingValueString = kepuasanCard.dataset.rating;
                const ratingValue = parseFloat(ratingValueString);
                const starsContainer = kepuasanCard.querySelector('#kepuasan-stars-container-js-logic');

                if (isNaN(ratingValue)) {
                    console.error('Rating Bintang: Nilai rating tidak valid atau tidak ditemukan:', ratingValueString);
                    if (starsContainer) {
                        starsContainer.innerHTML = '<span class="text-xs text-gray-400">Rating tidak tersedia.</span>';
                    }
                    return;
                }

                if (starsContainer) {
                    applyStarStyling(ratingValue, starsContainer);
                }

                function applyStarStyling(kepuasanScore, containerElement) {
                    const totalStars = 5;
                    const fullStars = Math.floor(kepuasanScore);
                    const hasFraction = (kepuasanScore - fullStars) >= 0.01;
                    const starElements = containerElement.querySelectorAll('svg.star-item');

                    if (starElements.length !== totalStars) {
                        console.error('Rating Bintang: Jumlah elemen bintang tidak sesuai:', starElements.length);
                        return;
                    }

                    starElements.forEach((svgElement, index) => {
                        const starNumber = index + 1;
                        svgElement.classList.remove('opacity-50', 'text-gray-300');

                        if (starNumber <= fullStars) {
                        } else if (starNumber === fullStars + 1 && hasFraction) {
                            svgElement.classList.add('opacity-50');
                        } else {
                            svgElement.classList.add('text-gray-300');
                        }
                    });
                }
            })();
        });
    </script>
@endpush
