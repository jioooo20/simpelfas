<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Tren Laporan & Penyelesaian</h4>
        <p class="text-xs text-gray-500 mb-6">Perkembangan laporan masuk vs laporan selesai</p>
        <div class="h-80 md:h-[350px]">
            <canvas id="reportTrendChart"></canvas>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Performa Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Skor gabungan berdasarkan laporan, kepuasan, dan maintenance</p>
        <div class="space-y-3 h-80 md:h-[350px] overflow-y-auto pr-2 custom-scrollbar">
            @php
                $facilitiesPerformance = [
                    ['name' => 'Gedung A', 'status' => 'Rendah', 'status_color' => 'green', 'reports' => 234, 'satisfaction' => '4.6/5', 'interval' => '35 hari', 'score' => 85],
                    ['name' => 'Gedung B', 'status' => 'Sedang', 'status_color' => 'yellow', 'reports' => 189, 'satisfaction' => '4.3/5', 'interval' => '28 hari', 'score' => 78],
                    ['name' => 'Laboratorium', 'status' => 'Tinggi', 'status_color' => 'red', 'reports' => 167, 'satisfaction' => '4/5', 'interval' => '12 hari', 'score' => 65],
                    ['name' => 'Perpustakaan', 'status' => 'Rendah', 'status_color' => 'green', 'reports' => 145, 'satisfaction' => '4.5/5', 'interval' => '42 hari', 'score' => 88],
                    ['name' => 'Kantin', 'status' => 'Sedang', 'status_color' => 'yellow', 'reports' => 123, 'satisfaction' => '3.8/5', 'interval' => '25 hari', 'score' => 72],
                    // Add more items to test scroll
                    ['name' => 'Area Parkir', 'status' => 'Rendah', 'status_color' => 'green', 'reports' => 98, 'satisfaction' => '4.0/5', 'interval' => '50 hari', 'score' => 82],
                ];

                $statusColors = [
                    'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                    'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                ];
            @endphp

            @foreach ($facilitiesPerformance as $facility)
                <div
                    class="bg-white border border-gray-200 p-4 rounded-lg flex justify-between items-center hover:shadow-sm transition-shadow duration-150">
                    <div class="flex-grow">
                        <div class="flex items-center mb-1">
                            <h5 class="font-semibold text-sm text-gray-700 mr-2">{{ $facility['name'] }}</h5>
                            <span
                                class="{{ $statusColors[$facility['status_color']]['bg'] }} {{ $statusColors[$facility['status_color']]['text'] }} text-xs font-semibold px-2 py-0.5 rounded-md">
                                    {{ $facility['status'] }}
                                </span>
                        </div>
                        <p class="text-xs text-gray-500">
                            {{ $facility['reports'] }} laporan &nbsp;&bull;&nbsp; {{ $facility['satisfaction'] }}
                            kepuasan &nbsp;&bull;&nbsp; {{ $facility['interval'] }} interval
                        </p>
                    </div>
                    <div class="text-right pl-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $facility['score'] }}</p>
                        <p class="text-xs text-gray-500">Skor</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    // Data and Configuration for Multi-Line Chart (Tren Laporan & Penyelesaian)
    const ctxReportTrend = document.getElementById('reportTrendChart').getContext('2d');
    const reportLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // Data perkiraan dari gambar
    const laporanMasukData = [100, 115, 90, 140, 160, 150, 180, 195, 150, 120, 50, 0];
    const laporanSelesaiData = [90, 100, 80, 120, 140, 145, 160, 170, 130, 90, 30, 0];

    const reportTrendChart = new Chart(ctxReportTrend, {
        type: 'line',
        data: {
            labels: reportLabels,
            datasets: [
                {
                    label: 'Laporan Masuk',
                    data: laporanMasukData,
                    borderColor: 'rgba(56, 168, 157, 1)', // Teal/Green
                    backgroundColor: 'rgba(56, 168, 157, 0.1)',
                    fill: false,
                    tension: 0.1,
                    pointBackgroundColor: 'rgba(56, 168, 157, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(56, 168, 157, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                },
                {
                    label: 'Laporan Selesai',
                    data: laporanSelesaiData,
                    borderColor: 'rgba(255, 127, 80, 1)', // Orange/Coral
                    backgroundColor: 'rgba(255, 127, 80, 0.1)',
                    fill: false,
                    tension: 0.1,
                    pointBackgroundColor: 'rgba(255, 127, 80, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(255, 127, 80, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Legenda tidak ditampilkan sesuai gambar
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 200,
                    ticks: {
                        stepSize: 50,
                        color: '#6b7280', // text-gray-500
                        font: {size: 10},
                        // Callback untuk menambahkan '-' jika diinginkan, tapi umumnya angka saja sudah cukup
                        // callback: function(value) { return value === 0 ? '0' : value + '–'; }
                    },
                    grid: {
                        color: '#e5e7eb' // gray-200
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280', // text-gray-500
                        font: {size: 10}
                    },
                    grid: {
                        display: false
                    }
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            }
        }
    });
</script>
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

