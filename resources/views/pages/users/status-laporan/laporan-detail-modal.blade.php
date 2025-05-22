@extends('layouts.main')
@section('judul', 'Detail Laporan')
@section('content')
    <div class="p-4">

        <div class="overflow-x-auto">
            <div class="rounded-xl shadow-md border border-gray-200 bg-base-100 text-base-content">
                <table class="table w-full text-sm">
                    <tbody>
                    @php
                        $rows = [
                            ['label' => 'Kode Laporan', 'value' => $laporan->pelaporan_kode],
                            ['label' => 'Laporan', 'value' => $laporan->pelaporan_deskripsi],
                            ['label' => 'Tanggal', 'value' => $laporan->created_at->format('d M Y')],
                            ['label' => 'Status', 'value' => $status],
                        ];
                    @endphp

                    @foreach ($rows as $i => $row)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-base-200' }} border-b">
                            <th class="text-left whitespace-nowrap w-max font-semibold text-base-content px-4 py-3">{{ $row['label'] }}</th>
                            <td class="px-4 py-3">
                                @if ($row['label'] === 'Status')
                                    @php
                                        $badgeClasses = [
                                            'Menunggu' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                            'Diproses' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'Selesai' => 'bg-green-100 text-green-800 border border-green-200',
                                            'Ditolak' => 'bg-red-100 text-red-800 border border-red-200',
                                        ];
                                        $badgeIcons = [
                                            'Menunggu' => '<i class="bi bi-hourglass"></i>',
                                            'Diproses' => '<i class="bi bi-gear"></i>',
                                            'Selesai' => '<i class="bi bi-check-circle"></i>',
                                            'Ditolak' => '<i class="bi bi-x-circle"></i>',
                                        ];
                                        $badgeStyle = $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
                                        $badgeIcon = $badgeIcons[$status] ?? '';
                                    @endphp

                                    <span class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium {!! $badgeStyle !!}">
                                    {!! $badgeIcon !!}
                                    <span class="text-center">{{ $status }}</span>
                                </span>
                                @else
                                    {{ $row['value'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @if ($laporan->pelaporan_gambar)
                        <tr class="{{ count($rows) % 2 === 0 ? 'bg-white' : 'bg-base-200' }}">
                            <th class="text-left font-semibold text-base-content px-4 py-3 align-top rounded-bl-xl">Gambar</th>
                            <td class="px-4 py-3 align-top">
                                <img src="{{ asset('storage/' . $laporan->pelaporan_gambar) }}"
                                     alt="Gambar Laporan"
                                     class="rounded-lg shadow-sm max-h-[300px] w-auto object-contain border border-gray-300">
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
