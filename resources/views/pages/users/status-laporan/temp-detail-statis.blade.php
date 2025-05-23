@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">
        <div class="flex justify-start pb-4">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-sm font-medium text-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <div class="overflow-x-auto">
            <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 text-base-content">
                <table class="table w-full text-sm table-fixed">
                    <colgroup>
                        <col class="w-1/4">
                        <col class="w-3/4">
                    </colgroup>
                    <tbody>
                    <tr class="bg-white border-b">
                        <th class="text-left font-semibold text-base-content px-4 py-3">Kode Laporan</th>
                        <td class="px-4 py-3">LP20250523</td>
                    </tr>
                    <tr class="bg-base-200 border-b">
                        <th class="text-left font-semibold text-base-content px-4 py-3">Laporan</th>
                        <td class="px-4 py-3">Lampu jalan mati di depan gedung rektorat.</td>
                    </tr>
                    <tr class="bg-white border-b">
                        <th class="text-left font-semibold text-base-content px-4 py-3">Tanggal</th>
                        <td class="px-4 py-3">23 Mei 2025</td>
                    </tr>
                    <tr class="bg-base-200 border-b">
                        <th class="text-left font-semibold text-base-content px-4 py-3">Status</th>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                <i class="bi bi-hourglass"></i>
                                <span class="text-center">Menunggu</span>
                            </span>
                        </td>
                    </tr>
                    <tr class="bg-white">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">Gambar (Menunggu)</th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm" style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-white">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">Gambar (Diperbaiki)</th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm" style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-white">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">Gambar (Selesai)</th>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex space-x-3 overflow-x-auto py-1" style="max-width: 100%;">
                                @for ($i = 1; $i <= 3; $i++)
                                    <div class="flex items-center justify-center bg-gray-100 border border-gray-300 rounded shadow-sm" style="height: 100px; width: 100px; min-width: 100px;">
                                        <i class="bi bi-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
