@extends('layouts.main')
@section('judul', 'Kelola Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Umpan Balik</h1>
        </div>

            {{-- @foreach ($completedReports as $report)
                    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow p-4 justify-between items-start relative mb-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <img src="{{ asset('storage/'.$report->photo) }}" alt="Foto kerusakan"
                                class="w-32 h-32 object-cover rounded shadow">
                            <div>
                                <h3 class="font-bold text-lg uppercase text-gray-800">{{ $report->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $report->location }}</p>
                                <p class="text-sm text-gray-500">Ditangani pada: {{ $report->completed_at->format('d M Y') }}</p>
                                
                                @if($report->description)
                                <div class="mt-2 p-3 bg-gray-50 rounded">
                                    <h4 class="font-semibold text-sm mb-1">Deskripsi Kerusakan:</h4>
                                    <p class="text-sm text-gray-700">{{ $report->description }}</p>
                                </div>
                                @endif
                                
                                <p class="text-sm mt-2">
                                    <span class="font-semibold">Status:</span> 
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $report->status }}</span>
                                </p>
                                
                                @if($report->handling_team)
                                <p class="text-sm mt-1">
                                    <span class="font-semibold">Tim Penanganan:</span> {{ $report->handling_team }}
                                </p>
                                @endif
                                
                                @if($report->duration)
                                <p class="text-sm mt-1">
                                    <span class="font-semibold">Durasi Penanganan:</span> {{ $report->duration }} hari kerja
                                </p>
                                @endif
                                
                                @if($report->repair_details)
                                <p class="text-sm mt-1">
                                    <span class="font-semibold">Detail Perbaikan:</span> {{ $report->repair_details }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <a href="{{ route('feedback.create', $report->id) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded shadow transition duration-200">
                                Beri Penilaian
                            </a>
                        </div>
                    </div>
                @endforeach 
            --}}

        {{-- START --}}
        <div class="flex flex-col gap-4 w-full">        
            <div class="flex flex-col md:flex-row bg-white rounded-lg shadow p-4 justify-between items-start relative mb-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <img src="/placeholder-damage.jpg" alt="Foto kerusakan"
                        class="w-42 h-42 object-cover rounded shadow">
                            <div>
                                <h3 class="font-bold text-lg uppercase text-gray-800">LAMPU LOBY MATI</h3>
                                <p class="text-sm text-gray-600">Gd. AN Lt. 2</p>
                                <p class="text-sm text-gray-500">Ditangani pada: 15 Mei 2025</p>
                                
                                <div class="mt-2 p-3 bg-gray-50 rounded">
                                    <h4 class="font-semibold text-sm mb-1">Deskripsi Kerusakan:</h4>
                                    <p class="text-sm text-gray-700">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quam, placeat. Enim laudantium eos asperiores suscipit.</p>
                                </div>
                                
                                <p class="text-sm mt-2">
                                    <span class="font-semibold">Status:</span> 
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Selesai diperbaiki</span>
                                </p>
                                <p class="text-sm mt-1">
                                    <span class="font-semibold">Durasi Penanganan:</span> 7 hari kerja
                                </p>
                            </div>
                </div>
                <div class="absolute bottom-4 right-4">
                    <a href="{{ route('feedback-create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm rounded shadow transition duration-200">
                        Beri Penilaian
                    </a>
                </div>
            </div>
        </div>
        {{-- END --}}
    </div>
@endsection
