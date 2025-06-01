@extends('layouts.main')
@section('judul', 'Laporan & Statistik Sistem')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-base-content text-center md:text-left">Laporan & Statistik Sistem</h1>
            <div class="flex gap-3">
                <button class="bg-green-500 text-white btn btn-outline btn-sm flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>Impor Data Laporan
                </button>
            </div>
        </div>

        {{-- kotak --}}
        <div class="bg-base-100 shadow-md border rounded-xl p-6">
            {{-- table --}}
            <div class="overflow-x-auto">
                <livewire:laporan-statistik />
        </div>
    </div>
@endsection

{{-- @extends('layouts.main')
@section('judul', 'Laporan dan Statistik Sistem')
@section('content')
    <div class="p-4">
        <h2 class="text-xl font-bold mb-4">Laporan & Statistik Sistem</h2>

        <div class="overflow-x-auto">
            <table class="table w-full border">
                <thead>
                    <tr class="bg-base-200">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['judul'] }}</td>
                            <td>{{ $data['status'] }}</td>
                            <td>{{ $data['tanggal'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection --}}
