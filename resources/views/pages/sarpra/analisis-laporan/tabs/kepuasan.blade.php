<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Tren Kepuasan Bulanan</h4>
        <p class="text-xs text-gray-500 mb-6">Perkembangan tingkat kepuasan pengguna</p>
        <div class="h-80 md:h-[330px]"> {{-- Sesuaikan tinggi chart --}}
            <canvas id="satisfactionTrendChart"></canvas>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Kepuasan per Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Rating kepuasan berdasarkan fasilitas</p>
        <div class="space-y-3 h-80 md:h-[330px] overflow-y-auto pr-2"> {{-- Tambahkan tinggi tetap dan overflow --}}
            @php
                $facilities = [
                    ['name' => 'Gedung A', 'rating' => 4.6],
                    ['name' => 'Gedung B', 'rating' => 4.3],
                    ['name' => 'Laboratorium', 'rating' => 4.0],
                    ['name' => 'Perpustakaan', 'rating' => 4.5],
                    ['name' => 'Kantin', 'rating' => 3.8],
                    // Tambahkan data dummy lain jika ingin scroll terlihat
                    ['name' => 'Ruang Diskusi', 'rating' => 4.1],
                    ['name' => 'Taman', 'rating' => 3.5],
                ];
                $maxStars = 5;
            @endphp

            @foreach ($facilities as $facility)
                <div
                    class="bg-white border border-gray-200 p-3 rounded-lg flex justify-between items-center hover:shadow-sm transition-shadow duration-150">
                    <div>
                        <h5 class="font-semibold text-sm text-gray-700 mb-0.5">{{ $facility['name'] }}</h5>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= $maxStars; $i++)
                                @if ($i <= floor($facility['rating']))
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @elseif ($i - 0.5 <= $facility['rating'])
                                    {{-- Cek untuk setengah bintang (opsional, jika ingin lebih detail) --}}
                                    {{-- Jika ingin implementasi setengah bintang yang lebih akurat, bisa gunakan ikon fa-star-half-alt --}}
                                    <i class="fas fa-star text-yellow-400 text-xs"></i> {{-- Untuk simple, bulatkan ke atas jika > x.0 dan < x.5 tidak jadi setengah --}}
                                    {{-- Atau gunakan far fa-star untuk bintang kosong jika tidak ada setengah bintang --}}
                                    {{-- <i class="far fa-star text-gray-300 text-xs"></i> --}}
                                @else
                                    <i class="far fa-star text-gray-300 text-xs"></i>
                                @endif
                            @endfor
                            <span
                                class="ml-1.5 text-xs text-gray-600 font-medium">{{ number_format($facility['rating'], 1) }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-700 h-2 w-10 rounded-full"></div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<script>
    // Data dan Konfigurasi untuk Line Chart (Tren Kepuasan Bulanan)
    const ctxTrend = document.getElementById('satisfactionTrendChart').getContext('2d');
    const trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const trendData = [4.2, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9, 5.0, 4.8, 4.7, 4.6, 0.5];
    const trendLineColor = 'rgba(79, 209, 197, 1)'; // #4FD1C5

    const satisfactionTrendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Tingkat Kepuasan',
                data: trendData,
                borderColor: trendLineColor,
                backgroundColor: trendLineColor, // Warna area di bawah garis jika 'fill: true'
                fill: false, // Set true jika ingin area di bawah garis diwarnai
                tension: 0.1, // Membuat garis sedikit melengkung (0 untuk lurus)
                pointBackgroundColor: trendLineColor,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: trendLineColor,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function (context) {
                            return ` Rating: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 5.5, // Agar ada ruang di atas angka 5
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280', // text-gray-500
                        font: {size: 10},
                        // Callback untuk memformat label sumbu Y (misal: 0, 1, 2, 3, 4, 5)
                        // Untuk label spesifik seperti '2–', '5' di gambar, butuh kustomisasi lebih lanjut
                        // atau pengaturan tick yang sangat spesifik. Untuk sekarang kita pakai stepSize.
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
                        display: false // Sembunyikan grid vertikal
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
