<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Tren Kepuasan Bulanan</h4>
        <p class="text-xs text-gray-500 mb-6">Perkembangan tingkat kepuasan pengguna</p>
        <div class="h-80 md:h-[330px]">
            <canvas id="satisfactionTrendChart"></canvas>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Kepuasan per Fasilitas</h4>
        <p class="text-xs text-gray-500 mb-6">Rating kepuasan berdasarkan fasilitas</p>
        <div
            class="space-y-3 h-80 md:h-[330px] overflow-y-auto pr-2 custom-scrollbar">
            @php $maxStars = 5; @endphp

            @if (count($facilities) > 0)
                @foreach ($facilities as $facility)
                    <div
                        class="bg-white border border-gray-200 p-3 rounded-lg flex justify-between items-center hover:shadow-md transition-shadow duration-150 ease-in-out">

                        {{-- Kolom Kiri: Info Fasilitas & Rating --}}
                        <div class="flex-1 min-w-0 pr-3">
                            <h5 class="font-semibold text-sm text-gray-700 truncate"
                                title="{{ $facility->item_name }} {{ $facility->item_code ? '['.$facility->item_code.']' : '' }}">
                                {{ $facility->item_name }} {{ $facility->item_code ? '['.$facility->item_code.']' : '' }}
                            </h5>
                            <p class="text-xs text-gray-500 truncate"
                               title="{{ $facility->room }}, {{ $facility->floor }}, {{ $facility->building }}">
                                {{ $facility->room }} &bull; {{ $facility->floor }}
                            </p>
                            <div class="flex items-center mt-1">
                                @for ($i = 1; $i <= $maxStars; $i++)
                                    @if ($facility->rating >= $i)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @elseif ($facility->rating >= $i - 0.5)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-400 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="ml-1.5 text-xs text-gray-600 font-medium">
                                    {{ number_format($facility->rating, 1) }}
                                </span>
                                    <span class="ml-2 text-xs text-gray-400 hidden sm:inline">
                                    ({{ $facility->total_ratings }} ulasan)
                                </span>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Indikator Visual --}}
                        <div class="flex-shrink-0 ml-2">
                            <div class="bg-gray-200 h-2 w-16 sm:w-20 rounded-full overflow-hidden"
                                 title="Rating: {{ number_format($facility->rating,1) }} dari {{ $maxStars }}">
                                <div
                                    class="bg-gradient-to-r from-yellow-400 to-orange-500 h-full transition-all duration-300 ease-in-out"
                                    style="width: {{ ($facility->rating / $maxStars) * 100 }}%;">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <p class="text-center text-gray-500 text-sm py-10">Belum ada data kepuasan fasilitas untuk
                    ditampilkan.</p>
            @endif
        </div>
    </div>
</div>

@push('skrip')
    <script>
        const rawRatings = @json($monthlyRatings);

        const monthMap = {
            '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
            '05': 'Mei', '06': 'Jun', '07': 'Jul', '08': 'Agu',
            '09': 'Sep', '10': 'Okt', '11': 'Nov', '12': 'Des'
        };

        const trendLabels = rawRatings.map(item => {
            const [year, month] = item.bulan.split('-');
            return monthMap[month] || month;
        });

        const trendData = rawRatings.map(item => item.rata_rata_rating);

        const ctxTrend = document.getElementById('satisfactionTrendChart').getContext('2d');

        const satisfactionTrendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Tingkat Kepuasan',
                    data: trendData,
                    borderColor: 'rgba(79, 209, 197, 1)',
                    backgroundColor: 'rgba(79, 209, 197, 1)',
                    fill: false,
                    tension: 0.1,
                    pointBackgroundColor: 'rgba(79, 209, 197, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(79, 209, 197, 1)',
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
                        max: 5.5,
                        ticks: {
                            stepSize: 1,
                            color: '#6b7280',
                            font: { size: 10 }
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6b7280',
                            font: { size: 10 }
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
@endpush

