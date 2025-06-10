@extends('layouts.main')
@section('judul', 'Dasbor Pelaporan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="bi bi-journal-text text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900">{{$laporan_total}}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="bi bi-hourglass-split text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-2xl font-bold text-gray-900">{{$status_laporan['Menunggu'] ?? 0}}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="bi bi-check-square text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Diterima</p>
                        <p class="text-2xl font-bold text-gray-900">{{$status_laporan['Diterima'] ?? 0}}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="bi bi-tools text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Diproses</p>
                        <p class="text-2xl font-bold text-gray-900">{{$status_laporan['Diproses'] ?? 0}}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-2xl font-bold text-gray-900">{{$status_laporan['Selesai'] ?? 0}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                    <livewire:admin-dasbor-chart>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-full">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 font-['Montserrat']">Aksi Cepat</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <button class="p-4 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                            onclick="window.location.href='{{ route('admin.user') }}'">
                            <i class="bi bi-people text-blue-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-900 font-['Open_Sans']">Kelola Pengguna</p>
                        </button>

                        <button class="p-4 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                            onclick="window.location.href='{{ route('admin.gedung') }}'">
                            <i class="bi bi-building text-purple-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-900 font-['Open_Sans']">Manajemen Gedung</p>
                        </button>

                        <button class="p-4 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                            onclick="window.location.href='{{ route('admin.fasilitas') }}'">
                            <i class="bi bi-gear-wide-connected text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-900 font-['Open_Sans']">Manajemen Fasilitas</p>
                        </button>

                        <button class="p-4 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                            onclick="window.location.href='#'">
                            <i class="bi bi-graph-up text-orange-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-900 font-['Open_Sans']">Laporan dan Statistik</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
