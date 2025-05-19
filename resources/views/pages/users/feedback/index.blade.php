@extends('layouts.main')
@section('judul', 'Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header section with consistent spacing -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Umpan Balik</h1>
        </div>

        <!-- Feedback items container -->
        <div class="space-y-6">
            <!-- Feedback item card with better structure -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card header and content area -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Image with proper sizing constraints -->
                        <div class="w-full md:w-48 h-48 flex-shrink-0 relative">
                            <img src="{{ asset('storage/kucing.jpg') }}" alt="Foto kerusakan"
                                class="w-full h-full object-cover rounded-md shadow">
                            <!-- Status Badge -->
                            <div
                                class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                SELESAI
                            </div>
                        </div>

                        <!-- Content with better spacing and hierarchy -->
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="font-bold text-xl text-gray-800">LAMPU LOBY MATI</h3>
                                <p class="text-gray-600 flex items-center gap-2"><i class="bi bi-geo-alt"></i>Gd. AN Lt. 2
                                </p>
                                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2"><i
                                        class="bi bi-calendar2-minus"></i>Ditangani pada: 15 Mei 2025</p>

                                <h4 class="font-medium text-gray-700 mt-10 mb-2">Deskripsi Kerusakan:</h4>
                                <p class="text-gray-600">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quam,
                                    placeat. Enim laudantium eos asperiores suscipit.</p>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('feedback-create') }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    Beri Penilaian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card header and content area -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Image with proper sizing constraints -->
                        <div class="w-full md:w-48 h-48 flex-shrink-0 relative">
                            <img src="{{ asset('storage/kucing.jpg') }}" alt="Foto kerusakan"
                                class="w-full h-full object-cover rounded-md shadow">
                            <!-- Status Badge -->
                            <div
                                class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                SELESAI
                            </div>
                        </div>

                        <!-- Content with better spacing and hierarchy -->
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="font-bold text-xl text-gray-800">LAMPU LOBY MATI</h3>
                                <p class="text-gray-600 flex items-center gap-2"><i class="bi bi-geo-alt"></i>Gd. AN Lt. 2
                                </p>
                                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2"><i
                                        class="bi bi-calendar2-minus"></i>Ditangani pada: 15 Mei 2025</p>

                                <h4 class="font-medium text-gray-700 mt-10 mb-2">Deskripsi Kerusakan:</h4>
                                <p class="text-gray-600">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quam,
                                    placeat. Enim laudantium eos asperiores suscipit.</p>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('feedback-create') }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    Beri Penilaian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card header and content area -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Image with proper sizing constraints -->
                        <div class="w-full md:w-48 h-48 flex-shrink-0 relative">
                            <img src="{{ asset('storage/kucing.jpg') }}" alt="Foto kerusakan"
                                class="w-full h-full object-cover rounded-md shadow">
                            <!-- Status Badge -->
                            <div
                                class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                SELESAI
                            </div>
                        </div>

                        <!-- Content with better spacing and hierarchy -->
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="font-bold text-xl text-gray-800">LAMPU LOBY MATI</h3>
                                <p class="text-gray-600 flex items-center gap-2"><i class="bi bi-geo-alt"></i>Gd. AN Lt. 2
                                </p>
                                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2"><i
                                        class="bi bi-calendar2-minus"></i>Ditangani pada: 15 Mei 2025</p>

                                <h4 class="font-medium text-gray-700 mt-10 mb-2">Deskripsi Kerusakan:</h4>
                                <p class="text-gray-600">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quam,
                                    placeat. Enim laudantium eos asperiores suscipit.</p>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('feedback-create') }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    Beri Penilaian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
