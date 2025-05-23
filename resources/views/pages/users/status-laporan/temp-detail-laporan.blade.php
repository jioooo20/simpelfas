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
                <table class="table w-full text-sm ">
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

                                    <span
                                        class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium {!! $badgeStyle !!}">
                                    {!! $badgeIcon !!}
                                    <span class="text-center">{{ $status }}</span>
                                </span>
                                @else
                                    {{ $row['value'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($laporan->pelaporan_gambar && is_array(json_decode($laporan->pelaporan_gambar, true)) && count(json_decode($laporan->pelaporan_gambar, true)) > 0)
                        @php
                            $gambarArray = json_decode($laporan->pelaporan_gambar, true);
                        @endphp
                        <tr class="{{ count($rows) % 2 === 0 ? 'bg-white' : 'bg-base-200' }}">
                            <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                                Gambar
                            </th>
                            <td class="px-4 py-3 align-middle">
                                {{-- Wadah untuk gambar dengan scroll horizontal --}}
                                <div class="flex space-x-2 overflow-x-auto py-1" style="max-width: 100%;">
                                    @foreach ($gambarArray as $gambar)
                                        {{-- Tautan untuk membuka gambar lebih besar (misalnya dengan lightbox) --}}
                                        <a href="{{ asset('storage/' . $gambar) }}" data-fancybox="galleryLaporan" data-caption="Gambar Laporan {{ $loop->iteration }}">
                                            <img src="{{ asset('storage/' . $gambar) }}"
                                                 alt="Gambar Laporan {{ $loop->iteration }}"
                                                 class="rounded shadow-sm object-cover border border-gray-300"
                                                 style="height: 50px; width: auto; min-width: 50px; cursor:pointer;"> {{-- Tinggi tetap, lebar otomatis --}}
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr class="{{ count($rows) % 2 === 0 ? 'bg-white' : 'bg-base-200' }}">
                            <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                                Gambar
                            </th>
                            <td class="px-4 py-3 align-middle text-base-content text-sm">
                                <span class="italic text-gray-500">Tidak ada gambar yang dilampirkan.</span>
                            </td>
                        </tr>
                    @endif

                    <tr class="{{ count($rows) % 2 === 0 ? 'bg-white' : 'bg-base-200' }}">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                            Gambar
                        </th>
                        <td class="px-4 py-3 align-middle text-base-content text-sm">
                            <span class="italic text-gray-500">Tidak ada gambar yang dilampirkan.</span>
                        </td>
                    </tr>

                    <tr class="{{ count($rows) % 2 === 0 ? 'bg-white' : 'bg-base-200' }}">
                        <th class="text-left font-semibold text-base-content px-4 py-3 align-middle rounded-bl-xl">
                            Gambar
                        </th>
                        <td class="px-4 py-3 align-middle text-base-content text-sm">
                            <span class="italic text-gray-500">Tidak ada gambar yang dilampirkan.</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
