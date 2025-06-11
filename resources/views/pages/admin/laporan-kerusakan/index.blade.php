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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanKerusakan as $laporan)
                        <tr @if($loop->odd) style="background:#f5f8ff;" @endif>
                            <td>{{ $loop->iteration + ($laporanKerusakan->currentPage()-1)*$laporanKerusakan->perPage() }}</td>
                            <td>{{ $laporan->pelaporan_kode }}</td>
                            <td>{{ $laporan->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td>{{ $laporan->user->nama ?? '-' }}</td>
                            <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                            <td>
                                @php
                                    $status = optional($laporan->statusPelaporan->last())->status_pelaporan ?? 'Belum Diproses';
                                @endphp
                                @if($status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($status == 'proses')
                                    <span class="badge bg-warning text-dark">Proses</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diproses</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan-kerusakan.show', $laporan->pelaporan_id) }}" class="btn btn-outline-primary btn-sm">
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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    Menampilkan {{ $laporanKerusakan->firstItem() ?? 0 }} - {{ $laporanKerusakan->lastItem() ?? 0 }} dari {{ $laporanKerusakan->total() }} hasil
                </div>
                <div>
                    {{ $laporanKerusakan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection