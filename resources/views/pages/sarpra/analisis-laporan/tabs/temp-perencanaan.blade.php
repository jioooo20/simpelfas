<div class="flex flex-col lg:flex-row gap-6">
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-md border border-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-lg font-bold text-gray-800">
                    Statistik Laporan Kerusakan
                </h3>
                <p class="text-sm text-gray-500">
                    Data laporan per bulan
                </p>
            </div>

            <div class="flex items-center gap-2">
                <div>
                    <select id="monthFilter"
                            class="bg-gray-50 border border-gray-300 text-sm font-medium text-gray-700 rounded-md pl-4 pr-8 py-2 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150">
                    </select>
                </div>
                <div>
                    <select id="yearFilter"
                            class="bg-gray-50 border border-gray-300 text-sm font-medium text-gray-700 rounded-md pl-4 pr-8 py-2 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150">
                        <option value="">2025</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="h-64">
            <canvas id="maintenanceChart"></canvas>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow">
        <h4 class="text-md font-semibold text-gray-700 mb-1">Analisis Fasilitas</h4>
        <p class="text-sm text-gray-500 mb-4">Berdasarkan frekuensi kerusakan dan kepuasan setelah perbaikan</p>
        <div class="space-y-4">
            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-red-500 mr-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-red-700">Sering Rusak</h5>
                        <p class="text-sm text-red-600">Laboratorium dilaporkan rusak sebanyak 5 kali bulan ini</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-yellow-500 mr-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-yellow-700">Perlu Monitoring</h5>
                        <p class="text-sm text-yellow-600">Gedung B memiliki 2 laporan dan skor kepuasan rendah</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-green-500 mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-green-700">Tidak Ada Masalah</h5>
                        <p class="text-sm text-green-600">Perpustakaan tidak ada laporan kerusakan dalam 30 hari
                            terakhir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('skrip')
    <script>
        (function () {
            // Mock data for demonstration purposes. Replace with your actual data.
            const laporanPerBulan = {!! json_encode($statistikKerusakan ?? []) !!};
            const semuaLaporanHarian = {!! json_encode($statistikKerusakanHarian ?? []) !!};

            const monthFilter = document.getElementById('monthFilter');
            const yearFilter = document.getElementById('yearFilter');
            const ctx = document.getElementById('maintenanceChart').getContext('2d');

            const maintenanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: [],
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.2,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {display: false},
                        tooltip: {mode: 'index', intersect: false}
                    }
                }
            });

            function updateChart() {
                if (!yearFilter.value || !monthFilter.value) {
                    maintenanceChart.data.labels = ['Tidak ada data untuk ditampilkan'];
                    maintenanceChart.data.datasets[0].data = [];
                    maintenanceChart.update();
                    return;
                }

                const selectedYear = parseInt(yearFilter.value, 10);
                const selectedMonth = parseInt(monthFilter.value, 10);

                const filteredData = semuaLaporanHarian.filter(item => {
                    const itemYear = parseInt(item.tanggal.substring(0, 4), 10);
                    const itemMonth = parseInt(item.tanggal.substring(5, 7), 10);
                    return itemYear === selectedYear && itemMonth === selectedMonth;
                });

                const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
                const monthShortName = new Date(selectedYear, selectedMonth - 1, 1).toLocaleString('id-ID', {month: 'short'});
                const labels = Array.from({length: daysInMonth}, (_, i) => `${i + 1} ${monthShortName}`);
                const data = Array(daysInMonth).fill(0);

                filteredData.forEach(item => {
                    const day = parseInt(item.tanggal.substring(8, 10), 10);
                    const index = day - 1;
                    if (index >= 0 && index < daysInMonth) {
                        data[index] = item.total_pelaporan;
                    }
                });

                maintenanceChart.data.labels = labels;
                maintenanceChart.data.datasets[0].data = data;
                maintenanceChart.update();
            }

            // --- FUNGSI INI DIMODIFIKASI ---
            function populateYearFilter() {
                const currentYear = new Date().getFullYear(); // Hasil: 2025
                const yearsFromData = laporanPerBulan.map(item => item.tahun);

                // Pastikan tahun saat ini ada, lalu buat daftar tahun yang unik dan terurut
                const allYears = [...new Set([currentYear, ...yearsFromData])].sort((a, b) => b - a);

                yearFilter.innerHTML = ''; // Kosongkan opsi sebelumnya

                // Loop setiap tahun untuk membuat elemen <option>
                allYears.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;

                    // Jika tahun sama dengan tahun saat ini, atur sebagai default 'selected'
                    if (year === currentYear) {
                        option.selected = true;
                    }

                    yearFilter.appendChild(option);
                });
            }

            // --- AKHIR MODIFIKASI ---


            function populateMonthFilter(selectedYear) {
                const monthsForYear = laporanPerBulan.filter(item => String(item.tahun) === String(selectedYear));
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1;

                monthFilter.innerHTML = '';

                // Gunakan semua bulan jika tidak ada data untuk tahun yang dipilih
                if (monthsForYear.length === 0) {
                    const allMonths = Array.from({length: 12}, (e, i) => {
                        return {
                            bulan: i + 1,
                            nama_bulan: new Date(null, i + 1, null).toLocaleString('id-ID', {month: 'long'})
                        }
                    });

                    allMonths.forEach(item => {
                        const option = document.createElement('option');
                        option.value = String(item.bulan);
                        option.textContent = item.nama_bulan;
                        monthFilter.appendChild(option);
                    });

                } else {
                    monthsForYear.sort((a, b) => a.bulan - b.bulan).forEach(item => {
                        const option = document.createElement('option');
                        option.value = String(item.bulan);
                        option.textContent = item.nama_bulan;
                        monthFilter.appendChild(option);
                    });
                }

                // Atur bulan default ke bulan saat ini JIKA tahun yang dipilih adalah tahun saat ini
                if (String(selectedYear) === String(currentYear)) {
                    if ([...monthFilter.options].some(opt => opt.value === String(currentMonth))) {
                        monthFilter.value = String(currentMonth);
                    }
                } else if (monthFilter.options.length > 0) {
                    // Jika tidak, pilih bulan pertama yang tersedia
                    monthFilter.value = monthFilter.options[0].value;
                }
            }

            yearFilter.addEventListener('change', () => {
                populateMonthFilter(yearFilter.value);
                updateChart();
            });

            monthFilter.addEventListener('change', updateChart);

            // Inisialisasi awal
            populateYearFilter();
            populateMonthFilter(yearFilter.value);
            updateChart();
        })();
    </script>
@endpush
