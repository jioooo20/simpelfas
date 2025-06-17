<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Section 1: Informasi Perbaikan -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Perbaikan
                </h2>

                <div class="mt-4">
                    <div class="mb-3 flex flex-col">
                        <span class="font-semibold">Status :</span>
                        @if ($statusTerakhir->perbaikan_status == 'Menunggu')
                            <span
                                class="badge badge-info mt-1 text-white">{{ $statusTerakhir->perbaikan_status ?? '-' }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Diproses')
                            <span
                                class="badge badge-primary mt-1 text-white">{{ $statusTerakhir->perbaikan_status ?? '-' }}</span>
                        @elseif ($statusTerakhir->perbaikan_status == 'Selesai')
                            <span
                                class="badge badge-success mt-1 text-white">{{ $statusTerakhir->perbaikan_status ?? '-' }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Kode Perbaikan :</span>
                        <p>{{ $perbaikan->perbaikan_kode ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Tanggal Dibuat :</span>
                        <p>{{ date('d/m/Y H:i', strtotime($perbaikan->created_at)) }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Terakhir Update :</span>
                        <p>{{ date('d/m/Y H:i', strtotime($statusTerakhir->created_at ?? $perbaikan->updated_at)) }}</p>
                    </div>                </div>
                @if ($statusTerakhir->perbaikan_status != 'Selesai')
                    <!-- Tombol aksi untuk teknisi -->
                    <div class="card-actions justify-end mt-16">
                        @php
                            // Cek apakah user yang login adalah teknisi yang ditugaskan
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
                                class="btn btn-primary btn-sm text-white">Update
                                Status</button>
                        @else
                            <button type="button" disabled
                                class="btn btn-primary btn-sm text-white opacity-50 cursor-not-allowed" 
                                title="Hanya teknisi yang ditugaskan yang dapat mengupdate status">Update
                                Status</button>
                        @endif
                    </div>
                    @livewire('perbaikan-update-form', ['perbaikanId' => $perbaikan->perbaikan_id], key($perbaikan->perbaikan_id))
                @else
                    <div class="text-center mt-4">
                        <span class="text-gray-500">Perbaikan telah selesai</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 2: Informasi Laporan -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Laporan
                </h2>

                <div class="mt-4">
                    <div class="mb-3">
                        <span class="font-semibold">Lokasi :</span>
                        <p>{{ $lokasi ? $lokasi->gedung_nama ?? '-' : '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Fasilitas :</span>
                        <p>{{ $fasilitas ? $fasilitas->fasilitas_kode ?? '-' : '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Deskripsi Masalah :</span>
                        <p class="text-sm">{{ $perbaikan->pelaporan->pelaporan_deskripsi ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Total Laporan :</span>
                        <p>{{ $totalPerbaikan ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="font-semibold">Bukti Foto :</span>
                    </div>
                    <div class="mt-2">
                        @php
                            // Ambil prefix kode perbaikan
                            $prefix = preg_replace('/\d+$/', '', $perbaikan->perbaikan_kode);
                            // Cari pelaporan dengan kode perbaikan mirip
                            $perbaikanSama = \App\Models\PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->pluck('pelaporan_id');
                            $fotoPath = null;
                            if ($perbaikanSama->count() > 0) {
                                $pelaporanFoto = \App\Models\PelaporanModel::whereIn('pelaporan_id', $perbaikanSama)
                                    ->whereNotNull('pelaporan_gambar')
                                    ->first();
                                if ($pelaporanFoto && $pelaporanFoto->pelaporan_gambar) {
                                    $fotoArr = json_decode($pelaporanFoto->pelaporan_gambar, true);
                                    if (is_array($fotoArr) && count($fotoArr) > 0) {
                                        $fotoPath = $fotoArr[0];
                                    }
                                }
                            }
                        @endphp
                        @if ($fotoPath)
                            <button type="button" onclick="openImageModal('{{ $fotoPath }}')"
                                class="btn btn-sm btn-primary mt-1 text-white">
                                <i class="fas fa-image"></i> Lihat Foto
                            </button>
                        @else
                            <span class="text-gray-400">Tidak ada foto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Informasi Teknisi -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                    Informasi Teknisi
                </h2>

                <div class="mt-4">
                    <div class="mb-3">
                        <span class="font-semibold">Nama Teknisi :</span>
                        @if ($perbaikan->perbaikanPetugas && $perbaikan->perbaikanPetugas->count())
                            <ul class="list-disc ml-5">
                                @foreach ($perbaikan->perbaikanPetugas as $petugas)
                                    <li>{{ $petugas->user->nama ?? '-' }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>-</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="font-semibold">Deskripsi Perbaikan :</span>
                        <p class="text-sm">{{ $perbaikan->perbaikan_deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histori update perbaikan -->
    <div class="mt-8 mb-10">
        <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th class="w-1/5">Tanggal</th>
                        <th class="w-1/5">Status</th>
                        <th class="w-3/5">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histori as $row)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($row->created_at)) }}</td>
                            @if ($row->perbaikan_status == 'Menunggu')
                                <td><span class="badge badge-info text-white">{{ $row->perbaikan_status }}</span></td>
                            @elseif ($row->perbaikan_status == 'Diproses')
                                <td><span class="badge badge-primary text-white">{{ $row->perbaikan_status }}</span>
                                </td>
                            @elseif ($row->perbaikan_status == 'Selesai')
                                <td><span class="badge badge-success text-white">{{ $row->perbaikan_status }}</span>
                                </td>
                            @endif
                            <td>
                                @if ($row->perbaikan_status == 'Menunggu')
                                    <p class="">Penugasan Perbaikan Fasilitas dibuat dan menunggu teknisi untuk
                                        melakukan perbaikan</p>
                                @elseif ($row->perbaikan_status == 'Diproses')
                                    <p class="">Teknisi sedang melakukan perbaikan</p>
                                @elseif ($row->perbaikan_status == 'Selesai')
                                    <p class="">Fasilitas telah selesai diperbaiki</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
