@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">

        <div class="flex justify-start pb-4">
            <a href="{{ route('status-laporan') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 text-base-content overflow-hidden">
            <div class="text-sm">
                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-white">
                    <dt class="font-semibold text-gray-800">Kode Laporan</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">{{ $laporan->pelaporan_kode }}</dd>
                </div>

                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-base-200/60">
                    <dt class="font-semibold text-gray-800">Fasilitas</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">{{ $laporan->fasilitas_label }}</dd>
                </div>

                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-white">
                    <dt class="font-semibold text-gray-800">Skala Kerusakan</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">
                        {{ $skalaLabels[$skor['Skala_Kerusakan']] ?? 'Tidak Tersedia' }}
                    </dd>
                </div>

                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-base-200/60">
                    <dt class="font-semibold text-gray-800">Frekuensi Penggunaan</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">
                        {{ $frekuensiLabels[$skor['Frekuensi_Penggunaan']] ?? 'Tidak Tersedia' }}
                    </dd>
                </div>

                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-white">
                    <dt class="font-semibold text-gray-800 align-top">Laporan</dt>
                    <dd class="mt-1 text-gray-600 text-justify break-words md:col-span-3 md:mt-0"
                        x-data="{ expanded: false }">
                        @if(strlen($laporan->pelaporan_deskripsi) > 100)
                            <span x-show="!expanded">
                                    {{ Str::limit($laporan->pelaporan_deskripsi, 100) }}
                                </span>
                            <span x-show="expanded" x-cloak>
                                        {{ $laporan->pelaporan_deskripsi }}
                                </span>
                            <br>
                            <button @click="expanded = !expanded"
                                    class="text-sm text-blue-500 hover:underline mt-2">
                                <span x-show="!expanded" x-cloak="">Lihat Selengkapnya</span>
                                <span x-show="expanded" x-cloak>Lihat Lebih Sedikit</span>
                            </button>
                        @else
                            {{ $laporan->pelaporan_deskripsi }}
                        @endif
                    </dd>
                </div>

                <div class="p-4 border-b md:grid md:grid-cols-4 md:gap-4 bg-base-200/60">
                    <dt class="font-semibold text-gray-800">Tanggal</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">{{$laporan->created_at->format('d M Y')}}</dd>
                </div>

                <div class="p-4 md:grid md:grid-cols-4 md:gap-4 bg-white">
                    <dt class="font-semibold text-gray-800">Status</dt>
                    <dd class="mt-1 text-gray-600 md:col-span-3 md:mt-0">
                         <span
                             class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                 <div id="status-badge" data-status="{{ $status }}"></div>
                         </span>
                    </dd>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <div class="mt-6">
                <div id="tab-buttons"
                     class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium text-center text-gray-500 border border-gray-300">
                    <button
                        class="tab-btn active-tab w-full px-4 py-2 text-gray-700 bg-white font-semibold border-r border-gray-300"
                        data-status="Gambar Laporan">
                        <span class="sm:hidden">Laporan</span>
                        <span class="hidden sm:inline">Gambar Laporan</span>
                    </button>
                    <button class="tab-btn w-full px-4 py-2 hover:bg-white border-r border-gray-300"
                            data-status="Gambar Perbaikan">
                        <span class="sm:hidden">Perbaikan</span>
                        <span class="hidden sm:inline">Gambar Perbaikan</span>
                    </button>
                    <button class="tab-btn w-full px-4 py-2 hover:bg-white"
                            data-status="Gambar Selesai">
                        <span class="sm:hidden">Selesai</span>
                        <span class="hidden sm:inline">Gambar Selesai</span>
                    </button>
                </div>
            </div>
        </div>
        <div id="image-container" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 scroll-mt-20"></div>

        <div id="loading-spinner" class="flex justify-center items-center py-10 text-gray-500 text-sm">
            <i class="bi bi-arrow-repeat animate-spin mr-2 text-lg"></i> Memuat gambar...
        </div>

        <div class="h-20 opacity-0 pointer-events-none"></div>

        <div id="zoom-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-70">
            <div class="relative w-full max-w-6xl px-4">
                <div class="w-full flex justify-center">
                    <div class="relative inline-block">
                        <img id="zoomed-image"
                             src=""
                             alt="Zoomed Gambar"
                             class="w-full max-h-[90vh] object-contain rounded-lg shadow-lg"/>
                        <button id="close-modal"
                                class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-2xl font-bold rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-80 transition">
                            &times;
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('css')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
@endpush

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // -----------------------------
            // DOM Elements
            // -----------------------------
            const tabButtons = document.querySelectorAll('.tab-btn');
            const imageContainer = document.getElementById('image-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const badgeContainer = document.getElementById('status-badge');

            // -----------------------------
            // Data & Caching
            // -----------------------------

            const imageMap = @json($gambar);
            const imageCache = {};

            // -----------------------------
            // Initialization
            // -----------------------------
            initTabs(tabButtons);

            renderIcons('Gambar Laporan');
            if (badgeContainer) renderStatusBadgeOnLoad(badgeContainer);

            // -----------------------------
            // Tab Handling Functions
            // -----------------------------

            function initTabs(buttons) {
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelector('.active-tab')?.classList.remove('active-tab', 'bg-white', 'text-gray-700', 'font-semibold');
                        btn.classList.add('active-tab', 'bg-white', 'text-gray-700', 'font-semibold');
                        const status = btn.dataset.status;
                        renderIcons(status);
                    });
                });
            }

            function setActiveTab(buttons, activeButton) {
                buttons.forEach(btn => {
                    btn.classList.remove('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');
                });
                activeButton.classList.add('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');
            }

            function toggleTabsByStatus(status) {
                const tabButtonsContainer = document.getElementById('tab-buttons');
                if (!tabButtonsContainer) {
                    console.error("Elemen #tab-buttons tidak ditemukan!");
                    return;
                }
                const tabButtons = Array.from(tabButtonsContainer.querySelectorAll('.tab-btn'));

                if (status === 'Ditolak') {
                    let gambarLaporanButton = null;

                    tabButtonsContainer.style.width = `calc(100% / ${tabButtons.length || 3})`;
                    tabButtonsContainer.classList.add('single-tab-display-mode');

                    tabButtons.forEach(btn => {
                        const statusValue = btn.dataset.status;
                        if (statusValue === 'Gambar Laporan') {
                            gambarLaporanButton = btn;
                            btn.classList.remove('hidden');
                            btn.classList.add('w-full');
                            btn.classList.remove('border-r');
                        } else {
                            btn.classList.add('hidden');
                            btn.classList.remove('w-full');
                        }
                    });

                    if (gambarLaporanButton && !gambarLaporanButton.classList.contains('active-tab')) {
                        tabButtons.forEach(b => b.classList.remove('active-tab', 'bg-white', 'text-gray-700', 'font-semibold'));
                        gambarLaporanButton.classList.add('active-tab', 'bg-white', 'text-gray-700', 'font-semibold');
                    }

                } else {
                    tabButtonsContainer.style.width = '';
                    tabButtonsContainer.classList.remove('single-tab-display-mode');

                    tabButtons.forEach((btn, index) => {
                        btn.classList.remove('hidden');
                        btn.classList.add('w-full');

                        if (index < tabButtons.length - 1) {
                            btn.classList.add('border-r');
                        } else {
                            btn.classList.remove('border-r');
                        }
                    });

                    const isActiveTabPresent = tabButtons.some(btn => btn.classList.contains('active-tab'));
                    if (!isActiveTabPresent && tabButtons.length > 0) {
                        tabButtons.forEach(b => b.classList.remove('active-tab', 'bg-white', 'text-gray-700', 'font-semibold'));
                        tabButtons[0].classList.add('active-tab', 'bg-white', 'text-gray-700', 'font-semibold');
                    }
                }
            }

            // -----------------------------
            // Zoom Modal Functions
            // -----------------------------
            window.openZoomModal = function (src) {
                const modal = document.getElementById('zoom-modal');
                const img = document.getElementById('zoomed-image');

                img.src = src;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            document.getElementById('close-modal').addEventListener('click', () => {
                const modal = document.getElementById('zoom-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            document.getElementById('zoom-modal').addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    e.currentTarget.classList.add('hidden');
                    e.currentTarget.classList.remove('flex');
                }
            });

            // -----------------------------
            // Image Rendering Functions
            // -----------------------------
            function renderEmptyState() {
                return `
                    <div class="col-span-3 flex justify-center items-center text-gray-500 text-sm h-48">
                        <i class="bi bi-exclamation-circle text-lg mr-2"></i>
                        <span>Tidak ada gambar untuk status ini.</span>
                    </div>
                `;
            }

            function renderIcons(status) {
                if (imageCache[status]) {
                    imageContainer.innerHTML = imageCache[status];
                    scrollToContainer();
                    return;
                }

                imageContainer.innerHTML = '';
                loadingSpinner.style.display = 'flex';

                setTimeout(() => {
                    const images = imageMap[status] || [];
                    imageContainer.innerHTML = images.length === 0
                        ? renderEmptyState()
                        : renderImageItems(images);

                    imageCache[status] = imageContainer.innerHTML;
                    loadingSpinner.style.display = 'none';
                    scrollToContainer();
                }, 500);
            }

            function renderImageItems(images) {
                return images.map(path => {
                    const finalPath = path.startsWith('storage/dummy')
                        ? `/${path}` // langsung pakai path dummy
                        : `/storage/${path}`; // path biasa

                    return `
                        <div class="flex flex-col items-center p-2">
                            <div class="relative aspect-video w-full flex items-center justify-center bg-gray-50 rounded-lg overflow-hidden px-4">
                                <!-- Gambar utama -->
                                <img src="${finalPath}" alt="Gambar"
                                     class="object-contain max-w-full max-h-full bg-white cursor-pointer"
                                     onclick="openZoomModal('${finalPath}')"
                                     onerror="handleImageError(this)"
                                />
                                <!-- Placeholder ikon jika gambar rusak -->
                                <div class="absolute flex flex-col items-center justify-center inset-0 text-gray-400 hidden" data-placeholder>
                                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                                    <span class="text-sm mt-1">Gambar tidak ditemukan</span>
                                </div>
                                <!-- Kotak outline -->
                                <div class="absolute inset-0 border border-gray-300 rounded-lg pointer-events-none"></div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function scrollToContainer() {
                setTimeout(() => {
                    imageContainer.scrollIntoView({behavior: 'smooth', block: 'start'});
                }, 0);
            }

            window.handleImageError = function (img) {
                img.style.display = 'none';
                const placeholder = img.parentElement.querySelector('[data-placeholder]');
                if (placeholder) {
                    placeholder.classList.remove('hidden');
                }
            }

            // -----------------------------
            // Badge Rendering Functions
            // -----------------------------
            function renderStatusBadgeOnLoad(container) {
                const status = container.dataset.status;
                container.innerHTML = renderStatusBadge(status);

                toggleTabsByStatus(status);
            }

            function renderStatusBadge(status) {
                const badgeClasses = {
                    'Menunggu': 'bg-amber-100 text-amber-800 border border-amber-200',
                    'Diproses': 'bg-blue-100 text-blue-800 border border-blue-200',
                    'Selesai': 'bg-green-100 text-green-800 border border-green-200',
                    'Ditolak': 'bg-red-100 text-red-800 border border-red-200',
                    'Diterima': 'bg-emerald-100 text-emerald-800 border border-emerald-200', // Tambahan
                };

                const badgeIcons = {
                    'Menunggu': 'bi-hourglass',
                    'Diproses': 'bi-gear',
                    'Selesai': 'bi-check-circle',
                    'Ditolak': 'bi-x-circle',
                    'Diterima': 'bi-check2-circle', // Tambahan
                };

                const badgeClass = badgeClasses[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
                const badgeIcon = badgeIcons[status] || 'bi-question-circle';

                return `
                    <span class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium ${badgeClass}">
                        <i class="bi ${badgeIcon}"></i>
                        <span class="text-center">${status}</span>
                    </span>
                `;
            }
        });
    </script>
@endpush
