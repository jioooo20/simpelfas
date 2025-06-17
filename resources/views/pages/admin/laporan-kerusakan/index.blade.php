@extends('layouts.main')
@section('judul', 'Laporan Kerusakan')

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="bg-base-100 shadow-md border rounded-xl mb-3">
        <div class="flex border-b">
            <div class="px-6 py-4 font-semibold border-b-2 border-primary text-primary bg-gray-100 rounded-t-lg flex items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Laporan Kerusakan</span>
            </div>
        </div>
        <div class="p-6">
            <div class="table-responsive">
                <table class="table align-middle min-w-full">
                    <thead style="background: #f5f8ff;">
                        <tr>
                            <th>No</th>
                            <th>Kode Laporan</th>
                            <th>Fasilitas</th>
                            <th>Pelapor</th>
                            <th>Tanggal Lapor</th>
                            <th>Status Tindak Lanjut</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanKerusakan as $laporan)
                        <tr @if($loop->even) style="background:#f5f8ff;" @endif>
                            <td>{{ $loop->iteration + ($laporanKerusakan->currentPage()-1)*$laporanKerusakan->perPage() }}</td>
                            <td>{{ $laporan->pelaporan_kode }}</td>
                            <td>{{ $laporan->fasilitas->fasilitas_kode ?? '-' }}</td>
                            <td>{{ $laporan->user->nama ?? '-' }}</td>
                            <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                            <td>
                                @php
                                    $status = optional($laporan->statusPelaporan->last())->status_pelaporan ?? 'Belum Diproses';
                                @endphp
                                @if($status == 'selesai')
                                    <span class="badge" style="background:#C1E1C1;">Selesai</span>
                                @elseif($status == 'proses')
                                    <span class="badge" style="background:#fdfd96;">Proses</span>
                                @else
                                    <span class="badge" style="background:#FAA0A0;">Belum Diproses</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.laporan-kerusakan.show', $laporan->pelaporan_id) }}" class="btn btn-outline-primary btn-sm border-gray-200">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada laporan kerusakan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @php
                $currentPage = $laporanKerusakan->currentPage();
                $lastPage = $laporanKerusakan->lastPage();
            @endphp

            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $laporanKerusakan->firstItem() }} - {{ $laporanKerusakan->lastItem() }} dari {{ $laporanKerusakan->total() }} hasil
                </div>

                <div class="flex space-x-1 px-2 py-1 bg-gray-100 rounded-lg">
                    {{-- Page numbers --}}
                    @for ($i = 1; $i <= $lastPage; $i++)
                        @if ($i == $currentPage)
                            <span class="px-3 py-1 text-sm font-semibold text-blue-600 bg-white rounded-md">{{ $i }}</span>
                        @else
                            <a href="{{ $laporanKerusakan->url($i) }}"
                            class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-200 rounded-md">{{ $i }}</a>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection