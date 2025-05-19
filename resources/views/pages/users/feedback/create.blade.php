@extends('layouts.main')
@section('judul', 'Beri Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Beri Umpan Balik</h1>

            <!-- Informasi Laporan (Statis) -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-lg mb-3 text-gray-700">Laporan yang Ditangani</h3>
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    <img src="https://via.placeholder.com/150" alt="Foto kerusakan"
                        class="w-24 h-24 object-cover rounded-md shadow">
                    <div class="grid grid-cols-1 gap-2">
                        <p><span class="font-medium">Judul:</span> Kerusakan AC Ruang 301</p>
                        <p><span class="font-medium">Lokasi:</span> Gedung A Lantai 3</p>
                        <p class="mt-5"><span class="font-thin-">Tanggal Lapor:</span> 10 Mei 2024</p>
                        <p><span class="font-medium">Ditangani pada:</span> 15 Mei 2024</p>
                    </div>
                </div>
            </div>

            <!-- Form Umpan Balik -->
            <form action="{{ route('feedback-store') }}" method="POST">
                @csrf
                <input type="hidden" name="report_id" value="1"> <!-- ID statis -->

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-3">Rating Kepuasan</label>
                    <div class="flex items-center gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer transform hover:scale-110 transition">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer"
                                    {{ $i == 3 ? 'checked' : '' }}>
                                <span class="text-3xl peer-checked:text-yellow-500 text-gray-300">★</span>
                            </label>
                        @endfor
                        <span class="ml-2 text-gray-600 text-sm">(1 = Buruk, 5 = Sangat Puas)</span>
                    </div>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Komentar -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-3">Komentar</label>
                    <textarea name="comment" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Bagaimana penanganan kerusakan ini?"></textarea>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.feedback') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Kirim Umpan Balik
                    </button>
                </div>
            </form>

            {{-- 
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
    <h3 class="font-semibold text-lg mb-3 text-gray-700">Laporan yang Ditangani</h3>
    <div class="flex flex-col md:flex-row gap-4 items-start">
        <!-- Foto dinamis -->
        <img src="{{ asset($report->photo ? 'storage/'.$report->photo : 'https://via.placeholder.com/150') }}" 
             alt="Foto kerusakan" 
             class="w-24 h-24 object-cover rounded-md shadow">
        
        <div class="grid grid-cols-1 gap-2">
            <p><span class="font-medium">Judul:</span> {{ $report->judul }}</p>
            <p><span class="font-medium">Lokasi:</span> {{ $report->lokasi }}</p>
            <p><span class="font-medium">Tanggal Lapor:</span> {{ $report->created_at->format('d F Y') }}</p>
            <p><span class="font-medium">Ditangani pada:</span> {{ $tanggal_ditangani }}</p>
            
            <!-- Jika ada petugas penangan -->
            @if ($report->handler)
            <p><span class="font-medium">Ditangani oleh:</span> {{ $report->handler->name }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Form Umpan Balik -->
<form action="{{ route('feedback.store') }}" method="POST">
    @csrf
    <input type="hidden" name="report_id" value="{{ $report->id }}">
    
    <!-- Rating -->
    <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-3">Rating Kepuasan</label>
        <div class="flex items-center gap-1">
            @for ($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer transform hover:scale-110 transition">
                    <input type="radio" name="rating" value="{{ $i }}" 
                           class="hidden peer" 
                           {{ old('rating', 3) == $i ? 'checked' : '' }}> <!-- Pertahankan input sebelumnya -->
                    <span class="text-3xl peer-checked:text-yellow-500 text-gray-300">★</span>
                </label>
            @endfor
            <span class="ml-2 text-gray-600 text-sm">(1 = Buruk, 5 = Sangat Puas)</span>
        </div>
        @error('rating')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Komentar -->
    <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-3">Komentar</label>
        --}}
            </div>
        </div>
    </div>
@endsection
