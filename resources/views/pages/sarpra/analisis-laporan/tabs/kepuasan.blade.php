<div class="flex flex-col lg:flex-row gap-6">

    <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h4 class="text-lg font-bold text-gray-800">Tren Kepuasan Bulanan</h4>
                <p class="text-xs text-gray-500">Perkembangan tingkat kepuasan pengguna</p>
            </div>
            <div class="relative">
                {{-- Dropdown sekarang diisi secara dinamis dari data controller --}}
                <select id="yearFilter"
                        class="appearance-none w-24 bg-white border border-gray-300 text-gray-700 py-2 pl-3 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @forelse (array_keys($yearlyRatingsData) as $year)
                        <option value="{{ $year }}" @if ($loop->last) selected @endif>{{ $year }}</option>
                    @empty
                        <option>N/A</option>
                    @endforelse
                </select>
            </div>
        </div>
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
                                    @if ($facility->rata_rata_rating >= $i)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @elseif ($facility->rata_rata_rating >= $i - 0.5)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-400 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="ml-1.5 text-xs text-gray-600 font-medium">
                                    {{ number_format($facility->rata_rata_rating, 1) }}
                                </span>
                                <span class="ml-2 text-xs text-gray-400 hidden sm:inline">
                                    ({{ $facility->total_ratings }} ulasan)
                                </span>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Indikator Visual --}}
                        <div class="flex-shrink-0 ml-2">
                            <div class="bg-gray-200 h-2 w-16 sm:w-20 rounded-full overflow-hidden"
                                 title="Rating: {{ number_format($facility->rata_rata_rating,1) }} dari {{ $maxStars }}">
                                <div
                                    class="bg-gradient-to-r from-yellow-400 to-orange-500 h-full transition-all duration-300 ease-in-out"
                                    style="width: {{ ($facility->rata_rata_rating / $maxStars) * 100 }}%;">
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
        const yearlyRatings = @json($yearlyRatingsData);
        const yearFilter = document.getElementById('yearFilter');
        const ctxTrend = document.getElementById('satisfactionTrendChart').getContext('2d');
        let satisfactionTrendChart;

        function updateChart(selectedYear) {
            const rawRatings = yearlyRatings[selectedYear] || [];
            const ratingsMap = new Map(rawRatings.map(item => [item.month, item.rating]));
            const now = new Date();
            const currentYear = now.getFullYear();
            const isCurrentYear = parseInt(selectedYear, 10) === currentYear;
            const monthsToShow = isCurrentYear ? now.getMonth() + 1 : 12;
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const finalLabels = [];
            const finalData = [];

            for (let i = 0; i < monthsToShow; i++) {
                finalLabels.push(monthNames[i]);
                const monthKey = String(i + 1).padStart(2, '0');
                finalData.push(ratingsMap.get(monthKey) || 0);
            }

            const chartData = {
                labels: finalLabels,
                datasets: [{
                    label: `Tingkat Kepuasan ${selectedYear}`,
                    data: finalData,
                    borderColor: 'rgba(79, 209, 197, 1)',
                    backgroundColor: 'rgba(79, 209, 197, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 4,
                    borderWidth: 2.5,
                }]
            };

            if (!satisfactionTrendChart) {
                satisfactionTrendChart = new Chart(ctxTrend, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: {display: false},
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {label: context => ` Rating: ${context.raw}`}
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 5.3,
                                ticks: {
                                    stepSize: 1,
                                    callback: function (value, index, ticks) {
                                        if (value === 5.3) {
                                            return null;
                                        }
                                        return value;
                                    }
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
            } else {
                satisfactionTrendChart.data = chartData;
                satisfactionTrendChart.update();
            }
        }

        yearFilter.addEventListener('change', (event) => {
            updateChart(event.target.value);
        });

        if (yearFilter.value) {
            updateChart(yearFilter.value);
        }
    </script>
@endpush

