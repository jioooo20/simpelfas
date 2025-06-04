@extends('layouts.main')
@section('judul', 'Statistik Fasilitas')
@section('content')
    <div class="p-6 bg-white min-h-screen">
        <!-- Breadcrumb -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 w-full">
            <!-- Total Laporan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Total Laporan</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ number_format($total ?? 0) }}</h2>
                <p class="text-sm text-gray-400">{{ $pending }} pending • {{ $selesai }} selesai</p>
            </div> <!-- End Total Laporan -->

            <div id="kepuasan-pengguna-card-js-logic"
                 class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between"
                 data-rating="{{ $kepuasan }}">
                <p class="text-gray-500 text-sm mb-2">Kepuasan Pengguna</p>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-semibold text-yellow-500">
                        {{ number_format($kepuasan, 2, ',', '.') }}
                    </h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                    </svg>
                </div>

                {{-- Kontainer ini memberikan warna dasar kuning untuk bintang --}}
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
                </div>
            </div>

            <!-- Waktu Respon -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Waktu Respon</p>
                <h2 class="text-xl font-semibold text-gray-800">3.2 hari</h2>
                <p class="text-sm text-gray-400">Rata-rata penyelesaian</p>
            </div>

            <!-- Maintenance -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Maintenance</p>
                <h2 class="text-xl font-semibold text-gray-800">45</h2>
                <p class="text-sm text-gray-400">68% preventif</p>
            </div>
        </div>

        <!-- Filter Tab - Full width like the cards above -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full">
            <div class="md:col-span-4">
                <div class="inline-flex w-full bg-gray-100 p-1 rounded-lg justify-between" role="tablist"
                     aria-label="Data Filters">
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Overview
                    </button>
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Analisis
                    </button>
                    <button type="button" role="tab" aria-selected="true"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Kepuasan
                    </button>
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Frekuensi
                    </button>
                    <button type="button" role="tab" aria-selected="false"
                            class="filter-tab w-full text-center px-4 py-1.5 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 transition-all duration-300 ease-in-out">
                        Perencanaan
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <!-- Konten Berdasarkan Tab -->
        <div id="tab-contents" class="mt-6">
            <div data-tab="Overview"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.overview')</div>
            <div data-tab="Analisis"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.analisis')</div>
            <div data-tab="Kepuasan"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.kepuasan')</div>
            <div data-tab="Frekuensi"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.frekuensi')</div>
            <div data-tab="Perencanaan"
                 class="tab-panel hidden">@include('pages.sarpra.analisis-laporan.tabs.perencanaan')</div>
        </div>

    </div>
@endsection
@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Modul untuk Fungsionalitas Tab
            (function TabSystem() {
                const tabsContainer = document.querySelector('[role="tablist"]');
                if (!tabsContainer) {
                    // console.warn('Sistem Tab: Elemen [role="tablist"] tidak ditemukan. Fungsionalitas tab tidak akan aktif.');
                    return; // Keluar jika kontainer tab utama tidak ada
                }

                const tabs = Array.from(tabsContainer.querySelectorAll('[role="tab"]'));
                const tabPanels = document.querySelectorAll('.tab-panel'); // Asumsi panel ada di luar tabsContainer

                if (tabs.length === 0) {
                    // console.warn('Sistem Tab: Tidak ada tombol tab yang ditemukan.');
                    return;
                }

                function updateTabVisuals(targetTab) {
                    tabs.forEach(tab => {
                        const isActive = tab === targetTab;
                        tab.setAttribute('aria-selected', isActive.toString());
                        tab.classList.toggle('bg-white', isActive);
                        tab.classList.toggle('shadow', isActive); // Gaya untuk tab aktif
                        tab.classList.toggle('text-gray-800', isActive); // Teks lebih gelap untuk aktif
                        tab.classList.toggle('text-gray-500', !isActive); // Teks lebih terang untuk non-aktif
                        tab.classList.toggle('hover:text-gray-700', !isActive); // Hover untuk non-aktif
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
                // console.log('Sistem Tab berhasil diinisialisasi.');

            })(); // Akhir dari IIFE TabSystem

            // Modul untuk Fungsionalitas Rating Bintang
            (function StarRatingSystem() {
                const kepuasanCard = document.getElementById('kepuasan-pengguna-card-js-logic');

                if (!kepuasanCard) {
                    // console.warn('Rating Bintang: Elemen #kepuasan-pengguna-card-js-logic tidak ditemukan.');
                    return;
                }

                const ratingValueString = kepuasanCard.dataset.rating;
                const ratingValue = parseFloat(ratingValueString);
                const starsContainer = kepuasanCard.querySelector('#kepuasan-stars-container-js-logic');
                // Skor numerik bisa diupdate di sini juga jika diinginkan, seperti sebelumnya
                // const scoreDisplayElement = kepuasanCard.querySelector('#kepuasan-score-value');

                if (isNaN(ratingValue)) {
                    console.error('Rating Bintang: Nilai rating tidak valid atau tidak ditemukan:', ratingValueString);
                    if (starsContainer) {
                        starsContainer.innerHTML = '<span class="text-xs text-gray-400">Rating tidak tersedia.</span>';
                    }
                    return;
                }

                // Jika Anda ingin mengupdate skor numerik juga dengan JS:
                // if (scoreDisplayElement) {
                //     scoreDisplayElement.textContent = ratingValue.toLocaleString('id-ID', {
                //         minimumFractionDigits: 2,
                //         maximumFractionDigits: 2
                //     });
                // }

                if (starsContainer) {
                    applyStarStyling(ratingValue, starsContainer);
                    // console.log('Rating Bintang berhasil dirender.');
                }

                function applyStarStyling(kepuasanScore, containerElement) {
                    const totalStars = 5;
                    const fullStars = Math.floor(kepuasanScore);
                    const hasFraction = (kepuasanScore - fullStars) >= 0.01;
                    const starElements = containerElement.querySelectorAll('svg.star-item');

                    if (starElements.length !== totalStars) {
                        // console.warn(`Rating Bintang: Jumlah SVG bintang (${starElements.length}) tidak sesuai harapan (${totalStars}).`);
                        // Mungkin perlu penanganan lebih lanjut jika jumlah bintang tidak sesuai
                    }

                    starElements.forEach((svgElement, index) => {
                        const starNumber = index + 1;
                        svgElement.classList.remove('opacity-50', 'text-gray-300'); // Reset

                        if (starNumber <= fullStars) {
                            // Full star (default, no class needed if parent has text-yellow-400)
                        } else if (starNumber === fullStars + 1 && hasFraction) {
                            svgElement.classList.add('opacity-50');
                        } else {
                            svgElement.classList.add('text-gray-300');
                        }
                    });
                }
            })(); // Akhir dari IIFE StarRatingSystem

        }); // Akhir dari DOMContentLoaded utama
    </script>
@endpush
