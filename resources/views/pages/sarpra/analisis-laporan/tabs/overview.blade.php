<div class="flex flex-col lg:flex-row gap-6 mb-8">

    <div class="w-full lg:w-3/5 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Key Performance Indicators</h4>
        <p class="text-xs text-gray-500 mb-6">Indikator kinerja utama manajemen fasilitas</p>
        <div class="space-y-4">
            @php
                $kpis = [
                    ['title' => 'Efisienzi Penyelesaian', 'value' => '88%', 'target' => 'Target: 90%'],
                    ['title' => 'Kepuasan Pengguna', 'value' => '4.2/5', 'target' => 'Target: 4.5/5'],
                    ['title' => 'Waktu Respon Rata-rata', 'value' => '3.2hari', 'target' => 'Target: 2hari'],
                    ['title' => 'Rasio Preventif', 'value' => '68%', 'target' => 'Target: 75%'],
                ];
            @endphp
            @foreach ($kpis as $kpi)
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <div>
                        <h5 class="text-sm font-medium text-gray-600">{{ $kpi['title'] }}</h5>
                        <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $kpi['value'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $kpi['target'] }}</p>
                    </div>
                    <div class="bg-slate-700 h-2.5 w-20 rounded-full"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="w-full lg:w-2/5 bg-white p-6 rounded-xl shadow-lg">
        <h4 class="text-lg font-bold text-gray-800 mb-1">Aktivitas Terbaru</h4>
        <p class="text-xs text-gray-500 mb-6">Update terkini dari semua modul</p>
        <div class="space-y-3 h-[360px] overflow-y-auto custom-scrollbar pr-2">
            @php
                $activities = [
                    ['icon' => 'fas fa-exclamation-triangle', 'color' => 'text-red-500', 'title' => 'Laporan baru: AC Gedung A rusak', 'time' => '2 jam lalu', 'actor' => 'Ahmad Rizki'],
                    ['icon' => 'fas fa-check-circle', 'color' => 'text-green-500', 'title' => 'Maintenance lift selesai', 'time' => '4 jam lalu', 'actor' => 'Tim Teknis'],
                    ['icon' => 'fas fa-comment-dots', 'color' => 'text-blue-500', 'title' => 'Feedback positif: Perbaikan proyektor', 'time' => '6 jam lalu', 'actor' => 'Siti Nurhaliza'],
                    ['icon' => 'fas fa-calendar-alt', 'color' => 'text-gray-500', 'title' => 'Maintenance AC dijadwalkan', 'time' => '1 hari lalu', 'actor' => 'System'],
                    ['icon' => 'fas fa-tools', 'color' => 'text-orange-500', 'title' => 'Permintaan Alat Baru', 'time' => '2 hari lalu', 'actor' => 'Departemen IT'],
                ];
            @endphp
            @foreach ($activities as $activity)
                <div
                    class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                    <div class="flex-shrink-0 w-5 text-center mt-0.5">
                        <i class="{{ $activity['icon'] }} {{ $activity['color'] }} text-base"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $activity['time'] }} <span
                                class="text-gray-400 mx-1">&bull;</span> {{ $activity['actor'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="w-full bg-white p-6 rounded-xl shadow-lg">
    <div class="mb-4">
        <h4 class="text-xl font-bold text-gray-800 mb-1">Tindakan Prioritas</h4>
        <p class="text-sm text-gray-600">Aksi yang memerlukan perhatian segera berdasarkan analisis komprehensif.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $priorityActions = [
                [
                    'badges' => [['text' => 'Tinggi', 'bg' => 'bg-red-100', 'text_color' => 'text-red-700'], ['text' => 'Urgent', 'bg' => 'bg-red-100', 'text_color' => 'text-red-700']],
                    'title' => 'Lab Komputer - Maintenance Darurat',
                    'description' => 'Interval perbaikan 12 hari, kepuasan turun ke 3.2',
                    'location' => 'Laboratorium',
                    'date' => '2024-01-20'
                ],
                [
                    'badges' => [['text' => 'Sedang', 'bg' => 'bg-yellow-100', 'text_color' => 'text-yellow-700'], ['text' => 'Maintenance', 'bg' => 'bg-yellow-100', 'text_color' => 'text-yellow-700']],
                    'title' => 'AC Gedung A - Pemeliharaan Preventif',
                    'description' => 'Jadwal maintenance rutin untuk mencegah kerusakan',
                    'location' => 'Gedung A',
                    'date' => '2024-01-22'
                ],
                [
                    'badges' => [['text' => 'Sedang', 'bg' => 'bg-yellow-100', 'text_color' => 'text-yellow-700'], ['text' => 'Satisfaction', 'bg' => 'bg-yellow-100', 'text_color' => 'text-yellow-700']],
                    'title' => 'Kantin - Perbaikan Layanan',
                    'description' => 'Kepuasan pengguna turun ke 3.8, perlu tindakan perbaikan',
                    'location' => 'Kantin',
                    'date' => '2024-01-25'
                ],
            ];
        @endphp

        @foreach ($priorityActions as $action)
            <div
                class="bg-white p-5 rounded-xl shadow-lg flex flex-col justify-between hover:shadow-xl transition-shadow duration-200">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        @foreach ($action['badges'] as $badge)
                            <span
                                class="{{ $badge['bg'] }} {{ $badge['text_color'] }} text-xs font-semibold px-2.5 py-0.5 rounded-md">
                                {{ $badge['text'] }}
                            </span>
                        @endforeach
                    </div>
                    <h5 class="text-md font-semibold text-gray-800 mb-1.5">{{ $action['title'] }}</h5>
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">{{ $action['description'] }}</p>
                </div>
                <div
                    class="flex justify-between items-center text-xs text-gray-500 border-t border-gray-100 pt-3 mt-auto">
                    <span><i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>{{ $action['location'] }}</span>
                    <span><i class="fas fa-calendar-alt mr-1 text-gray-400"></i>{{ $action['date'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

