<!-- main content -->
<div class="flex flex-col lg:flex-row gap-6">

    <!-- Ringkasan Kondisi Fasilitas -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow flex flex-col h-[420px]">
        <!-- Header -->
        <div>
            <h4 class="text-md font-semibold text-gray-700 mb-1">Ringkasan Kondisi Fasilitas</h4>
            <p class="text-sm text-gray-500">Proporsi fasilitas berdasarkan kategori maintenance</p>
        </div> <!-- end Header -->

        <!-- Chart Area -->
        <div class="mt-6 pt-6 border-t border-gray-200 flex-1 min-h-0">
            <!-- chart container -->
            <div class="relative h-full w-full">
                <canvas id="maintenanceChart"></canvas>
            </div> <!-- end chart container -->
        </div> <!-- end Chart Area -->
    </div> <!-- end Ringkasan Kondisi Fasilitas -->

    <!-- Rekomendasi Maintenance -->
    <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow relative">
        <h4 class="text-md font-semibold text-gray-700 mb-1">Rekomendasi Maintenance</h4>
        <p class="text-sm text-gray-500 mb-4">Berdasarkan analisis frekuensi dan kepuasan</p>

        <!-- List Rekomendasi -->
        <div class="space-y-3 h-80 overflow-y-auto pr-2 custom-scrollbar">

            @forelse ($rekomendasiMaintenance as $rekomendasi)
                <a href="#"
                   class="block bg-white border border-gray-200 p-4 rounded-lg hover:shadow-lg hover:border-blue-500 transition-all duration-200 group">
                    <!-- Rekomendasi Item -->
                    <div class="flex justify-between items-center">
                        <div class="flex-1 min-w-0 pr-4">
                            <div class="flex items-center">
                                {{-- Judul kini hanya menampilkan nama --}}
                                <p class="text-sm font-semibold text-gray-900 truncate"
                                   title="{{ $rekomendasi['title'] }}">
                                    {{ $rekomendasi['title'] }}
                                </p>

                                {{-- [BARU] Kode barang menjadi 'tag' terpisah --}}
                                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-mono">
                                    {{ $rekomendasi['item_code'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $rekomendasi['subtitle'] }}">
                                {{ $rekomendasi['subtitle'] }}
                            </p>
                            <div class="text-xs text-gray-600 mt-3 pt-3 border-t border-gray-200">
                                <span
                                    class="font-medium {{ $statusColors[$rekomendasi['status_color']]['text'] ?? 'text-gray-600' }}">{{ $rekomendasi['reports'] }} laporan</span>
                                <span class="mx-1.5">&bull;</span>
                                <span
                                    class="font-medium {{ $statusColors[$rekomendasi['status_color']]['text'] ?? 'text-gray-600' }}">{{ number_format($rekomendasi['satisfaction'], 2) }}/5 kepuasan</span>
                                <span class="mx-1.5">&bull;</span>
                                <span
                                    class="font-medium {{ $statusColors[$rekomendasi['status_color']]['text'] ?? 'text-gray-600' }}">{{ $rekomendasi['interval'] }} hari interval</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex-shrink-0">
                            <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                         {{ $statusColors[$rekomendasi['status_color']]['bg'] ?? 'bg-gray-100' }}
                                         {{ $statusColors[$rekomendasi['status_color']]['text'] ?? 'text-gray-800' }}"
                                  data-description="@switch($rekomendasi['status'])
                                             @case('Berisiko')
                                                 Fasilitas ini sangat sering rusak dengan interval pendek. Memerlukan tindakan perbaikan segera.
                                                 @break
                                             @case('Waspada')
                                                 Fasilitas ini mulai menunjukkan frekuensi kerusakan yang perlu diwaspadai. Sebaiknya segera dijadwalkan untuk maintenance preventif.
                                                 @break
                                             @case('Cukup')
                                                 Performa fasilitas ini cukup, namun perlu observasi rutin untuk mencegah penurunan kondisi.
                                                 @break
                                             @case('Baik')
                                                 Fasilitas ini dalam kondisi optimal dengan sedikit atau tanpa laporan kerusakan.
                                                 @break
                                             @default
                                                 Status tidak terdefinisi.
                                         @endswitch"
                            >
                                @if ($rekomendasi['status_color'] == 'red')
                                    <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"><path fill-rule="evenodd"
                                                                   d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                   clip-rule="evenodd"/></svg>
                                @elseif ($rekomendasi['status_color'] == 'yellow')
                                    <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"><path fill-rule="evenodd"
                                                                   d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z"
                                                                   clip-rule="evenodd"/></svg>
                                @else
                                    <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"><path fill-rule="evenodd"
                                                                   d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                   clip-rule="evenodd"/></svg>
                                @endif
                                {{ $rekomendasi['action_label'] }}
                            </span>
                        </div> <!-- end Status Badge -->
                    </div> <!-- End Rekomendasi Item -->
                </a>
            @empty
                <!-- Data Kosong -->
                <div
                    class="h-full flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-200 text-center px-6 bg-green-50">

                    <!-- Ikon Data Kosong -->
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div> <!-- end Ikon Data Kosong -->

                    <!-- Pesan Data Kosong -->
                    <div class="mt-2">
                        <h4 class="text-sm font-semibold text-green-800">Semua Fasilitas Terpantau</h4>
                        <p class="mt-1 text-sm text-green-700">Saat ini tidak ada rekomendasi maintenance yang perlu
                            diprioritaskan.</p>
                    </div> <!-- end Pesan Data Kosong -->
                </div> <!-- end Data Kosong -->
            @endforelse
        </div> <!-- end List Rekomendasi -->

        <!-- Popover untuk status badge -->
        <div id="status-popover"
             class="hidden absolute z-20 w-64 text-sm rounded-lg shadow-xl transition-opacity duration-200 opacity-0">
            <div class="bg-gray-800 text-white p-3 rounded-lg">
            </div>
        </div> <!-- end Popover -->
    </div> <!-- end Rekomendasi Maintenance -->
</div> <!-- end main content -->


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===================================================================
            // 1. MENGAMBIL DATA UTAMA & ELEMEN DASAR
            // ===================================================================
            const allRecommendations = @json($rekomendasiMaintenance ?? []);

            const popover = document.getElementById('status-popover');
            const badges = document.querySelectorAll('.status-badge');
            const chartElement = document.getElementById('maintenanceChart');

            // ===================================================================
            // 2. LOGIKA UNTUK POPOVER STATUS
            // ===================================================================
            if (popover && badges.length > 0) {
                let popoverTimeout;

                badges.forEach(badge => {
                    badge.addEventListener('mouseenter', (event) => {
                        clearTimeout(popoverTimeout);
                        const description = badge.dataset.description.trim();
                        if (!description) return;
                        popover.querySelector('div').textContent = description;
                        positionPopover(badge);
                        popover.classList.remove('hidden');
                        setTimeout(() => popover.classList.remove('opacity-0'), 10);
                    });

                    badge.addEventListener('mouseleave', () => {
                        popoverTimeout = setTimeout(() => {
                            popover.classList.add('opacity-0');
                            setTimeout(() => popover.classList.add('hidden'), 200);
                        }, 200);
                    });
                });

                popover.addEventListener('mouseenter', () => clearTimeout(popoverTimeout));
                popover.addEventListener('mouseleave', () => {
                    popover.classList.add('opacity-0');
                    setTimeout(() => popover.classList.add('hidden'), 200);
                });
            }

            function positionPopover(targetElement) {
                if (!popover) return;
                const container = targetElement.closest('.relative');
                if (!container) return;
                const targetRect = targetElement.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();
                const top = targetRect.top - containerRect.top;
                const left = targetRect.left - containerRect.left;
                let finalTop = top - popover.offsetHeight - 10;
                let finalLeft = left + (targetRect.width / 2) - (popover.offsetWidth / 2);
                if (finalTop < 0) finalTop = top + targetRect.height + 10;
                if (finalLeft < 0) finalLeft = 5;
                if (finalLeft + popover.offsetWidth > containerRect.width) finalLeft = containerRect.width - popover.offsetWidth - 5;
                popover.style.top = `${finalTop}px`;
                popover.style.left = `${finalLeft}px`;
            }

            // ===================================================================
            // 3. LOGIKA UNTUK CHART DISTRIBUSI DINAMIS (4 KATEGORI)
            // ===================================================================
            if (!chartElement) return;

            // Penanganan jika tidak ada data sama sekali untuk dianalisis
            if (!allRecommendations || allRecommendations.length === 0) {
                const chartContainer = chartElement.parentElement;
                chartContainer.classList.add('flex', 'items-center', 'justify-center');
                chartContainer.innerHTML = `
                <div class="text-center text-gray-500 px-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                    <h4 class="mt-2 text-sm font-semibold text-gray-700">Data Analisis Belum Tersedia</h4>
                    <p class="mt-1 text-xs">Chart distribusi akan muncul setelah analisis selesai dijalankan.</p>
                </div>
            `;
                return;
            }

            // Hitung data ringkasan dari data rekomendasi yang ada
            const summary = {tinggi: 0, waspada: 0, cukup: 0, baik: 0};
            allRecommendations.forEach(item => {
                switch (item.status_color) {
                    case 'red':
                        summary.tinggi++;
                        break;
                    case 'yellow':
                        summary.waspada++;
                        break;
                    case 'blue':
                        summary.cukup++;
                        break;
                    case 'green':
                        summary.baik++;
                        break;
                }
            });

            const ctxMaintenance = chartElement.getContext('2d');
            const maintenanceChart = new Chart(ctxMaintenance, {
                type: 'doughnut',
                data: {
                    labels: ['Tindakan Segera', 'Perlu Dijadwalkan', 'Observasi Rutin', 'Kondisi Optimal'],
                    datasets: [{
                        label: 'Jumlah Fasilitas',
                        data: [
                            summary.tinggi,
                            summary.waspada,
                            summary.cukup,
                            summary.baik
                        ],
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',  // Merah
                            'rgba(234, 179, 8, 0.8)',  // Kuning
                            'rgba(59, 130, 246, 0.8)', // Biru
                            'rgba(34, 197, 94, 0.8)'   // Hijau
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' fasilitas';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
