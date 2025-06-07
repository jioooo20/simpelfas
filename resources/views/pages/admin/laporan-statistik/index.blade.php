@extends('layouts.main')
@section('judul', 'Laporan & Statistik Sistem')
@section('content')
    <div class="container mx-auto px-4 py-4">

        {{-- Grafik Statistik --}}
        <div class="my-8">
            <h2 class="text-xl font-bold mb-4">📊 Statistik Laporan</h2>
            <div class="flex flex-wrap gap-6 justify-between">
                <!-- Chart 1 -->
                <div class="w-full md:w-[48%] bg-white rounded shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">📈 Tren Jumlah Laporan per Bulan</h3>
                    <div class="relative h-64">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2 -->
                <div class="w-full md:w-[48%] bg-white rounded shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">🧩 Fasilitas Paling Banyak Dilaporkan</h3>
                    <div class="relative h-64">
                        <canvas id="fasilitasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('laporanChart').getContext('2d');
            const laporanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($laporanPerBulan->pluck('bulan')),
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: @json($laporanPerBulan->pluck('jumlah')),
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const ctx2 = document.getElementById('fasilitasChart').getContext('2d');
            const fasilitasChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: @json($kerusakanPerBarang->keys()),
                    datasets: [{
                        label: 'Jumlah Kerusakan',
                        data: @json($kerusakanPerBarang->values()),
                        backgroundColor: [
                            '#60A5FA', '#F472B6', '#FBBF24', '#34D399', '#818CF8', '#F87171'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>

        {{-- Filter Periode --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-2 items-end mb-4">
            <div>
                <label for="start_date" class="block text-sm">Dari</label>
                <input type="date" name="start_date" id="start_date"
                    value="{{ request('start_date') ?? now()->startOfMonth()->toDateString() }}"
                    class="input input-bordered">
            </div>
            <div>
                <label for="end_date" class="block text-sm">Sampai</label>
                <input type="date" name="end_date" id="end_date"
                    value="{{ request('end_date') ?? now()->endOfMonth()->toDateString() }}" class="input input-bordered">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
        </form>

        {{-- Card Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="stat bg-base-200 rounded-xl p-4 shadow">
                <div class="stat-title">Total Laporan</div>
                <div class="stat-value text-primary">{{ $totalLaporan }}</div>
            </div>
            <div class="stat bg-base-200 rounded-xl p-4 shadow">
                <div class="stat-title">Selesai</div>
                <div class="stat-value text-green-600">{{ $selesai }}</div>
            </div>
            <div class="stat bg-base-200 rounded-xl p-4 shadow">
                <div class="stat-title">Dalam Proses</div>
                <div class="stat-value text-yellow-600">{{ $proses }}</div>
            </div>
            <div class="stat bg-base-200 rounded-xl p-4 shadow">
                <div class="stat-title">Ditolak</div>
                <div class="stat-value text-red-600">{{ $ditolak }}</div>
            </div>
        </div>

        {{-- kotak --}}
        <div class="bg-base-100 shadow-md border rounded-xl p-6">
            {{-- table --}}
            <div class="overflow-x-auto">
                <livewire:laporan-statistik />
            </div>
        </div>
    @endsection
