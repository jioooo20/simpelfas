@extends('layouts.main')
@section('judul', 'Statistik Fasilitas')
@section('content')
    <div class="p-6 bg-white min-h-screen">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 mb-1">Statistik Fasilitas</h1>
                <p class="text-gray-500">Analisis dan monitoring kerusakan fasilitas</p>
            </div>
            <div class="flex items-center space-x-2">
                <select
                    class="border border-gray-300 bg-white rounded px-3 py-2 text-sm text-gray-700 appearance-none pr-8">
                    <option>2023</option>
                    <option>2022</option>
                </select>
                <button class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-500 transition">
                    Export
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <!-- Total Laporan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Total Laporan</p>
                <h2 class="text-2xl font-semibold text-gray-800">1,247</h2>
                <p class="text-sm text-emerald-500">+12% dari bulan lalu</p>
            </div>

            <!-- Pending -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Pending</p>
                <h2 class="text-2xl font-semibold text-yellow-500">89</h2>
                <p class="text-sm text-gray-400">Menunggu tindakan</p>
            </div>

            <!-- Dalam Proses -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Dalam Proses</p>
                <h2 class="text-2xl font-semibold text-sky-500">60</h2>
                <p class="text-sm text-gray-400">Sedang dikerjakan</p>
            </div>

            <!-- Selesai -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Selesai</p>
                <h2 class="text-2xl font-semibold text-emerald-600">1,098</h2>
                <p class="text-sm text-gray-400">88% tingkat penyelesaian</p>
            </div>

            <!-- Rata-rata Waktu -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Rata-rata Waktu</p>
                <h2 class="text-2xl font-semibold text-gray-700">3.2 hari</h2>
                <p class="text-sm text-gray-400">Waktu penyelesaian</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <button class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-500 transition">Tren Laporan</button>
            <button
                class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded hover:bg-gray-100 transition">Per
                Fasilitas
            </button>
            <button
                class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded hover:bg-gray-100 transition">
                Status Distribution
            </button>
        </div>

        <!-- Tempat grafik tren laporan nantinya -->
        <div class="mt-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Tren Laporan</h2>
            <div class="h-64 flex items-center justify-center text-gray-400 italic">
                [Grafik akan muncul di sini...]
            </div>
        </div>

        <!-- Container Laporan Terbaru -->
        <div class="mt-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Laporan Terbaru</h2>
            <p class="text-gray-500 mb-4">Daftar laporan kerusakan fasilitas terbaru</p>

            @php
                $laporanTerbaru = [
                    [
                        'id' => 'RPT-2024-001',
                        'status' => 'Pending',
                        'judul' => 'Kerusakan AC di ruang kelas 201',
                        'lokasi' => 'Gedung A - Lantai 2',
                        'pengirim' => 'Ahmad Rizki',
                        'tanggal' => '2024-01-15',
                        'warna' => 'bg-yellow-100 text-yellow-800'
                    ],
                    [
                        'id' => 'RPT-2024-002',
                        'status' => 'Dalam Proses',
                        'judul' => 'Proyektor tidak berfungsi',
                        'lokasi' => 'Laboratorium Komputer',
                        'pengirim' => 'Siti Nurhaliza',
                        'tanggal' => '2024-01-14',
                        'warna' => 'bg-sky-100 text-sky-800'
                    ],
                    [
                        'id' => 'RPT-2024-003',
                        'status' => 'Selesai',
                        'judul' => 'Lampu mati di area baca',
                        'lokasi' => 'Perpustakaan',
                        'pengirim' => 'Budi Santoso',
                        'tanggal' => '2024-01-13',
                        'warna' => 'bg-emerald-100 text-emerald-800'
                    ],
                ];
            @endphp

            <div class="space-y-4">
                @foreach($laporanTerbaru as $laporan)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-sky-700 font-medium mb-1">
                                {{ $laporan['id'] }}
                                <span
                                    class="ml-2 px-2 py-0.5 text-xs rounded-full font-semibold {{ $laporan['warna'] }}">
                            {{ $laporan['status'] }}
                        </span>
                            </p>
                            <h3 class="text-base font-semibold text-gray-800">{{ $laporan['judul'] }}</h3>
                            <div class="text-sm text-gray-500 mt-1 flex flex-wrap gap-4">
                                <div class="flex items-center space-x-1">
                                    <i class="fa fa-building"></i>
                                    <span>{{ $laporan['lokasi'] }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fa fa-user"></i>
                                    <span>{{ $laporan['pengirim'] }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fa fa-calendar"></i>
                                    <span>{{ $laporan['tanggal'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="text-sky-600 hover:underline text-sm">Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
