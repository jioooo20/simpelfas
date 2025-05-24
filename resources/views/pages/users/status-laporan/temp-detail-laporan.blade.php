@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">

        <!-- Back Button -->
        <div class="flex justify-start pb-4">
            <a href="{{ route('status-laporan') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div> <!-- End of Back Button -->

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
                        <td class="text-gray-600 px-4 py-5">{{ $laporan->pelaporan_kode }}</td>
                    </tr>

                    <!-- Fasilitas Laporan -->
                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Fasilitas</th>
                        <td class="text-gray-600 px-4 py-5">{{ $laporan->fasilitas_label }}</td>
                    </tr>

                    <!-- Deskripsi Laporan -->
                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Laporan</th>
                        <td class="text-gray-600 text-justify px-4 py-5" x-data="{ expanded: false }">
                            @if(strlen($laporan->pelaporan_deskripsi) > 100)
                                <span x-show="!expanded">
                                        {{ Str::limit($laporan->pelaporan_deskripsi, 100) }}
                                    </span>
                                <span x-show="expanded" x-cloak>
                                            {{ $laporan->pelaporan_deskripsi }}
                                    </span>
                                <br>
                                <button @click="expanded = !expanded"
                                        class="text-sm text-blue-500 hover:underline mt-1">
                                    <span x-show="!expanded" x-cloak="">Lihat Selengkapnya</span>
                                    <span x-show="expanded" x-cloak>Lihat Lebih Sedikit</span>
                                </button>
                            @else
                                {{ $laporan->pelaporan_deskripsi }}
                            @endif
                        </td>
                    </tr> <!-- End of Deskripsi Laporan -->

                    <!-- Tanggal Laporan -->
                    <tr class="bg-base-200 border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Tanggal</th>
                        <td class="text-gray-600 px-4 py-5">{{$laporan->created_at->format('d M Y')}}</td>
                    </tr>

                    <!-- Status -->
                    <tr class="bg-white border-b py-5">
                        <th class="text-left align-top font-semibold text-gray-800 px-4 py-5">Status</th>
                        <td class="text-gray-600 px-4 py-5">
                                 <span
                                     class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <i class="bi bi-hourglass"></i>
                                    <span class="text-center">{{ $status }}</span>
                                </span>
                        </td>
                    </tr> <!-- End Status Laporan -->

                    </tbody> <!-- End of Table Body -->
                </table> <!-- End of Table -->
            </div> <!-- End of Table Container -->
        </div> <!-- End of Modal Header -->

        <!-- Tab Gambar Status -->
        <div class="mt-6">
            <div id="tab-buttons"
                 class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium text-center text-gray-500 border border-gray-300">
                <button
                    data-tab="laporan"
                    class="tab-btn active-tab w-full px-4 py-2 transition-colors duration-300 ease-in-out text-gray-700 bg-white font-semibold border-r border-gray-300 focus:outline-none">
                    Gambar Laporan
                </button>
                <button
                    data-tab="perbaikan"
                    class="tab-btn w-full px-4 py-2 transition-colors duration-300 ease-in-out hover:bg-white border-r border-gray-300">
                    Gambar Perbaikan
                </button>
                <button
                    data-tab="selesai"
                    class="tab-btn w-full px-4 py-2 transition-colors duration-300 ease-in-out hover:bg-white">
                    Gambar Selesai
                </button>
            </div>
        </div> <!-- End of Tab Gambar Status -->

        <div id="image-container" class="mt-6 grid grid-cols-3 gap-4"></div>

    </div> <!-- End of Modal Content -->
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

        // Function to handle image loading
        document.addEventListener('DOMContentLoaded', function () {
            const imageContainer = document.getElementById('image-container');

            // Contoh array gambar (dummy)
            const gambarUrls = []; // Ubah jumlah elemen: [] = 0 gambar, ['a.jpg'] = 1, dst.

            const maxSlots = 3;

            // Jika tidak ada gambar sama sekali
            if (gambarUrls.length === 0) {
                const message = document.createElement('div');
                message.className = "col-span-3 text-center text-gray-500 text-sm mt-4";
                message.innerHTML = `<i class="bi bi-exclamation-circle text-lg mr-1"></i> Tidak ada gambar yang dilampirkan.`;
                imageContainer.appendChild(message);

                // Tambahkan 3 placeholder ikon
                for (let i = 0; i < maxSlots; i++) {
                    const placeholder = document.createElement('div');
                    placeholder.className = "flex flex-col items-center";
                    placeholder.innerHTML = `
                    <div class="col-span-3 flex items-center justify-center text-gray-500 text-sm h-48">
                        <i class="bi bi-exclamation-circle text-lg mr-2"></i>
                        <span>Tidak ada gambar untuk status ini.</span>
                    </div>

                `;
                    imageContainer.appendChild(placeholder);
                }

            } else {
                // Tampilkan gambar atau placeholder jika < 3 gambar
                for (let i = 0; i < maxSlots; i++) {
                    const hasImage = i < gambarUrls.length;
                    const content = hasImage
                        ? `<img src="${gambarUrls[i]}" alt="Gambar ${i + 1}" class="object-cover w-full h-full">`
                        : `<i class="bi bi-image text-4xl text-gray-400"></i>`;

                    const item = document.createElement('div');
                    item.className = "flex flex-col items-center";

                    item.innerHTML = `
                    <div class="relative border rounded-lg overflow-hidden w-full h-48 flex items-center justify-center bg-gray-50">
                        ${content}
                    </div>
                `;

                    imageContainer.appendChild(item);
                }
            }
        });

        // Function to handle tab switching
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-btn');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');
                    });

                    this.classList.add('active-tab', 'text-gray-700', 'bg-white', 'font-semibold');
                });
            });
        });

        // Function to render status badge
        function renderStatusBadge(status) {
            const badgeClasses = {
                'Menunggu': 'bg-amber-100 text-amber-800 border border-amber-200',
                'Diproses': 'bg-blue-100 text-blue-800 border border-blue-200',
                'Selesai': 'bg-green-100 text-green-800 border border-green-200',
                'Ditolak': 'bg-red-100 text-red-800 border border-red-200',
            };

            const badgeIcons = {
                'Menunggu': 'bi-hourglass',
                'Diproses': 'bi-gear',
                'Selesai': 'bi-check-circle',
                'Ditolak': 'bi-x-circle',
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

        document.addEventListener('DOMContentLoaded', function () {
            const badgeContainer = document.getElementById('status-badge');
            if (badgeContainer) {
                const status = badgeContainer.dataset.status;
                badgeContainer.innerHTML = renderStatusBadge(status);
            }
        });
    </script>
@endpush

