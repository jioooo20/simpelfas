{{-- filepath: d:\Coolyeah\SEM4\A PBL\code app\simpelfas\resources\views\pages\sarpra\dasbor\index.blade.php --}}
@extends('layouts.main')
@section('judul', 'Dasbor Sarana Prasarana')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="container mx-auto px-4 py-3 1/2">

            <!-- Enhanced Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Fasilitas -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Fasilitas</h3>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalFasilitas) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Laporan -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Laporan</h3>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalLaporan) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Verifikasi -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-yellow-200 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Menunggu Verifikasi</h3>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($laporanMenungguVerifikasi) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l6 6 4-10"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Penugasan -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-indigo-200 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Menunggu Penugasan</h3>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($laporanMenungguPenugasan) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perbaikan Selesai -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-200 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Selesai Bulan Ini</h3>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($perbaikanSelesaiBulanIni) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Status Fasilitas Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Status Fasilitas</h3>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="h-72 relative">
                        <canvas id="fasilitasChart"></canvas>
                    </div>
                </div>

                <!-- Laporan per Bulan Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Tren Laporan 6 Bulan</h3>
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="h-72 relative">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Enhanced Bottom Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Reports -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Laporan Terbaru</h3>
                        <a href="{{ url('/sarpra/laporan-kerusakan-fasilitas') }}" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full">
                            <thead class="bg-base-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fasilitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($laporanTerbaru as $index => $laporan)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $laporan->pelaporan_kode }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $laporan->fasilitas->barang->barang_nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $laporan->user->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $laporan->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-gray-500 font-medium">Tidak ada laporan terbaru</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Statistik Cepat</h3>

                    <!-- Enhanced Average Rating -->
                    <div class="mb-8 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl">
                        <p class="text-sm font-semibold text-gray-600 mb-3">Rating Kepuasan Pengguna</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($ratingRataRata, 1) }}</span>
                            <span class="text-lg text-gray-500">/5.0</span>
                        </div>
                        <div class="flex items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= round($ratingRataRata) ? 'text-yellow-400' : 'text-gray-300' }} transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-400 h-2 rounded-full transition-all duration-500" style="width: {{ ($ratingRataRata / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Enhanced Quick Stats List -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Dalam Proses Perbaikan</span>
                            </div>
                            <span class="text-sm font-bold text-blue-600">{{ $perbaikanProses }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Total Perbaikan</span>
                            </div>
                            <span class="text-sm font-bold text-green-600">{{ $totalPerbaikan }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Efisiensi</span>
                            </div>
                            <span class="text-sm font-bold text-purple-600">{{ $totalPerbaikan > 0 ? number_format(($perbaikanSelesaiBulanIni / $totalPerbaikan) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('skrip')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enhanced Fasilitas Status Chart
    const fasilitasCtx = document.getElementById('fasilitasChart').getContext('2d');
    const fasilitasData = @json($fasilitasPerStatus);

    new Chart(fasilitasCtx, {
        type: 'doughnut',
        data: {
            labels: fasilitasData.map(item => item.fasilitas_status || 'Tidak Diketahui'),
            datasets: [{
            data: fasilitasData.map(item => item.total),
            backgroundColor: [
                'rgba(16, 185, 129, 0.9)', // Green
                'rgba(239, 68, 68, 0.9)',  // Red
                'rgba(245, 158, 11, 0.9)', // Orange
                'rgba(139, 92, 246, 0.9)', // Purple
            ],
            borderColor: [
                'rgb(16, 185, 129)', // Green
                'rgb(239, 68, 68)',  // Red
                'rgb(245, 158, 11)', // Orange
                'rgb(139, 92, 246)', // Purple
            ],
            borderWidth: 3,
            hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000
            }
        }
    });

    // Enhanced Laporan per Bulan Chart
    const laporanCtx = document.getElementById('laporanChart').getContext('2d');
    const laporanData = @json($laporanPerBulan);
    const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

    const gradient = laporanCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

    new Chart(laporanCtx, {
        type: 'line',
        data: {
            labels: laporanData.map(item => `${bulanNames[item.bulan - 1]} ${item.tahun}`),
            datasets: [{
                label: 'Jumlah Laporan',
                data: laporanData.map(item => item.total),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: 'white',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: 'rgb(29, 78, 216)',
                pointHoverBorderColor: 'white',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: '600'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            weight: '600'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    displayColors: false
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Add loading animation
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.group');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush
