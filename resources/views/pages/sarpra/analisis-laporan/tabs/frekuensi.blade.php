<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Interval Perbaikan per Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Rata-rata waktu antar perbaikan</p>
        <div class="h-80 md:h-[350px]"> {{-- Sesuaikan tinggi chart --}}
            <canvas id="intervalChart"></canvas>
        </div>
    </div>

{{--    @php--}}
{{--        // --- DATA DUMMY UNTUK TESTING -----}}
{{--        $fasilitasBerisiko = collect([--}}
{{--            (object)[--}}
{{--                'nama_lokasi' => 'Auditorium Utama',--}}
{{--                'jumlah_laporan' => 215,--}}
{{--                'interval_rata_rata_hari' => 2 // <-- Interval sangat pendek--}}
{{--            ],--}}
{{--            (object)[--}}
{{--                'nama_lokasi' => 'Toilet Lantai 3 (Pria)',--}}
{{--                'jumlah_laporan' => 55,--}}
{{--                'interval_rata_rata_hari' => 0 // <-- Interval ekstrem (misal: beberapa laporan di hari yang sama)--}}
{{--            ],--}}
{{--            (object)[--}}
{{--                'nama_lokasi' => 'Ruang Server',--}}
{{--                'jumlah_laporan' => 34,--}}
{{--                'interval_rata_rata_hari' => 7--}}
{{--            ],--}}
{{--            (object)[--}}
{{--                'nama_lokasi' => 'Perpustakaan',--}}
{{--                'jumlah_laporan' => 120,--}}
{{--                'interval_rata_rata_hari' => 29 // <-- Mendekati batas 30 hari--}}
{{--            ],--}}
{{--        ]);--}}
{{--    @endphp--}}
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Fasilitas Berisiko Tinggi</h4>
        <p class="text-xs text-gray-500 mb-6">Fasilitas dengan interval perbaikan pendek</p>
        <div class="space-y-3 overflow-y-auto max-h-72 pr-2">

            @forelse ($fasilitasBerisiko as $fasilitas)
                <div class="bg-red-50 border border-red-200 p-3.5 rounded-lg flex justify-between items-center">
                    <div>
                        <h5 class="font-semibold text-sm text-gray-700">{{ $fasilitas->nama_lokasi }}</h5>
                        <p class="text-xs text-gray-600">
                            {{ $fasilitas->jumlah_laporan }} laporan - Interval {{ $fasilitas->interval_rata_rata_hari }} hari
                        </p>
                    </div>
                    <button class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-md">
                        Perlu Perhatian
                    </button>
                </div>
            @empty
                <div class="rounded-lg border border-green-200 bg-green-50 p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-green-800">
                                Tidak Ada Isu Ditemukan
                            </h4>
                            <p class="mt-1 text-sm text-green-700">
                                Semua fasilitas dalam kondisi terpantau dan tidak ada yang diklasifikasikan sebagai berisiko tinggi.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</div>

<script>
    const ctxInterval = document.getElementById('intervalChart').getContext('2d');

    // Data untuk chart (label dan data diurutkan agar 'Gedung A' tampil di atas)
    const facilityLabels = ['Kantin', 'Perpustakaan', 'Laboratorium', 'Gedung B', 'Gedung A'];
    const intervalData = [23, 40, 12, 25, 35]; // Data disesuaikan dengan urutan label di atas

    const intervalChart = new Chart(ctxInterval, {
        type: 'bar',
        data: {
            labels: facilityLabels.slice().reverse(), // Balik urutan label untuk tampilan Chart.js
            datasets: [{
                label: 'Interval Perbaikan (hari)',
                data: intervalData.slice().reverse(), // Balik urutan data untuk tampilan Chart.js
                backgroundColor: 'rgba(45, 156, 145, 0.9)', // Warna teal seperti #2D9C91
                borderColor: 'rgba(45, 156, 145, 1)',
                borderWidth: 1,
                borderRadius: 4, // Sedikit lengkungan pada bar
                barThickness: 'flex', // Atau angka absolut seperti 20 atau 25
                // maxBarThickness: 30 // Opsional
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar chart menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Menyembunyikan legenda
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return ` ${context.raw} hari`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 60,
                    ticks: {
                        stepSize: 15,
                        color: '#6b7280', // text-gray-500
                        font: {
                            size: 10 // Ukuran font sumbu X
                        }
                    },
                    grid: {
                        color: '#e5e7eb', // Warna grid abu-abu muda (gray-200)
                        borderColor: '#d1d5db' // Warna border sumbu (gray-300)
                    }
                },
                y: {
                    ticks: {
                        color: '#374151', // text-gray-700
                        font: {
                            size: 11 // Ukuran font sumbu Y
                        }
                    },
                    grid: {
                        display: false // Menyembunyikan grid vertikal pada sumbu Y
                    }
                }
            },
            // Animasi bisa ditambahkan jika perlu
            // animation: {
            //     duration: 1000,
            //     easing: 'easeOutQuart'
            // }
        }
    });
</script>

</body>
</html>
