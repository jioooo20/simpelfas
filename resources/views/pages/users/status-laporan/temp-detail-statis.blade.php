@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">

        <!-- Back Button -->
        <div class="flex justify-start pb-4">
            <a href="#"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Detail Table -->
        <div class="overflow-x-auto">
            <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 text-base-content">
                <table class="table w-full text-sm table-fixed">
                    <colgroup>
                        <col class="w-1/4">
                        <col class="w-3/4">
                    </colgroup>
                    <tbody>

                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Kode Laporan</th>
                        <td class="text-gray-600 px-4 py-5">LP-20250524-001</td>
                    </tr>

                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Fasilitas</th>
                        <td class="text-gray-600 px-4 py-5">Toilet Umum Gedung B</td>
                    </tr>

                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Laporan</th>
                        <td class="text-gray-600 text-justify px-4 py-5" x-data="{ expanded: false }">
                            <span x-show="!expanded">
                                Pintu toilet rusak dan tidak bisa dikunci, menyebabkan ketidaknyamanan...
                            </span>
                            <span x-show="expanded" x-cloak>
                                Pintu toilet rusak dan tidak bisa dikunci, menyebabkan ketidaknyamanan bagi pengguna. Sudah berlangsung selama lebih dari 3 hari dan belum ada perbaikan dilakukan.
                            </span>
                            <br>
                            <button @click="expanded = !expanded" class="text-sm text-blue-500 hover:underline mt-1">
                                <span x-show="!expanded" x-cloak="">Lihat Selengkapnya</span>
                                <span x-show="expanded" x-cloak>Lihat Lebih Sedikit</span>
                            </button>
                        </td>
                    </tr>

                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Tanggal</th>
                        <td class="text-gray-600 px-4 py-5">24 Mei 2025</td>
                    </tr>

                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Status</th>
                        <td class="text-gray-600 px-4 py-5">
                            <span
                                class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="bi bi-gear"></i>
                                <span class="text-center">Diproses</span>
                            </span>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Gambar Status -->
        <div class="mt-6">
            <div id="tab-buttons"
                 class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium text-center text-gray-500 border border-gray-300">
                <button
                    class="tab-btn active-tab w-full px-4 py-2 text-gray-700 bg-white font-semibold border-r border-gray-300">
                    Gambar Laporan
                </button>
                <button class="tab-btn w-full px-4 py-2 hover:bg-white border-r border-gray-300">Gambar Perbaikan
                </button>
                <button class="tab-btn w-full px-4 py-2 hover:bg-white">Gambar Selesai</button>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loading-spinner" class="flex justify-center items-center py-10 text-gray-500 text-sm">
            <i class="bi bi-arrow-repeat animate-spin mr-2 text-lg"></i> Memuat gambar...
        </div>

        <!-- Gambar Container -->
        <div id="image-container" class="mt-6 grid grid-cols-3 gap-4 scroll-mt-20"></div>

        <!-- Footer transparent -->
        <div class="h-20 opacity-0 pointer-events-none"></div>

    </div> <!-- End of Modal Body -->
@endsection

@push('css')
    <style>[x-cloak] {
            display: none;
        }</style>
@endpush

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const imageContainer = document.getElementById('image-container');
            const loadingSpinner = document.getElementById('loading-spinner');

            const imageData = {
                'Gambar Laporan': 3,
                'Gambar Perbaikan': 1,
                'Gambar Selesai': 0
            };

            // Cache untuk menyimpan konten yang sudah dirender
            const imageCache = {};

            function renderIcons(status) {
                // Jika ada di cache, langsung tampilkan tanpa loading
                if (imageCache[status]) {
                    imageContainer.innerHTML = imageCache[status];
                    setTimeout(() => {
                        imageContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 0);

                    return;
                }

                imageContainer.innerHTML = '';
                loadingSpinner.style.display = 'flex';

                // Simulasi loading
                setTimeout(() => {
                    const count = imageData[status];
                    imageContainer.innerHTML = '';

                    if (count === 0) {
                        imageContainer.innerHTML = `
                    <div class="col-span-3 flex justify-center items-center text-gray-500 text-sm h-48">
                        <i class="bi bi-exclamation-circle text-lg mr-2"></i>
                        <span>Tidak ada gambar untuk status ini.</span>
                    </div>`;
                    } else {
                        for (let i = 0; i < count; i++) {
                            const div = document.createElement('div');
                            div.className = 'flex flex-col items-center';
                            div.innerHTML = `
                        <div class="relative border rounded-lg overflow-hidden w-full h-48 flex items-center justify-center bg-gray-50">
                            <i class="bi bi-image text-4xl text-gray-400"></i>
                        </div>`;
                            imageContainer.appendChild(div);
                        }
                    }

                    // Simpan konten ke cache
                    imageCache[status] = imageContainer.innerHTML;

                    loadingSpinner.style.display = 'none';

                    // Scroll ke gambar setelah render selesai
                    imageContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 500);
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');
                    });
                    this.classList.add('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');

                    const status = this.innerText.trim();
                    renderIcons(status);
                });
            });

            // Render default tab on load
            renderIcons('Gambar Laporan');
        });
    </script>
@endpush


{{--
@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">
        <div class="flex justify-start pb-4">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Modal Header -->
        <div class="overflow-x-auto">

            <!-- Table Container -->
            <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 text-base-content">
                <table class="table w-full text-sm table-fixed">
                    <colgroup>
                        <col class="w-1/4">
                        <col class="w-3/4">
                    </colgroup>
                    <tbody>

                    <!-- Kode Laporan -->
                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Kode Laporan</th>
                        <td class="text-gray-600 px-4 py-5">LP20250523</td>
                    </tr>

                    <!-- Fasilitas Laporan -->
                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Fasilitas</th>
                        <td class="text-gray-600 px-4 py-5">Gedung TI dan Sipil - Lantai 5 Barat - Ruang Teori 01 - Meja kotak mahal</td>
                    </tr>

                    <!-- Deskripsi Laporan -->
                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Laporan</th>
                        <td class="text-gray-600 text-justify px-4 py-5" x-data="{ expanded: false }">
                            <span x-show="!expanded">
                                {{ Str::limit('Lampu jalan mati di depan gedung rektorat. Kondisi ini sudah berlangsung selama 3 hari dan menyebabkan area tersebut sangat gelap pada malam hari. Beberapa mahasiswa telah melaporkan kesulitan berjalan di area tersebut karena kurangnya penerangan. Dibutuhkan penggantian lampu segera untuk menghindari potensi kecelakaan atau masalah keamanan lainnya.', 100) }}
                            </span>
                            <span x-show="expanded" x-cloak>
                                Lampu jalan mati di depan gedung rektorat. Kondisi ini sudah berlangsung selama 3 hari dan
                                menyebabkan area tersebut sangat gelap pada malam hari. Beberapa mahasiswa telah melaporkan
                                kesulitan berjalan di area tersebut karena kurangnya penerangan. Dibutuhkan penggantian lampu
                                segera untuk menghindari potensi kecelakaan atau masalah keamanan lainnya.
                            </span>
                            <br>
                            <button @click="expanded = !expanded" class="text-sm text-blue-500 hover:underline mt-1">
                                <span x-show="!expanded" x-cloak="">Lihat Selengkapnya</span>
                                <span x-show="expanded" x-cloak>Lihat Lebih Sedikit</span>
                            </button>
                        </td>
                    </tr>

                    <!-- Tanggal Laporan -->
                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Tanggal</th>
                        <td class="text-gray-600 px-4 py-5">23 Mei 2025</td>
                    </tr>

                    <!-- Status -->
                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Status</th>
                        <td class="text-gray-600 px-4 py-5">
                             <span
                                 class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                <i class="bi bi-hourglass"></i>
                                <span class="text-center">Menunggu</span>
                            </span>
                        </td>
                    </tr>
                    <!-- End Status Laporan -->
                    </tbody> <!-- End of Table Body -->
                </table> <!-- End of Table -->
            </div> <!-- End of Table Container -->
        </div> <!-- End of Modal Header -->

        <!-- Tab Gambar Status -->
        <div class="mt-6">
            <div class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium text-center text-gray-500">
                <button
                    class="w-full px-4 py-2 text-gray-900 bg-white font-semibold border-r border-gray-200 focus:outline-none">
                    Gambar (Menunggu)
                </button>
                <button class="w-full px-4 py-2 hover:bg-white border-r border-gray-200">
                    Gambar (Diperbaiki)
                </button>
                <button class="w-full px-4 py-2 hover:bg-white">
                    Gambar (Selesai)
                </button>
            </div>
        </div>

        <!-- Gambar Dibagi Kiri - Tengah - Kanan -->
        <div class="mt-6 grid grid-cols-3 gap-4">

            <!-- Kiri -->
            <div class="flex flex-col items-center">
                <div class="relative border rounded-lg overflow-hidden w-full h-48 flex items-center justify-center bg-gray-50">
                    <i class="bi bi-image text-4xl text-gray-400"></i>
                </div>
            </div>

            <!-- Tengah -->
            <div class="flex flex-col items-center">
                <div class="relative border rounded-lg overflow-hidden w-full h-48 flex items-center justify-center bg-gray-50">
                    <i class="bi bi-image text-4xl text-gray-400"></i>
                </div>
            </div>

            <!-- Kanan -->
            <div class="flex flex-col items-center">
                <div class="relative border rounded-lg overflow-hidden w-full h-48 flex items-center justify-center bg-gray-50">
                    <i class="bi bi-image text-4xl text-gray-400"></i>
                </div>
            </div>

        </div>


    </div> <!-- End of Modal Content -->
@endsection
@push('css')
    <style>
        [x-cloak] { display: none; }
    </style>
@endpush
{{--
<tr class="bg-base-200">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                            Gambar Laporan
                        </th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div
                                        class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm"
                                        style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-white">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                            Gambar Proses Perbaikan
                        </th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div
                                        class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm"
                                        style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-base-200">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                            Gambar Perbaikan Selesai
                        </th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div
                                        class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm"
                                        style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
--}}
