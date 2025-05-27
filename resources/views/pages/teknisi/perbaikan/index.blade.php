@extends('layouts.main')
@section('judul', 'Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold mb-4">Perbaikan Fasilitas</h1>
        <div class="mb-4 flex items-center justify-between flex-wrap gap-2">
            <div class="relative w-full max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-500"></i>
                </span>
                <input type="text" placeholder="Cari Penugasan..."
                    class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400" />
            </div>

            <button class="btn btn-outline btn-primary h-10">
                <i class="bi bi-funnel me-2"></i>
                Filter
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content">
                    <tr class="">
                        <th class="text-center">No</th>
                        <th>Kode Perbaikan</th>
                        <th>Perbaikan</th>
                        <th>Tanggal</th>
                        <th>Teknisi yang Ditugaskan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([['id' => 1, 'kode' => 'PRB-001', 'perbaikan' => 'Kerusakan AC di Gedung A', 'tanggal' => '12 Mei 2025', 'teknisi' => 'John Doe', 'status' => 'Menunggu'], ['id' => 2, 'kode' => 'PRB-002', 'perbaikan' => 'Lampu padam di koridor B', 'tanggal' => '10 Mei 2025', 'teknisi' => 'Jane Smith', 'status' => 'Diproses'], ['id' => 3, 'kode' => 'PRB-003', 'perbaikan' => 'Kursi patah di ruang kelas 103', 'tanggal' => '8 Mei 2025', 'teknisi' => 'Alice Johnson', 'status' => 'Selesai'], ['id' => 4, 'kode' => 'PRB-004', 'perbaikan' => 'Kebocoran pipa di laboratorium', 'tanggal' => '3 Mei 2025', 'teknisi' => 'Charlie Davis', 'status' => 'Selesai'], ['id' => 5, 'kode' => 'PRB-005', 'perbaikan' => 'Pintu tidak bisa dikunci di ruang rapat', 'tanggal' => '1 Mei 2025', 'teknisi' => 'Diana Evans', 'status' => 'Diproses']] as $perbaikan)
                        <tr>
                            <td class="text-center">{{ $perbaikan['id'] }}</td>
                            <td class="text-start">{{ $perbaikan['kode'] }}</td>
                            <td>{{ $perbaikan['perbaikan'] }}</td>
                            <td class="">{{ $perbaikan['tanggal'] }}</td>
                            <td class="">
                                <span class="px-3 py-1 rounded-full">
                                    {{ $perbaikan['teknisi'] }}
                                </span>
                            </td>
                            <td class="">
                                @php
                                    $warnaBadge = match ($perbaikan['status']) {
                                        'Menunggu' => 'bg-yellow-400',
                                        'Diproses' => 'bg-blue-500',
                                        'Selesai' => 'bg-green-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp

                                <span class="badge text-white px-3 py-1 rounded-full {{ $warnaBadge }}">
                                    {{ $perbaikan['status'] }}
                                </span>
                            </td>
                            <td class="text-center flex items-center justify-center gap-4">
                                <a href="{{route ('detail-perbaikan')}}" class="flex items-center gap-1 justify-center">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between flex-wrap gap-2">
            <p class="text-sm">Menampilkan 5 dari 5 Penugasan</p>
            <div class="join">
                <button class="join-item btn btn-sm">«</button>
                <button class="join-item btn btn-sm btn-active">1</button>
                <button class="join-item btn btn-sm">2</button>
                <button class="join-item btn btn-sm">»</button>
            </div>
        </div>
    </div>
@endsection
