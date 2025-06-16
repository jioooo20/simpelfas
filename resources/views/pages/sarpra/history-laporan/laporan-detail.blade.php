@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4" x-data="detailLaporan()" x-init="init()">

        <div class="flex justify-start pb-4">
            <a href="{{ url()->previous() }}" {{-- Kembali ke halaman sebelumnya --}}
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- Tampilkan spinner saat data disimulasikan loading --}}
        <div x-show="loading" class="flex justify-center items-center py-20">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>

        {{-- GANTI SELURUH <div x-show="!loading" x-cloak> DENGAN KODE DI BAWAH INI --}}
        <div x-show="!loading" x-cloak>
            {{-- Layout Grid 2 Kolom untuk layar besar (lg) --}}
            {{-- GANTI SELURUH BLOK GRID LAMA ANDA DENGAN INI --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div>
                    <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 h-full overflow-hidden">
                        <div class="p-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-file-earmark-text"></i>
                                Detail Laporan
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table w-full text-sm">
                                <tbody>
                                <tr class="bg-white border-b">
                                    <th class="th-style">Kode Laporan</th>
                                    <td class="td-style" x-text="laporan.kode"></td>
                                </tr>
                                <tr class="bg-base-200 border-b">
                                    <th class="th-style">Fasilitas</th>
                                    <td class="td-style" x-text="laporan.fasilitas"></td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <th class="th-style">Skala Kerusakan</th>
                                    <td class="td-style" x-text="laporan.skalaKerusakan"></td>
                                </tr>
                                <tr class="bg-base-200 border-b">
                                    <th class="th-style">Frekuensi Penggunaan</th>
                                    <td class="td-style" x-text="laporan.frekuensiPenggunaan"></td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <th class="th-style">Deskripsi Laporan</th>
                                    <td class="td-style text-justify">
                                        <div :class="showFullDescription ? '' : 'line-clamp-3'"
                                             x-text="laporan.deskripsi"></div>
                                        <button @click="showFullDescription = !showFullDescription"
                                                class="text-sm text-blue-500 hover:underline mt-2"
                                                x-show="laporan.deskripsi.length > 150">
                                            <span x-show="!showFullDescription">Lihat Selengkapnya</span>
                                            <span x-show="showFullDescription">Lihat Lebih Sedikit</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-base-200 border-b">
                                    <th class="th-style">Tanggal Laporan</th>
                                    <td class="td-style" x-text="laporan.tanggal"></td>
                                </tr>
                                <tr class="bg-white">
                                    <th class="th-style">Status Laporan</th>
                                    <td class="td-style">
                                <span
                                    class="inline-flex items-center justify-center gap-2 w-32 h-7 px-2 rounded-full text-sm font-medium"
                                    :class="badgeStyle(laporan.status)">
                                    <span x-html="badgeIcon(laporan.status)"></span>
                                    <span x-text="laporan.status"></span>
                                </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div>
                    <template x-if="laporan.perbaikan">
                        <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 h-full overflow-hidden">
                            <div class="p-4 border-b">
                                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="bi bi-tools"></i>
                                    Detail Perbaikan
                                </h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table w-full text-sm">
                                    <tbody>
                                    <tr class="bg-white border-b">
                                        <th class="th-style">Kode Perbaikan</th>
                                        <td class="td-style" x-text="laporan.perbaikan.kode"></td>
                                    </tr>
                                    <tr class="bg-base-200 border-b">
                                        <th class="th-style">Ditugaskan Kepada</th>
                                        <td class="td-style" x-text="laporan.perbaikan.teknisi"></td>
                                    </tr>
                                    <tr class="bg-white border-b">
                                        <th class="th-style">Tanggal Mulai</th>
                                        <td class="td-style" x-text="laporan.perbaikan.tanggalMulai"></td>
                                    </tr>
                                    <tr class="bg-base-200 border-b">
                                        <th class="th-style">Catatan Teknisi</th>
                                        <td class="td-style">
                                            <span
                                                x-text="laporan.perbaikan.catatan"
                                                :class="{ 'text-gray-500 italic': laporan.perbaikan.catatan === 'Tidak ada catatan' }"
                                            ></span>
                                        </td>
                                    </tr>
                                    <tr class="bg-white">
                                        <th class="th-style">Status Perbaikan</th>
                                        <td class="td-style">
                                    <span
                                        class="inline-flex items-center justify-center gap-2 w-32 h-7 px-2 rounded-full text-sm font-medium"
                                        :class="badgeStyle(laporan.perbaikan.status)">
                                        <span x-html="badgeIcon(laporan.perbaikan.status)"></span>
                                        <span x-text="laporan.perbaikan.status"></span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    {{-- Placeholder tidak berubah --}}
                    <template x-if="!laporan.perbaikan">
                        <div
                            class="rounded-xl shadow-md border-2 border-dashed border-gray-300 bg-base-100 h-full flex flex-col">
                            <div class="p-4 border-b">
                                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="bi bi-tools"></i>
                                    Detail Perbaikan
                                </h2>
                            </div>
                            <div
                                class="flex-grow flex flex-col items-center justify-center text-center p-6 text-gray-500">
                                <i class="bi bi-tools text-4xl mb-2"></i>
                                <p class="font-semibold">Belum Ada Data Perbaikan</p>
                                <p class="text-sm mt-1">Informasi perbaikan akan muncul di sini setelah laporan
                                    ditangani.</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="lg:col-span-2 mt-6"> {{-- lg:col-span-2 agar section ini membentang penuh di bawah 2 kolom --}}
                <div
                    class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium text-center text-gray-500 border border-gray-300">
                    <button @click="setActiveTab('Gambar Laporan')"
                            :class="{'active-tab': activeTab === 'Gambar Laporan'}"
                            class="tab-btn w-full px-4 py-2 border-r border-gray-300">
                        Gambar Laporan
                    </button>
                    <template x-if="laporan.status !== 'Ditolak' && laporan.status !== 'Menunggu'">
                        <button @click="setActiveTab('Gambar Perbaikan')"
                                :class="{'active-tab': activeTab === 'Gambar Perbaikan'}"
                                class="tab-btn w-full px-4 py-2 border-r border-gray-300">
                            Gambar Perbaikan
                        </button>
                    </template>
                    <template x-if="laporan.status === 'Selesai'">
                        <button @click="setActiveTab('Gambar Selesai')"
                                :class="{'active-tab': activeTab === 'Gambar Selesai'}"
                                class="tab-btn w-full px-4 py-2">
                            Gambar Selesai
                        </button>
                    </template>
                </div>

                {{-- Kontainer gambar (sekarang menggunakan 'currentImages' lagi) --}}
                <div
                    class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4 border rounded-xl bg-base-100">
                    <template x-for="imageUrl in currentImages" :key="imageUrl">
                        <div
                            class="relative aspect-video w-full flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                            <img :src="imageUrl" alt="Gambar Laporan"
                                 class="object-contain max-w-full max-h-full cursor-pointer"
                                 @click="openModal(imageUrl)"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"/>
                            <div class="absolute inset-0 flex-col items-center justify-center text-gray-400"
                                 style="display: none;">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                <span class="text-sm mt-1">Gambar tidak valid</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="currentImages.length === 0">
                        <div class="col-span-full flex flex-col justify-center items-center text-gray-500 text-sm h-48">
                            <i class="bi bi-camera text-4xl"></i>
                            <span class="mt-2">Tidak ada gambar untuk kategori ini.</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" x-cloak
             @keydown.escape.window="closeModal()"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4">
            <div @click.self="closeModal()" class="absolute inset-0"></div>
            <div class="relative w-full max-w-4xl max-h-full">
                <img :src="zoomedImageUrl" alt="Zoomed Gambar"
                     class="w-full h-auto object-contain max-h-[90vh] rounded-lg">
                <button @click="closeModal()"
                        class="absolute -top-2 -right-2 bg-white text-gray-700 rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                    &times;
                </button>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .tab-btn.active-tab {
            background-color: white;
            color: #1f2937; /* text-gray-800 */
            font-weight: 600;
        }

        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        .th-style {
            @apply text-left align-top font-semibold text-gray-800 p-4 bg-gray-50;
        }

        .td-style {
            @apply text-gray-600 p-4;
        }
    </style>
@endpush

@push('skrip')
    <script>
        function detailLaporan() {
            return {
                loading: true,
                showFullDescription: false,
                activeTab: 'Gambar Laporan',
                isModalOpen: false,
                zoomedImageUrl: '',

                laporan: {},

                init() {
                    this.laporan = @json($detailData);
                    this.loading = false;
                    console.log(this.laporan);
                },

                // [DIKEMBALIKAN] Logika getter kembali ke versi simpel
                get currentImages() {
                    return this.laporan.gambar[this.activeTab] || [];
                },

                // [DIKEMBALIKAN] Method untuk mengganti tab
                setActiveTab(tabName) {
                    this.activeTab = tabName;
                },

                openModal(imageUrl) {
                    this.zoomedImageUrl = imageUrl;
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                },

                // Helper badge tidak berubah
                badgeStyle(status) {
                    return {
                        'Menunggu': 'bg-amber-100 text-amber-800 border border-amber-200',
                        'Diterima': 'bg-sky-100 text-sky-800 border border-sky-200',
                        'Diproses': 'bg-blue-100 text-blue-800 border border-blue-200',
                        'Selesai': 'bg-green-100 text-green-800 border border-green-200',
                        'Ditolak': 'bg-red-100 text-red-800 border border-red-200',
                    }[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
                },
                badgeIcon(status) {
                    return {
                        'Menunggu': '<i class=\'bi bi-hourglass\'></i>',
                        'Diterima': '<i class=\'bi bi-check2-circle\'></i>',
                        'Diproses': '<i class=\'bi bi-gear\'></i>',
                        'Selesai': '<i class=\'bi bi-check-circle\'></i>',
                        'Ditolak': '<i class=\'bi bi-x-circle\'></i>',
                    }[status] || '';
                },
            }
        }
    </script>
@endpush
