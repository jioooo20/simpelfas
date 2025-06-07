@extends('layouts.main')
@section('judul', 'Beri Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Beri Umpan Balik</h1>

            {{-- Dinamis --}}
            <!-- Informasi Laporan (Dinamis) -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-lg mb-3 text-gray-700">Laporan yang Ditangani</h3>
                <div class="flex flex-col md:flex-row gap-4 items-start">


                    
                    @php
                        $gambarList = json_decode($laporan->pelaporan_gambar, true);
                        $gambarUtama = $gambarList[0] ?? null;
                    @endphp
                    <img src="{{ $gambarUtama ? asset('storage/' . $gambarUtama) : asset('images/no-image.png') }}"
                        alt="{{ $laporan->fasilitas->nama ?? 'Foto kerusakan' }}"
                        class="w-24 h-24 object-cover rounded-md shadow">

                    <div class="grid grid-cols-1 gap-2">
                        <p><span class="font-medium">Perbaikan:</span> 
                            {{ $laporan->fasilitas->barang->barang_nama ?? '-' }} 
                            ({{ $laporan->fasilitas->ruang->lantai->gedung->gedung_nama ?? '-' }} - 
                             {{ $laporan->fasilitas->ruang->lantai->lantai_nama?? '-' }} -
                             {{ $laporan->fasilitas->ruang->ruang_nama ?? '-' }})
                        </p>
                        
@php
        $statusSelesai = $laporan->statusPelaporan->where('status_pelaporan', 'SELESAI')->first();
        $tanggalDitangani = $statusSelesai ? $statusSelesai->created_at : ($laporan->tanggal_ditangani ?? $laporan->updated_at);
    @endphp

    <p class="mt-5"><span class="font-medium">Tanggal Lapor:</span> 
        {{ \Carbon\Carbon::parse($laporan->pelaporan_tanggal)->format('d M Y') }}
    </p>
    <p><span class="font-medium">Ditangani pada:</span> 
        {{ \Carbon\Carbon::parse($tanggalDitangani)->format('d M Y') }}
    </p>
                        
                    </div>
                </div>
            </div>

            <!-- Form Umpan Balik -->
            <form action="{{ route('feedback-store') }}" method="POST">
                @csrf
                <input type="hidden" name="report_id" value="{{ $laporan->pelaporan_id }}">
                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-3">Rating Kepuasan</label>
                    <div x-data="{ rating: 0 }" class="flex flex-col items-start space-y-2">
                        <div class='flex space-x-1'>
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer transform hover:scale-110 transition">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                        x-model="rating">
                                    <span class="text-3xl":class="rating >= {{ $i }} ? 'text-yellow-500' : 'text-gray-300'">★</span>
                                </label>
                            @endfor
                        </div>
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
        </div>
    </div>
    </div>
@endsection
