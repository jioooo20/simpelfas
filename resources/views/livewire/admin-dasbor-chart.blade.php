<div class="w-full h-full flex flex-col">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 font-['Montserrat']">Tren Laporan</h2>
    <div class="flex-1 min-h-0 relative">
        <canvas id="statistikChart" class="w-full h-full"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statistikChart').getContext('2d');

    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: chartData.datasets.map(dataset => ({
                ...dataset,
                borderWidth: 3,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: false,
                tension: 0.4
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6B7280'
                    },
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#6B7280'
                    },
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)'
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>
