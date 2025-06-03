@extends('layouts.main')
@section('judul', 'Statistik Fasilitas')
@section('content')
    <div class="p-6 bg-white min-h-screen">
        <!-- Breadcrumb -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 w-full">
            <!-- Total Laporan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Total Laporan</p>
                <h2 class="text-xl font-semibold text-gray-800">1,247</h2>
                <p class="text-sm text-gray-400">89 pending • 1098 selesai</p>
            </div>

            <!-- Kepuasan Pengguna -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                <p class="text-gray-500 text-sm mb-2">Kepuasan Pengguna</p>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-semibold text-yellow-500">4.8</h2>
                    <!-- Ikon ekspresi berdasarkan skor -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                    </svg>
                </div>
                <!-- Grafik Bintang -->
                <div class="flex text-yellow-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current opacity-50" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
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
            const tabsContainer = document.querySelector('[role="tablist"]');
            if (!tabsContainer) {
                console.warn('Element [role="tablist"] tidak ditemukan.');
                return;
            }

            const tabs = Array.from(tabsContainer.querySelectorAll('[role="tab"]'));
            const tabPanels = document.querySelectorAll('.tab-panel');

            const updateTabStyles = (tab, isActive) => {
                if (isActive) {
                    tab.setAttribute('aria-selected', 'true');
                    tab.classList.add('bg-white', 'shadow', 'text-gray-800');
                    tab.classList.remove('text-gray-500', 'hover:text-gray-700');
                } else {
                    tab.setAttribute('aria-selected', 'false');
                    tab.classList.remove('bg-white', 'shadow', 'text-gray-800');
                    tab.classList.add('text-gray-500', 'hover:text-gray-700');
                }
            };

            const showTabContent = (tabName) => {
                tabPanels.forEach(panel => {
                    if (panel.getAttribute('data-tab') === tabName) {
                        panel.classList.remove('hidden');
                    } else {
                        panel.classList.add('hidden');
                    }
                });
            };

            // Ambil tab aktif dari localStorage jika ada
            const activeTabName = localStorage.getItem('activeTabName');
            let defaultTabName = tabs[0]?.textContent.trim();

            // Inisialisasi tab
            tabs.forEach(tab => {
                const tabName = tab.textContent.trim();
                const isActive = tabName === activeTabName || (!activeTabName && tab === tabs[0]);
                updateTabStyles(tab, isActive);
                if (isActive) {
                    showTabContent(tabName);
                }
            });

            // Event saat tab diklik
            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    const clickedTab = e.currentTarget;
                    const clickedTabName = clickedTab.textContent.trim();

                    tabs.forEach(t => updateTabStyles(t, t === clickedTab));
                    showTabContent(clickedTabName);

                    // Simpan tab aktif ke localStorage
                    localStorage.setItem('activeTabName', clickedTabName);
                });
            });
        });
    </script>
@endpush
