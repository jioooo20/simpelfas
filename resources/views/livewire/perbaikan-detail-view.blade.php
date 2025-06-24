<div>
    <div class="mb-6">
        <a href="{{ url()->previous() }}" {{-- Kembali ke halaman sebelumnya --}}
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-md flex flex-col">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Perbaikan
            </h2>
            <div class="p-6 space-y-4 flex-grow">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Status</p>
                    <p class="mt-1">
                        @if ($statusTerakhir->perbaikan_status == 'Menunggu')
                            <span class="badge badge-warning text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Diproses')
                            <span class="badge badge-primary text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Selesai')
                            <span class="badge badge-success text-white">{{ $statusTerakhir->perbaikan_status }}</span>
                        @else
                            <span class="badge badge-ghost">{{ $statusTerakhir->perbaikan_status ?? '-' }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Kode Perbaikan</p>
                    <p class="mt-1 font-mono text-slate-900">{{ $perbaikan->perbaikan_kode ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Tanggal Dibuat</p>
                    <p class="mt-1 text-slate-900">{{ $perbaikan->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Terakhir Update</p>
                    <p class="mt-1 text-slate-900">{{ ($statusTerakhir->created_at ?? $perbaikan->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="p-6 pt-0">
                @if ($statusTerakhir->perbaikan_status != 'Selesai')
                    <div class="card-actions justify-end pt-4 border-t">
                        @php
                            $isAssignedTechnician = false;
                            $userId = auth()->id();
                            if ($perbaikan->perbaikanPetugas && $perbaikan->perbaikanPetugas->count() > 0) {
                                foreach ($perbaikan->perbaikanPetugas as $petugas) {
                                    if ($petugas->user_id == $userId) {
                                        $isAssignedTechnician = true;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if ($isAssignedTechnician)
                            <button type="button" onclick="Livewire.dispatch('openUpdateModal')"
                                    class="btn btn-primary btn-sm text-white">Update Status
                            </button>
                        @else
                            <button type="button" disabled
                                    class="btn btn-primary btn-sm text-white opacity-50 cursor-not-allowed"
                                    title="Hanya teknisi yang ditugaskan yang dapat mengupdate status">Update Status
                            </button>
                        @endif
                    </div>
                    @livewire('perbaikan-update-form', ['perbaikanId' => $perbaikan->perbaikan_id],
                    key($perbaikan->perbaikan_id))
                @else
                    <div class="text-center pt-4 border-t">
                        <span class="text-gray-500 italic text-sm">Perbaikan telah selesai.</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Laporan
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Lokasi</p>
                    <p class="mt-1 text-slate-900">{{ $lokasi ? ($lokasi->gedung_nama ?? '') . ' - ' . ($lokasi->ruang_nama ?? '') : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Fasilitas</p>
                    <p class="mt-1 text-slate-900">{{ $fasilitas->nama_barang ?? ($fasilitas->fasilitas_kode ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Deskripsi Masalah</p>
                    <p class="mt-1 text-slate-900 break-words">{{ $perbaikan->pelaporan->pelaporan_deskripsi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Total Laporan Terkait</p>
                    <p class="mt-1 text-slate-900">{{ $totalPerbaikan ?? '-' }} laporan</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Bukti Foto</p>
                    <div class="mt-1">
                        @php
                            $prefix = preg_replace('/-\d+[A-Z]*$/i', '', $perbaikan->perbaikan_kode);
                            $perbaikanSama = \App\Models\PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->pluck('pelaporan_id');
                            $fotoPath = null;
                            if ($perbaikanSama->isNotEmpty()) {
                                $pelaporanFoto = \App\Models\PelaporanModel::whereIn('pelaporan_id', $perbaikanSama)->whereNotNull('pelaporan_gambar')->latest()->first();
                                if ($pelaporanFoto && $pelaporanFoto->pelaporan_gambar) {
                                    $fotoArr = json_decode($pelaporanFoto->pelaporan_gambar, true);
                                    if (is_array($fotoArr) && count($fotoArr) > 0) {
                                        $url = $fotoArr[0];
                                        $fotoPath = str_starts_with($url, 'storage/') ? asset($url) : asset('storage/' . $url);
                                    }
                                }
                            }
                        @endphp
                        @if ($fotoPath)
                            <button type="button" onclick="openImageModal('{{ $fotoPath }}', 'Bukti Laporan Awal')"
                                    class="btn btn-sm btn-outline btn-primary">
                                <i class="bi bi-image"></i> Lihat Foto
                            </button>
                        @else
                            <span class="text-gray-400 italic">Tidak ada foto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md">
            <h2 class="text-lg font-bold p-4 bg-slate-50 text-slate-800 rounded-t-xl border-b">
                Informasi Teknisi
            </h2>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Teknisi Bertugas</p>
                    @if ($perbaikan->perbaikanPetugas->isNotEmpty())
                        <ul class="list-disc list-inside mt-1 text-slate-900">
                            @foreach ($perbaikan->perbaikanPetugas as $petugas)
                                <li>{{ $petugas->user->nama ?? '-' }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-1 text-gray-400 italic">Belum ada teknisi ditugaskan</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500">Deskripsi Penanganan</p>
                    <p class="mt-1 text-slate-900 break-words">{{ $perbaikan->perbaikan_deskripsi ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 mb-10">
        <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>

        <div class="lg:hidden space-y-6">
            @forelse ($histori as $row)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        @if ($row->perbaikan_status == 'Selesai')
                            <div
                                    class="w-5 h-5 rounded-full bg-success flex items-center justify-center ring-4 ring-success/30">
                                <i class="bi bi-check-lg text-white text-xs"></i>
                            </div>
                        @else
                            <div class="w-4 h-4 mt-1 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        @endif
                        @if (!$loop->last)
                            <div class="w-px h-full bg-gray-200 mt-2"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-6">
                        @if ($row->perbaikan_status == 'Menunggu')
                            <p class="font-bold text-gray-800">Menunggu Penugasan</p>
                        @else
                            <p class="font-bold text-gray-800">Status diubah menjadi "{{ $row->perbaikan_status }}"</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">{{ $row->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada histori perbaikan.</p>
            @endforelse
        </div>

        <div class="hidden lg:block overflow-x-auto rounded-xl border">
            <table class="table w-full">
                <thead class="bg-base-200">
                <tr>
                    <th class="w-1/5">Tanggal</th>
                    <th class="w-1/5">Status</th>
                    <th class="w-3/5">Keterangan</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($histori as $row)
                    <tr>
                        <td>{{ $row->created_at->translatedFormat('d F Y, H:i') }}</td>
                        <td>
                            @if ($row->perbaikan_status == 'Menunggu')
                                <span class="badge badge-warning text-white">{{ $row->perbaikan_status }}</span>
                            @elseif ($row->perbaikan_status == 'Diproses')
                                <span class="badge badge-primary text-white">{{ $row->perbaikan_status }}</span>
                            @elseif ($row->perbaikan_status == 'Selesai')
                                <span class="badge badge-success text-white">{{ $row->perbaikan_status }}</span>
                            @else
                                <span class="badge badge-ghost">{{ $row->perbaikan_status }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->perbaikan_status == 'Menunggu')
                                <p>Penugasan dibuat dan menunggu teknisi untuk melakukan perbaikan.</p>
                            @elseif ($row->perbaikan_status == 'Diproses')
                                <p>Teknisi sedang melakukan perbaikan.</p>
                            @else
                                <p>Fasilitas telah selesai diperbaiki.</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6">
                            <p class="text-gray-500">Belum ada histori perbaikan.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
