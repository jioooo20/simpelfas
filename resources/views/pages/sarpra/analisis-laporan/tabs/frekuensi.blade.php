<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Interval Perbaikan per Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Rata-rata waktu antar perbaikan</p>
        <div class="h-80 md:h-[350px]"> {{-- Sesuaikan tinggi chart --}}
            <canvas id="intervalChart"></canvas>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Fasilitas Berisiko Tinggi</h4>
        <p class="text-xs text-gray-500 mb-6">Fasilitas dengan interval perbaikan pendek</p>
        <div class="space-y-3">
            <div
                class="bg-red-50 border border-red-200 p-3.5 rounded-lg flex justify-between items-center hover:shadow-md transition-shadow duration-200">
                <div>
                    <h5 class="font-semibold text-sm text-gray-700">Gedung B</h5>
                    <p class="text-xs text-gray-600">189 laporan - Interval 28 hari</p>
                </div>
                <button
                    class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-md hover:bg-red-200 transition-colors duration-200">
                    Perlu Perhatian
                </button>
            </div>
            <div
                class="bg-red-50 border border-red-200 p-3.5 rounded-lg flex justify-between items-center hover:shadow-md transition-shadow duration-200">
                <div>
                    <h5 class="font-semibold text-sm text-gray-700">Laboratorium</h5>
                    <p class="text-xs text-gray-600">167 laporan - Interval 12 hari</p>
                </div>
                <button
                    class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-md hover:bg-red-200 transition-colors duration-200">
                    Perlu Perhatian
                </button>
            </div>
            <div
                class="bg-red-50 border border-red-200 p-3.5 rounded-lg flex justify-between items-center hover:shadow-md transition-shadow duration-200">
                <div>
                    <h5 class="font-semibold text-sm text-gray-700">Kantin</h5>
                    <p class="text-xs text-gray-600">123 laporan - Interval 25 hari</p>
                </div>
                <button
                    class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-md hover:bg-red-200 transition-colors duration-200">
                    Perlu Perhatian
                </button>
            </div>
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
