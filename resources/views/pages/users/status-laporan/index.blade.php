@extends('layouts.main')
@section('judul', 'Status Laporan')
@section('content')
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Status Laporan</h1>

        {{-- Container utama: Search, Filter, Table --}}
        <div class="bg-white shadow rounded-lg p-4 space-y-4">

            {{-- Search dan Filter --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                {{-- Search Bar diperpanjang --}}
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-500"></i>
                    </span>
                    <input type="text" placeholder="Cari laporan..."
                           class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400"/>
                </div>

                {{-- Dropdown Filter Status --}}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-outline btn-primary h-10">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 mt-2">
                        <li><a href="#">Menunggu</a></li>
                        <li><a href="#">Diproses</a></li>
                        <li><a href="#">Selesai</a></li>
                        <li><a href="#">Ditolak</a></li>
                    </ul>
                </div>
            </div>


            {{-- Tabel --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200 text-base-content">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach([
                        ['id' => 1, 'judul' => 'Kerusakan AC di Gedung A', 'tanggal' => '12 Mei 2025', 'status' => 'Menunggu'],
                        ['id' => 2, 'judul' => 'Lampu padam di koridor B', 'tanggal' => '10 Mei 2025', 'status' => 'Diproses'],
                        ['id' => 3, 'judul' => 'Kursi patah di ruang kelas 103', 'tanggal' => '8 Mei 2025', 'status' => 'Selesai'],
                        ['id' => 4, 'judul' => 'Toilet rusak di lantai 2', 'tanggal' => '5 Mei 2025', 'status' => 'Ditolak'],
                        ['id' => 5, 'judul' => 'Kebocoran pipa di laboratorium', 'tanggal' => '3 Mei 2025', 'status' => 'Selesai'],
                        ['id' => 6, 'judul' => 'Pintu tidak bisa dikunci di ruang rapat', 'tanggal' => '1 Mei 2025', 'status' => 'Diproses'],
                    ] as $laporan)
                        @php
                            $badgeStyle = match($laporan['status']) {
                                'Menunggu' => [
                                    'bg' => 'bg-amber-100',
                                    'text' => 'text-amber-800',
                                    'border' => 'border border-amber-200',
                                    'icon' => '<i class="bi bi-hourglass"></i>',
                                ],
                                'Diproses' => [
                                    'bg' => 'bg-blue-100',
                                    'text' => 'text-blue-800',
                                    'border' => 'border border-blue-200',
                                    'icon' => '<i class="bi bi-gear"></i>',
                                ],
                                'Selesai' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'border' => 'border border-green-200',
                                    'icon' => '<i class="bi bi-check-circle"></i>',
                                ],
                                'Ditolak' => [
                                    'bg' => 'bg-red-100',
                                    'text' => 'text-red-800',
                                    'border' => 'border border-red-200',
                                    'icon' => '<i class="bi bi-x-circle"></i>',
                                ],
                                default => [
                                    'bg' => 'bg-gray-100',
                                    'text' => 'text-gray-800',
                                    'border' => 'border border-gray-200',
                                    'icon' => '',
                                ],
                            };
                        @endphp
                        <tr>
                            <td class="text-center">{{ $laporan['id'] }}</td>
                            <td>{{ $laporan['judul'] }}</td>
                            <td class="text-center">{{ $laporan['tanggal'] }}</td>
                            <td class="text-center">
                                <span class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium {{ $badgeStyle['bg'] }} {{ $badgeStyle['text'] }} {{ $badgeStyle['border'] }}">
                                    <span class="text-sm opacity-80 leading-none">{!! $badgeStyle['icon'] !!}</span>
                                    <span class="text-center">{{ $laporan['status'] }}</span>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#"
                                   class="btn btn-sm btn-outline btn-primary flex items-center gap-1 justify-center">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex items-center justify-between flex-wrap gap-2">
            <p class="text-sm">Menampilkan 6 dari 6 laporan</p>
            <div class="join">
                <button class="join-item btn btn-sm">«</button>
                <button class="join-item btn btn-sm btn-active">1</button>
                <button class="join-item btn btn-sm">2</button>
                <button class="join-item btn btn-sm">»</button>
            </div>
        </div>
    </div>
@endsection
