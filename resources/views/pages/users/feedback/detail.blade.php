@extends('layouts.main')
@section('judul', 'Detail Penilaian')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Detail Penilaian</h1>
            <a href="{{ route('users.feedback') }}" class="text-blue-600 hover:text-blue-800">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Informasi Pelaporan</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Pelaporan</p>
                            <p class="font-medium">{{ strtoupper($feedback->pelaporan->pelaporan_kode) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Deskripsi Kerusakan</p>
                            <p class="font-medium">{{ $feedback->pelaporan->pelaporan_deskripsi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pelaporan</p>
                            <p class="font-medium">{{ $feedback->pelaporan->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Detail Penilaian</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Rating</p>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="bi bi-star-fill text-yellow-400"></i>
                                    @else
                                        <i class="bi bi-star text-gray-300"></i>
                                    @endif
                                @endfor
                                <span class="ml-2 font-medium">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Komentar</p>
                            <p class="font-medium">{{ $feedback->komentar }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Penilaian</p>
                            <p class="font-medium">{{ $feedback->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection