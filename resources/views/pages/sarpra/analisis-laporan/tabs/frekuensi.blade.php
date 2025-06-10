<!-- Main Content -->
<div class="flex flex-col lg:flex-row gap-6">

    <!-- Interval Perbaikan per Fasilitas -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Peta Risiko Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Analisis jumlah laporan vs. interval kerusakan</p>
        <!-- Chart Container -->
        <div class="h-80 md:h-[350px]">
            <canvas id="intervalChart"></canvas>
        </div> <!-- End Chart Container -->
    </div> <!-- End Interval Perbaikan per Fasilitas -->

    <!-- Fasilitas Berisiko Card -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Fasilitas Berisiko Tinggi</h4>
        <p class="text-xs text-gray-500 mb-6">Fasilitas dengan interval perbaikan pendek</p>
        <div class="space-y-3 overflow-y-auto max-h-72 pr-2">

            @forelse ($fasilitasBerisiko as $fasilitas)
                {{-- Di dalam loop ini, $fasilitas sekarang dianggap sebagai array --}}

                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex justify-between items-center">
                    <div class="flex-1 min-w-0 pr-4">
                        {{-- [DIUBAH] Judul dan kode barang kini dibungkus dalam flex container --}}
                        <div class="flex items-center">
                            <h5 class="font-semibold text-sm text-gray-800 truncate"
                                title="{{ $fasilitas['item_name'] }}">
                                {{ $fasilitas['item_name'] }}
                            </h5>

                            {{-- Tag untuk Kode Barang (hanya tampil jika ada dan bukan 'N/A') --}}
                            @if (!empty($fasilitas['item_code']) && $fasilitas['item_code'] !== 'N/A')
                                <span class="ml-2 flex-shrink-0 px-1.5 py-0.5 bg-white text-red-700 border border-red-200 rounded text-xs font-mono">
                                    {{ $fasilitas['item_code'] }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 truncate mt-1"
                           title="{{ $fasilitas['building'] }}, {{ $fasilitas['room'] }}, {{ $fasilitas['floor'] }}">
                            {{ $fasilitas['building'] }} &bull;
                            {{ $fasilitas['room'] }} &bull; {{ $fasilitas['floor'] }}
                        </p>

                        <p class="text-xs text-red-700 font-semibold mt-2">
                            {{ $fasilitas['jumlah_laporan'] }} laporan
                            &mdash; {{ $fasilitas['interval_rata_rata_hari'] }}
                            hari interval
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <button
                            class="inline-flex items-center bg-red-100 hover:bg-red-200 transition-colors text-red-600 text-xs font-semibold px-3 py-1.5 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span>Perlu Perhatian</span>
                        </button>
                    </div>
                </div>
            @empty
                <!-- Pesan jika tidak ada fasilitas berisiko -->
                <div class="rounded-lg border border-green-200 bg-green-50 p-5">
                    <!-- Ikon dan teks Card -->
                    <div class="flex items-start space-x-4">
                        <!-- Ikon -->
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div> <!-- End Ikon -->

                        <!-- Teks -->
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-green-800">
                                Tidak Ada Isu Ditemukan
                            </h4>
                            <p class="mt-1 text-sm text-green-700">
                                Semua fasilitas dalam kondisi terpantau dan tidak ada yang diklasifikasikan sebagai
                                berisiko tinggi.
                            </p>
                        </div> <!-- End Teks -->
                    </div> <!-- End Ikon dan teks -->
                </div> <!-- End Tidak Ada Isu Ditemukan Card -->
            @endforelse
        </div>
    </div> <!-- End Fasilitas Berisiko Card -->
</div> <!-- End Main Content -->

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fasilitasBerisikoData = @json($fasilitasBerisiko);
            const chartElement = document.getElementById('intervalChart');

            // Periksa jika elemen chart ada di halaman
            if (chartElement) {

                // [PERUBAHAN UTAMA] Pengecekan data dan penanganan jika kosong
                if (fasilitasBerisikoData && fasilitasBerisikoData.length > 0) {

                    // --- Jika ADA DATA, gambar chart seperti biasa ---

                    // Persiapan Data
                    const scatterData = fasilitasBerisikoData.map(item => ({
                        x: item.jumlah_laporan,
                        y: item.interval_rata_rata_hari,
                        label: item.item_name
                    }));
                    const getPointColors = (data) => {
                        return data.map(item => {
                            if (item.y <= 7 && item.x > 5) return 'rgba(220, 38, 38, 0.7)';
                            if (item.y <= 10) return 'rgba(239, 68, 68, 0.7)';
                            if (item.y <= 20) return 'rgba(249, 115, 22, 0.7)';
                            return 'rgba(234, 179, 8, 0.7)';
                        });
                    };
                    const pointBackgroundColors = getPointColors(scatterData);

                    // Inisialisasi Chart
                    const ctxInterval = chartElement.getContext('2d');
                    const intervalChart = new Chart(ctxInterval, {
                        type: 'scatter',
                        data: {
                            datasets: [{
                                label: 'Fasilitas Berisiko',
                                data: scatterData,
                                backgroundColor: pointBackgroundColors,
                                pointRadius: 8,
                                pointHoverRadius: 10
                            }]
                        },
                        options: {
                            // ... (semua opsi chart dari jawaban sebelumnya tetap sama)
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Laporan (Semakin Kanan, Semakin Sering)',
                                        font: {size: 11},
                                        color: '#6b7280'
                                    },
                                    ticks: {
                                        color: '#6b7280', callback: function (value) {
                                            if (Math.floor(value) === value) return value;
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Interval Kerusakan (Hari) - Semakin Rendah, Semakin Cepat Rusak',
                                        font: {size: 11},
                                        color: '#6b7280'
                                    },
                                    ticks: {
                                        color: '#6b7280', callback: function (value) {
                                            if (Math.floor(value) === value) return value;
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {display: false},
                                tooltip: {
                                    callbacks: {
                                        title: function (context) {
                                            return context[0].raw.label;
                                        },
                                        label: function (context) {
                                            return `Jumlah: ${context.raw.x} laporan, Interval: ${context.raw.y} hari`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                } else {
                    // --- Jika TIDAK ADA DATA, tampilkan pesan positif ---
                    const chartContainer = chartElement.parentElement;
                    chartContainer.classList.add('flex', 'items-center', 'justify-center');
                    chartContainer.innerHTML = `
                        <div class="text-center text-gray-500 px-4">
                            <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <h4 class="mt-2 text-sm font-semibold text-gray-700">Kondisi Aman</h4>
                            <p class="mt-1 text-xs">Tidak ada fasilitas berisiko tinggi yang terdeteksi.</p>
                        </div>
                    `;
                }
            }
        });
    </script>
@endpush
