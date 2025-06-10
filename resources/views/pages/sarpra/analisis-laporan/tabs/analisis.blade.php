@php
    // Logika ini tetap sama
    $reportTrendData = $reportTrendData ?? [];
    $availableYears = !empty($reportTrendData) ? array_keys($reportTrendData) : [date('Y')];
    $latestYear = $availableYears[0] ?? date('Y');
@endphp

    <!-- main content -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Tren Laporan & Penyelesaian -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <!-- Header dan Filter -->
        <div class="flex justify-between items-center mb-2">
            <!-- Judul dan Info -->
            <div class="flex items-center space-x-2">
                <h4 class="text-lg font-bold text-gray-800">Tren Laporan & Penyelesaian</h4>

                <!-- Info Icon dan Popover -->
                <div class="relative" id="legend-container">
                    <button id="legend-trigger"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Popover Legend -->
                    <div id="legend-popover"
                         class="hidden absolute top-full mt-2 -left-2 z-10 w-48 bg-white border border-gray-200 rounded-lg shadow-xl p-3">
                        <p class="text-xs font-bold text-gray-700 mb-2">Legenda</p>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li class="flex items-center cursor-pointer" data-dataset-index="0">
                                <span class="w-3 h-3 rounded-sm inline-block mr-2"
                                      style="background-color: rgba(56, 168, 157, 1);"></span>
                                Laporan Masuk
                            </li>
                            <li class="flex items-center cursor-pointer" data-dataset-index="1">
                                <span class="w-3 h-3 rounded-sm inline-block mr-2"
                                      style="background-color: rgba(255, 127, 80, 1);"></span>
                                Laporan Selesai
                            </li>
                        </ul>
                    </div> <!-- End of Popover Legend -->
                </div> <!-- End of Info Icon dan Popover -->
            </div> <!-- End of Judul dan Info -->

            <!-- Filter Tahun -->
            <div class="relative">
                <select id="analisisYearFilter"
                        class="appearance-none w-24 bg-white border border-gray-300 text-gray-700 py-2 pl-3 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @forelse ($availableYears as $year)
                        <option value="{{ $year }}" @if ($year == $latestYear) selected @endif>{{ $year }}</option>
                    @empty
                        <option>N/A</option>
                    @endforelse
                </select>
            </div> <!-- End of Filter Tahun -->
        </div> <!-- End of Header dan Filter -->

        <p class="text-xs text-gray-500 -mt-2 mb-6">Perkembangan laporan masuk vs laporan selesai</p>

        <!-- Chart -->
        <div class="h-80 md:h-[350px]">
            <canvas id="reportTrendChart"></canvas>
        </div> <!-- End of Chart -->
    </div> <!-- End of Tren Laporan & Penyelesaian -->

    <!-- Performa Fasilitas -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Performa Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Skor gabungan per item berdasarkan laporan, kepuasan, dan interval</p>

        <!-- List Performa -->
        <div class="space-y-3 h-80 md:h-[350px] overflow-y-auto pr-2 custom-scrollbar">
            @forelse ($facilitiesPerformance as $facility)
                <div
                    class="bg-white border border-gray-200 p-4 rounded-lg flex justify-between items-start hover:shadow-sm transition-shadow duration-150">
                    <div class="flex-grow">
                        {{-- [PERUBAHAN] Judul dan kode barang kini dibungkus untuk dijadikan tag --}}
                        <div class="flex items-center mb-2">
                            <h5 class="font-semibold text-sm text-gray-900 truncate shrink"
                                title="{{ $facility['title'] }}">
                                {{ $facility['title'] }}
                            </h5>
                            {{-- Tag untuk Kode Barang --}}
                            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-mono">
                        {{ $facility['item_code'] ?? 'N/A' }}
                    </span>
                        </div>
                        {{-- Lokasi kini berada di bawah wrapper judul/kode --}}
                        <p class="text-xs text-gray-500">{{ $facility['subtitle'] }}</p>

                        {{-- Bar Statistik --}}
                        <div class="text-xs pt-3 mt-3 border-t">
                            <div class="text-gray-600 bg-gray-50 p-2 rounded-md">
                                <span>{{ $facility['reports'] }} laporan</span>
                                <span class="mx-1.5">&bull;</span>
                                <span>{{ $facility['satisfaction'] }}/5 kepuasan</span>
                                <span class="mx-1.5">&bull;</span>
                                <span>{{ $facility['interval'] }} hari interval</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pl-4">
                        <span
                            class="{{ $statusColors[$facility['status_color']]['bg'] }} {{ $statusColors[$facility['status_color']]['text'] }} text-xs font-semibold px-2 py-0.5 rounded-md w-20 inline-block mb-2">
                            {{ $facility['status'] }}
                        </span>
                        <p class="text-2xl font-bold text-gray-800 mt-2">{{ $facility['score'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Skor</p>
                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada data performa --}}
                <div class="flex items-center justify-center h-full text-center text-gray-500">
                    <div class="p-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1.5-1.5m1.5 1.5l1.5-1.5m0 0l-1.5-1.5m1.5 1.5l1.5 1.5m-16.5-3.375h16.5"/>
                        </svg>
                        <h4 class="mt-2 text-sm font-semibold text-gray-700">Data Performa Belum Tersedia</h4>
                        <p class="mt-1 text-xs">Data akan muncul di sini setelah ada cukup laporan dan feedback untuk
                            dianalisis.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div> <!-- End of Performa Fasilitas -->
</div> <!-- End of main content -->

@push('css')
    <style>
        /* Kustomisasi scrollbar jika diperlukan, contoh sederhana */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a5a5a5;
        }
    </style>
@endpush

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allTrendsData = @json($reportTrendData);
            const analisisYearFilter = document.getElementById('analisisYearFilter');
            const chartElement = document.getElementById('reportTrendChart');

            // Cek jika elemen-elemen penting ada di halaman
            if (!analisisYearFilter || !chartElement) {
                return;
            }

            const legendContainer = document.getElementById('legend-container');
            const legendTrigger = document.getElementById('legend-trigger');
            const legendPopover = document.getElementById('legend-popover');

            if (!allTrendsData || Object.keys(allTrendsData).length === 0) {
                const chartContainer = chartElement.parentElement;
                chartContainer.classList.add('flex', 'items-center', 'justify-center');
                chartContainer.innerHTML = `
                    <div class="text-center text-gray-500 px-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1.5-1.5m1.5 1.5l1.5-1.5m0 0l-1.5-1.5m1.5 1.5l1.5 1.5m-16.5-3.375h16.5" />
                        </svg>
                        <h4 class="mt-2 text-sm font-semibold text-gray-700">Data Tren Belum Cukup</h4>
                        <p class="mt-1 text-xs">Grafik akan muncul di sini setelah ada riwayat laporan yang cukup untuk dianalisis.</p>
                    </div>
                `;
                return;
            }

            const ctxReportTrend = chartElement.getContext('2d');
            const reportLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            legendTrigger.addEventListener('click', (event) => {
                event.stopPropagation();
                legendPopover.classList.toggle('hidden');
            });

            window.addEventListener('click', (e) => {
                if (legendContainer && !legendContainer.contains(e.target)) {
                    legendPopover.classList.add('hidden');
                }
            });

            const getSuggestedMaxY = (data) => {
                const maxDataPoint = Math.max(...(data.laporanMasuk || []), ...(data.laporanSelesai || []));
                if (maxDataPoint === 0) return 5;
                if (maxDataPoint < 20) return maxDataPoint + 1;
                return Math.ceil((maxDataPoint * 1.1) / 10) * 10;
            };

            const initialYear = analisisYearFilter.value;
            const initialData = allTrendsData[initialYear] || {laporanMasuk: [], laporanSelesai: []};

            const reportTrendChart = new Chart(ctxReportTrend, {
                type: 'line',
                data: {
                    labels: reportLabels,
                    datasets: [
                        {
                            label: 'Laporan Masuk',
                            data: initialData.laporanMasuk,
                            borderColor: 'rgba(56, 168, 157, 1)',
                            backgroundColor: 'rgba(56, 168, 157, 0.1)',
                            fill: false,
                            tension: 0.4,
                            borderWidth: 2.5
                        },
                        {
                            label: 'Laporan Selesai',
                            data: initialData.laporanSelesai,
                            borderColor: 'rgba(255, 127, 80, 1)',
                            backgroundColor: 'rgba(255, 127, 80, 0.1)',
                            fill: false,
                            tension: 0.4,
                            borderWidth: 2.5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: getSuggestedMaxY(initialData),
                            grid: {color: '#e5e7eb'},
                            ticks: {color: '#6b7280'}
                        },
                        x: {
                            grid: {display: false},
                            ticks: {color: '#6b7280'}
                        }
                    },
                    plugins: {
                        legend: {display: false},
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 10,
                            cornerRadius: 6,
                            boxPadding: 4,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });

            legendPopover.addEventListener('click', (e) => {
                const target = e.target.closest('li');
                if (!target) return;
                const datasetIndex = parseInt(target.dataset.datasetIndex);
                reportTrendChart.toggleDataVisibility(datasetIndex);
                target.classList.toggle('opacity-50');
                target.classList.toggle('line-through');
                reportTrendChart.update();
            });

            analisisYearFilter.addEventListener('change', function () {
                const selectedYear = this.value;
                const newData = allTrendsData[selectedYear] || {laporanMasuk: [], laporanSelesai: []};
                reportTrendChart.data.datasets[0].data = newData.laporanMasuk;
                reportTrendChart.data.datasets[1].data = newData.laporanSelesai;
                reportTrendChart.options.scales.y.max = getSuggestedMaxY(newData);
                reportTrendChart.update();
            });
        });
    </script>
@endpush
