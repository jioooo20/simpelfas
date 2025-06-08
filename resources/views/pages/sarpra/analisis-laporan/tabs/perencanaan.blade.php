<div class="flex flex-col lg:flex-row gap-6">
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow">
        <h4 class="text-md font-semibold text-gray-700 mb-1">Jadwal Maintenance Mendatang</h4>
        <p class="text-sm text-gray-500 mb-6">Maintenance yang dijadwalkan dalam 30 hari ke depan</p>

        <div class="h-64">
            <canvas id="maintenanceChart"></canvas>
        </div>

    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow">
        <h4 class="text-md font-semibold text-gray-700 mb-1">Rekomendasi Maintenance</h4>
        <p class="text-sm text-gray-500 mb-4">Berdasarkan analisis frekuensi dan kepuasan</p>
        <div class="space-y-4">
            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-red-500 mr-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-red-700">Prioritas Tinggi</h5>
                        <p class="text-sm text-red-600">Laboratorium memerlukan maintenance segera</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-yellow-500 mr-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-yellow-700">Maintenance Rutin</h5>
                        <p class="text-sm text-yellow-600">Gedung B perlu dijadwalkan maintenance preventif</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="text-green-500 mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-green-700">Kondisi Baik</h5>
                        <p class="text-sm text-green-600">Perpustakaan dalam kondisi optimal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('maintenanceChart').getContext('2d');

    const maintenanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Jumlah Maintenance',
                data: [12, 15, 11, 18, 8, 14, 6, 10, 17, 4, 9, 13], // Dummy data
                backgroundColor: 'rgba(13, 148, 136, 0.7)', // teal-600
                borderRadius: 6,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'x', // Pastikan ini untuk chart vertikal
            animation: {
                y: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => ` ${context.raw} maintenance`
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    ticks: {
                        stepSize: 5
                    },
                    grid: {
                        color: '#e5e7eb'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
