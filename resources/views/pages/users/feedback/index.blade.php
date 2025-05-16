@extends('layouts.main')
@section('judul', 'Kelola Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Umpan Balik</h1>
        </div>

        <div class="flex flex-col gap-4 w-full">

            
    <div class="flex flex-col md:flex-row bg-blue-50 rounded-lg shadow p-4 justify-between items-start relative">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Foto kerusakan -->
            <img src="" alt="Foto kerusakan"
                 class="w-32 h-32 object-cover rounded shadow">

            <!-- Detail laporan -->
            <div>
                <h3 class="font-bold text-lg uppercase text-gray-800">Judul</h3>
                <p class="text-sm text-gray-600">Lokasi</p>
            </div>
        </div>

        <!-- Tombol Penilaian -->
        <div class="absolute bottom-4 right-4">
            <a href=""
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded shadow">
                Beri Penilaian
            </a>
        </div>
    </div>
    <div class="flex flex-col md:flex-row bg-blue-50 rounded-lg shadow p-4 justify-between items-start relative">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Foto kerusakan -->
            <img src="" alt="Foto kerusakan"
                 class="w-32 h-32 object-cover rounded shadow">

            <!-- Detail laporan -->
            <div>
                <h3 class="font-bold text-lg uppercase text-gray-800">Judul</h3>
                <p class="text-sm text-gray-600">Lokasi</p>
            </div>
        </div>

        <!-- Tombol Penilaian -->
        <div class="absolute bottom-4 right-4">
            <a href=""
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded shadow">
                Beri Penilaian
            </a>
        </div>
    </div>
    <div class="flex flex-col md:flex-row bg-blue-50 rounded-lg shadow p-4 justify-between items-start relative">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Foto kerusakan -->
            <img src="" alt="Foto kerusakan"
                 class="w-32 h-32 object-cover rounded shadow">

            <!-- Detail laporan -->
            <div>
                <h3 class="font-bold text-lg uppercase text-gray-800">Judul</h3>
                <p class="text-sm text-gray-600">Lokasi</p>
            </div>
        </div>

        <!-- Tombol Penilaian -->
        <div class="absolute bottom-4 right-4">
            <a href=""
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded shadow">
                Beri Penilaian
            </a>
        </div>
    </div>


            
        </div>
    </div>
@endsection
