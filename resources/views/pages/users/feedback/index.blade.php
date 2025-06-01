@extends('layouts.main')
@section('judul', 'Umpan Balik')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Umpan Balik</h1>
        </div>

        <div class="space-y-6">
            @forelse ($perbaikan as $items)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Gambar -->
                            <div class="w-full md:w-48 h-48 flex-shrink-0 relative">
                                <img src="{{ asset('storage/' . $items->pelaporan_gambar) }}" alt="Foto kerusakan"
                                    class="w-full h-full object-cover rounded-md shadow">

                                <!-- Status badge -->
                                @php
                                    $statusHistory = \App\Models\StatusPelaporanModel::where('pelaporan_id', $items->pelaporan_id)
                                                    ->orderBy('created_at')
                                                    ->get();

                                    $statusTerakhir = $statusHistory->last();
                                    $status = $statusTerakhir ? $statusTerakhir->status_pelaporan : 'MENUNGGU';
                                @endphp

                                <div class="absolute top-2 left-2
                                    {{ $status == 'SELESAI' ? 'bg-green-600' : 'bg-yellow-500' }}
                                    text-white text-xs font-bold px-2 py-1 rounded-md shadow-md">
                                    {{ strtoupper($status) }}
                                </div>
                            </div>

                            <!-- Konten -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <h3 class="font-bold text-xl text-gray-800">
                                        Kode: {{ strtoupper($items->pelaporan_kode) }}
                                    </h3>
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="bi bi-building"></i>
                                        Fasilitas: {{ $items['fasilitas_label'] ?? '-' }}
                                    </p>

                                    <h4 class="font-medium text-gray-700 mt-6 mb-2">Deskripsi Kerusakan:</h4>
                                    <p class="text-gray-600">{{ $items->pelaporan_deskripsi }}</p>
                                </div>

                                <!-- Tombol Penilaian -->
                                <div class="flex justify-end">
                                    <a href="{{ route('feedback-create', ['perbaikan_id' => $items->pelaporan_id]) }}"
                                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium text-sm transition duration-150 ease-in-out">
                                        Beri Penilaian
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">Belum ada pelaporan.</p>
            @endforelse
        </div>
    </div>
@endsection
