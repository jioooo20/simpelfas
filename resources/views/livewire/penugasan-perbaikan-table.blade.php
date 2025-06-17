<div>
    {{-- Search and Filters --}}
    <div class="flex flex-col lg:flex-row gap-4 mb-6">
        {{-- Search Bar --}}
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari berdasarkan kode, deskripsi, fasilitas, atau gedung..." />
        </div>

        {{-- Status Filter --}}
        <div class="relative">
            <select wire:model.live="statusFilter" class="select select-bordered w-full lg:w-48">
                <option value="">Semua Status</option>
                <option value="Diterima">Menunggu Penugasan</option>
                <option value="Menunggu">Menunggu Teknisi</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>

        {{-- Teknisi Filter --}}
        <div class="relative">
            <select wire:model.live="teknisiFilter" class="select select-bordered w-full lg:w-48">
                <option value="">Semua Teknisi</option>
                @foreach ($teknisiList as $teknisi)
                    <option value="{{ $teknisi->user_id }}">{{ $teknisi->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th class="text-center">No</th>
                    <th>Fasilitas</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Teknisi Ditugaskan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $shownFasilitas = [];
                    $no = 1;
                    $rowCount = 0; // Tambahkan penghitung baris yang lolos filter
                @endphp
                @foreach ($perbaikanData as $index => $pelaporan)
                    @php
                        // Ambil status perbaikan terbaru
                        $currentStatus = 'Diterima';
                        if ($pelaporan->perbaikan && $pelaporan->perbaikan->latestStatusPerbaikan) {
                            $currentStatus = $pelaporan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                        } elseif ($pelaporan->statusPelaporan && $pelaporan->statusPelaporan->first()) {
                            $currentStatus = $pelaporan->statusPelaporan->first()->status_pelaporan;
                        }
                        // Pengelompokan: hanya tampilkan satu laporan per fasilitas
                        if (in_array($pelaporan->fasilitas_id, $shownFasilitas)) {
                            continue;
                        }
                        $shownFasilitas[] = $pelaporan->fasilitas_id;
                        if ($statusFilter && $currentStatus !== $statusFilter) {
                            continue;
                        }
                        $rowCount++;
                        $lokasi = '';
                        if ($pelaporan->fasilitas && $pelaporan->fasilitas->ruang) {
                            $lokasi =
                                $pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama .
                                ' - ' .
                                $pelaporan->fasilitas->ruang->lantai->lantai_nama .
                                ' - ' .
                                $pelaporan->fasilitas->ruang->ruang_nama;
                        }
                        $assignedTechnicians = '';
                        $perbaikanKode = '';
                        if ($pelaporan->perbaikan) {
                            $assignedTechnicians = $pelaporan->perbaikan->perbaikanPetugas
                                ->pluck('user.nama')
                                ->join(', ');
                            // Ambil status dari perbaikan jika ada
                            if ($pelaporan->perbaikan->latestStatusPerbaikan) {
                                $currentStatus = $pelaporan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                            } else {
                                // Jika tidak ada status perbaikan, cek status pelaporan terakhir
                                $lastStatus = $pelaporan->statusPelaporan->first();
                                $currentStatus = $lastStatus ? $lastStatus->status_pelaporan : 'Diterima';
                            }

                            $perbaikanKode = $pelaporan->perbaikan->perbaikan_kode;
                        } else {
                            // Jika tidak ada perbaikan, ambil status pelaporan terakhir
                            $lastStatus = $pelaporan->statusPelaporan->first();
                            $currentStatus = $lastStatus ? $lastStatus->status_pelaporan : 'Diterima';
                        }
                        $statusColor = $this->getStatusBadgeColor($currentStatus);
                    @endphp
                    <tr class="hover" wire:key="row-{{ $pelaporan->pelaporan_id }}">
                        <td class="text-center">{{ $no++ }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-gear-fill text-gray-500"></i>
                                <div>
                                    <div class="font-medium">{{ $pelaporan->fasilitas->barang->barang_nama ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pelaporan->fasilitas->fasilitas_kode ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-red-500"></i>
                                <div class="text-sm">{{ $lokasi ?: 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <p class="text-sm line-clamp-2">{{ $pelaporan->pelaporan_deskripsi }}</p>
                            </div>
                        </td>
                        <td>
                            @if ($assignedTechnicians)
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($pelaporan->perbaikan->perbaikanPetugas as $petugas)
                                        <span class="badge badge-info badge-sm">{{ $petugas->user->nama }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                // Ambil status dari latestStatusPerbaikan jika ada, jika tidak fallback ke statusPelaporan
                                $status = '-';
                                $badgeClass = 'badge-ghost';
                                if ($pelaporan->perbaikan && $pelaporan->perbaikan->latestStatusPerbaikan) {
                                    $status = $pelaporan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                                } elseif ($pelaporan->statusPelaporan && $pelaporan->statusPelaporan->first()) {
                                    $status = '-';
                                }
                                $badgeClass = $this->getStatusBadgeColor($status);
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                // Ambil tanggal dari latestStatusPerbaikan jika ada, jika tidak fallback ke statusPelaporan
                                $statusDate = null;
                                if ($pelaporan->perbaikan && $pelaporan->perbaikan->latestStatusPerbaikan) {
                                    $statusDate = $pelaporan->perbaikan->latestStatusPerbaikan->created_at;
                                } elseif ($pelaporan->statusPelaporan && $pelaporan->statusPelaporan->first()) {
                                    $statusDate = $pelaporan->statusPelaporan->first()->created_at;
                                } else {
                                    $statusDate = $pelaporan->created_at;
                                }
                            @endphp
                            <div class="text-sm">
                                {{ $statusDate ? $statusDate->format('d/m/Y') : '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $statusDate ? $statusDate->format('H:i') : '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                {{-- Detail Button --}}
                                <button wire:click="openDetailModal({{ $pelaporan->pelaporan_id }})"
                                    class="btn btn-sm btn-circle btn-ghost text-blue-500 tooltip"
                                    data-tip="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                {{-- Assign/Edit Assignment Button --}}
                                @if (in_array($currentStatus, ['Diterima']))
                                    <button wire:click="openAssignModal({{ $pelaporan->pelaporan_id }})"
                                        class="btn btn-sm btn-circle btn-ghost text-green-500 tooltip"
                                        data-tip="Tugaskan Teknisi">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($rowCount === 0)
                    <tr>
                        <td colspan="10" class="text-center py-8">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-inbox text-4xl text-gray-400"></i>
                                <span class="text-gray-500">
                                    Tidak ada data ditemukan
                                </span>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($perbaikanData->hasPages())
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $perbaikanData->firstItem() }} - {{ $perbaikanData->lastItem() }} dari
                {{ $perbaikanData->total() }} hasil
            </div>
            <div class="join">
                {{ $perbaikanData->links() }}
            </div>
        </div>
    @endif

    {{-- Assignment Modal --}}
    @if ($showAssignModal && $selectedPerbaikan)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            wire:key="assign-modal-{{ $selectedPerbaikan->pelaporan_id }}">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="bi bi-person-plus mr-2"></i>
                        Tugaskan Teknisi
                    </h2>
                    <button wire:click="closeAssignModal" class="btn btn-circle btn-ghost">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                </div>

                {{-- Report Information --}}
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                            <i class="bi bi-info-circle"></i>
                            Informasi Laporan
                        </h3>
                        <div class="space-y-3 mt-4">
                            <div class="flex justify-between">
                                <span class="font-semibold">Kode Perbaikan:</span>
                                <span>{{ $selectedPerbaikan->perbaikan ? $selectedPerbaikan->perbaikan->perbaikan_kode : 'Belum dibuat' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Status Perbaikan:</span>
                                <span>
                                    @php
                                        $status = 'Menunggu Penugasan';
                                        if (
                                            $selectedPerbaikan->perbaikan &&
                                            $selectedPerbaikan->perbaikan->latestStatusPerbaikan
                                        ) {
                                            $status =
                                                $selectedPerbaikan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                                        } elseif (
                                            $selectedPerbaikan->statusPelaporan &&
                                            $selectedPerbaikan->statusPelaporan->first()
                                        ) {
                                            $status = $selectedPerbaikan->statusPelaporan->first()->status_pelaporan;
                                        }
                                    @endphp
                                    {{ $status }}
                                </span>
                            </div>
                            <div class="">
                                <span class="font-semibold">Fasilitas:</span>
                                <div class="mt-1 text-sm flex flex-col gap-1">
                                    <span>{{ $selectedPerbaikan->fasilitas->barang->barang_nama ?? 'N/A' }}</span>
                                    <span>{{ $selectedPerbaikan->fasilitas->fasilitas_kode ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="font-semibold">Lokasi:</span>
                                <div class="mt-1 text-sm">
                                    @if ($selectedPerbaikan->fasilitas && $selectedPerbaikan->fasilitas->ruang)
                                        {{ $selectedPerbaikan->fasilitas->ruang->lantai->gedung->gedung_nama }} -
                                        {{ $selectedPerbaikan->fasilitas->ruang->lantai->lantai_nama }} -
                                        {{ $selectedPerbaikan->fasilitas->ruang->ruang_nama }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="font-semibold">Deskripsi:</span>
                                <p class="mt-1 text-sm text-gray-700">{{ $selectedPerbaikan->pelaporan_deskripsi }}</p>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Tanggal Kerusakan:</span>
                                <span>{{ $selectedPerbaikan->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Technician Selection --}}
                <div class="card bg-base-100 shadow-lg mt-6">
                    <div class="card-body">
                        <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                            <i class="bi bi-people"></i>
                            Pilih Teknisi yang Akan Ditugaskan <span class="text-red-500">*</span>
                        </h3>
                        <div class="mt-4">
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-3 bg-gray-50 rounded-lg">
                                @foreach ($teknisiList as $teknisi)
                                    <label
                                        class="flex items-center space-x-3 p-3 rounded-lg border bg-white hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox" wire:model="selectedTeknisi"
                                            value="{{ $teknisi->user_id }}" class="checkbox checkbox-primary">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">{{ $teknisi->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $teknisi->email }}</div>
                                            @if ($teknisi->identitas)
                                                <div class="text-xs text-gray-400">ID: {{ $teknisi->identitas }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('selectedTeknisi')
                                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card bg-base-100 shadow-lg mt-6">
                    <div class="card-body">
                        <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                            <i class="bi bi-tools"></i>
                            Deskripsi Perbaikan
                        </h3>
                        <div class="mt-4">
                            <textarea wire:model="catatan_penugasan" class="textarea textarea-bordered w-full" rows="3"
                                placeholder="Tambahkan deskripsi perbaikan yang akan dilakukan..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end mt-6">
                    <button wire:click="closeAssignModal" class="btn btn-outline mr-3">
                        <i class="bi bi-x mr-1"></i> Batal
                    </button>
                    <button wire:click="assignTeknisi" class="btn btn-primary">
                        <i class="bi bi-check mr-1"></i>
                        {{ $selectedPerbaikan->perbaikan && $selectedPerbaikan->perbaikan->perbaikanPetugas->count() > 0 ? 'Update Penugasan' : 'Tugaskan Teknisi' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedPerbaikan)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            wire:key="detail-modal-{{ $selectedPerbaikan->pelaporan_id }}">
            <div class="bg-white rounded-lg p-6 w-full max-w-4xl shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="bi bi-info-circle mr-2"></i>
                        Detail Laporan & Perbaikan
                    </h2>
                    <button wire:click="closeDetailModal" class="btn btn-circle btn-ghost">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                </div>

                @if ($selectedPerbaikan->perbaikan)
                    <div class="alert shadow-lg mb-6">
                        <div class="flex justify-between w-full">
                            <div>
                                <div class="ml-2">
                                    <h3 class="font-bold">Kode Perbaikan:
                                        {{ $selectedPerbaikan->perbaikan->perbaikan_kode }}</h3>
                                    @php
                                        $status = 'Menunggu Penugasan';
                                        $badgeClass = 'badge-secondary';
                                        if ($selectedPerbaikan->perbaikan->latestStatusPerbaikan) {
                                            $status =
                                                $selectedPerbaikan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                                            $badgeClass = $this->getStatusBadgeColor($status);
                                        } elseif (
                                            $selectedPerbaikan->statusPelaporan &&
                                            $selectedPerbaikan->statusPelaporan->first()
                                        ) {
                                            $status = $selectedPerbaikan->statusPelaporan->first()->status_pelaporan;
                                            $badgeClass = $this->getStatusBadgeColor($status);
                                        }
                                    @endphp
                                    <div class="text-sm">Status: <span
                                            class="badge {{ $badgeClass }}">{{ $status }}</span></div>
                                    <div>
                                        <span class="text-sm">Dibuat:
                                            {{ $selectedPerbaikan->perbaikan->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-6">
                        <i class="bi bi-info-circle"></i>
                        <span>Belum ada kode perbaikan yang dibuat. Perbaikan akan dibuat saat penugasan teknisi
                            dilakukan.</span>
                    </div>
                @endif
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Facility & Location --}}
                    <div class="card bg-base-100 shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                <i class="bi bi-geo-alt"></i>
                                Informasi Fasilitas & Lokasi
                            </h3>
                            <div class="space-y-3 mt-4">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Nama Fasilitas:</span>
                                    <span>{{ $selectedPerbaikan->fasilitas->barang->barang_nama ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Kode Fasilitas:</span>
                                    <span
                                        class="badge badge-outline">{{ $selectedPerbaikan->fasilitas->fasilitas_kode ?? 'N/A' }}</span>
                                </div>
                                @if ($selectedPerbaikan->fasilitas && $selectedPerbaikan->fasilitas->ruang)
                                    <div>
                                        <span class="font-semibold">Lokasi:</span>
                                        <div class="mt-1 text-sm space-y-1">
                                            <div class="flex justify-between">
                                                <span><i class="bi bi-building"></i>
                                                    {{ $selectedPerbaikan->fasilitas->ruang->lantai->gedung->gedung_nama }}</span>
                                                <span>{{ $selectedPerbaikan->fasilitas->ruang->lantai->gedung->gedung_kode }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span><i class="bi bi-layers"></i>
                                                    {{ $selectedPerbaikan->fasilitas->ruang->lantai->lantai_nama }}</span>
                                                <span>{{ $selectedPerbaikan->fasilitas->ruang->lantai->lantai_kode }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span><i class="bi bi-door-open"></i>
                                                    {{ $selectedPerbaikan->fasilitas->ruang->ruang_nama }}</span>
                                                <span>{{ $selectedPerbaikan->fasilitas->ruang->ruang_kode }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Assigned Technicians --}}
                    <div class="card bg-base-100 shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                <i class="bi bi-people"></i>
                                Teknisi Ditugaskan
                            </h3>
                            <div class="mt-4">
                                @if ($selectedPerbaikan->perbaikan && $selectedPerbaikan->perbaikan->perbaikanPetugas->count() > 0)
                                    <div class="space-y-3">
                                        @foreach ($selectedPerbaikan->perbaikan->perbaikanPetugas as $petugas)
                                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                                <div class="flex items-center gap-3">
                                                    <div class="avatar placeholder">
                                                        <div class="bg-blue-500 text-white rounded-full w-10">
                                                            <span
                                                                class="text-sm">{{ strtoupper(substr($petugas->user->nama, 0, 2)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium">{{ $petugas->user->nama }}</div>
                                                        <div class="text-sm text-gray-500">{{ $petugas->user->email }}
                                                        </div>
                                                        @if ($petugas->catatan)
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                {{ $petugas->catatan }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="badge badge-primary">Aktif</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="bi bi-person-x text-4xl mb-2"></i>
                                        <p>Belum ada teknisi yang ditugaskan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Repair Description --}}
                @if ($selectedPerbaikan->perbaikan && $selectedPerbaikan->perbaikan->perbaikan_deskripsi)
                    <div class="card bg-base-100 shadow-lg mt-6">
                        <div class="card-body">
                            <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                                <i class="bi bi-tools"></i>
                                Deskripsi Perbaikan
                            </h3>
                            <div class="mt-4">
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $selectedPerbaikan->perbaikan->perbaikan_deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endif {{-- Dokumentasi Foto Perbaikan oleh Teknisi --}}
                <div class="card bg-base-100 shadow-lg mt-6">
                    <div class="card-body">
                        <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                            <i class="bi bi-images"></i>
                            Dokumentasi Foto Perbaikan
                        </h3>
                        @if ($selectedPerbaikan->perbaikan)
                            @php
                                $statusPerbaikanList = \App\Models\StatusPerbaikanModel::where(
                                    'perbaikan_id',
                                    $selectedPerbaikan->perbaikan->perbaikan_id,
                                )
                                    ->orderBy('created_at', 'asc')
                                    ->get();
                            @endphp
                            @if ($statusPerbaikanList->whereNotNull('perbaikan_gambar')->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    @foreach ($statusPerbaikanList as $statusPerbaikan)
                                        @if ($statusPerbaikan->perbaikan_gambar)
                                            <div class="card bg-base-100 shadow-sm border">
                                                <figure class="px-4 pt-4">
                                                    <img src="{{ asset('storage/' . $statusPerbaikan->perbaikan_gambar) }}"
                                                        alt="Foto Perbaikan"
                                                        class="rounded-lg h-48 w-full object-cover cursor-pointer hover:opacity-75 transition-opacity"
                                                        onclick="window.open('{{ asset('storage/' . $statusPerbaikan->perbaikan_gambar) }}', '_blank')">
                                                </figure>
                                                <div class="card-body pt-2">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="badge badge-primary text-xs">
                                                            {{ $statusPerbaikan->perbaikan_status }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $statusPerbaikan->created_at->format('d/m/Y H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="bi bi-camera text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium mb-2">Belum ada dokumentasi foto</p>
                                    <p class="text-sm">Foto perbaikan akan ditampilkan di sini setelah teknisi
                                        mengunggahnya</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-exclamation-circle text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium mb-2">Belum ada data perbaikan</p>
                                <p class="text-sm">Dokumentasi foto akan tersedia setelah perbaikan dimulai</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Daftar Laporan Lain pada Fasilitas/Barang Ini --}}
                <div class="card bg-base-100 shadow-lg mt-6">
                    <div class="card-body">
                        <h3 class="card-title text-lg bg-base-200 p-3 -mx-6 -mt-6 rounded-t-lg">
                            <i class="bi bi-list-task"></i>
                            Laporan pada Fasilitas Ini
                        </h3>
                        <div class="overflow-x-auto mt-4">
                            <table class="table table-sm w-full">
                                <thead>
                                    <tr class="bg-base-200">
                                        <th>Kode Laporan</th>
                                        <th>Deskripsi</th>
                                        <th>Pelapor</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $laporanFasilitas = \App\Models\PelaporanModel::with([
                                            'statusPelaporan' => function ($q) {
                                                $q->latest('created_at');
                                            },
                                        ])
                                            ->where('fasilitas_id', $selectedPerbaikan->fasilitas->fasilitas_id)
                                            ->orderByDesc('created_at')
                                            ->get();
                                    @endphp
                                    @foreach ($laporanFasilitas as $laporan)
                                        <tr @if ($laporan->pelaporan_id == $selectedPerbaikan->pelaporan_id) class="bg-blue-50 font-bold" @endif>
                                            <td>
                                                <span
                                                    class="badge badge-outline">{{ $laporan->pelaporan_kode }}</span>
                                            </td>
                                            <td class="max-w-xs">
                                                <div class="truncate" title="{{ $laporan->pelaporan_deskripsi }}">
                                                    {{ \Illuminate\Support\Str::limit($laporan->pelaporan_deskripsi, 60) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="">
                                                    {{ $laporan->user->nama ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $laporan->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($laporanFasilitas->isEmpty())
                                <div class="text-center text-gray-400 py-4">Tidak ada laporan lain pada fasilitas ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Close Button --}}
                <div class="flex justify-end mt-6">
                    @if ($selectedPerbaikan->perbaikan && $selectedPerbaikan->perbaikan->latestStatusPerbaikan)
                        @php
                            $currentStatus = $selectedPerbaikan->perbaikan->latestStatusPerbaikan->perbaikan_status;
                            // Ambil status pelaporan terbaru dengan first() karena sudah diurutkan DESC
                            $laporanStatus = $selectedPerbaikan->statusPelaporan->first()->status_pelaporan ?? null;
                            // Tombol hanya aktif jika status perbaikan 'Selesai' dan status pelaporan BUKAN 'Selesai'
                            $isDisabled = !($currentStatus == 'Selesai' && $laporanStatus != 'Selesai');
                            $buttonClass = !$isDisabled ? 'btn-success' : 'btn-info';
                            $disabledClass = $isDisabled ? 'opacity-60 cursor-not-allowed' : '';
                        @endphp
                        <button
                            wire:click="{{ $isDisabled ? '' : 'markAsCompleted(' . $selectedPerbaikan->pelaporan_id . ')' }}"
                            class="btn {{ $buttonClass }} mr-3 {{ $disabledClass }}"
                            {{ $isDisabled ? 'disabled' : '' }}
                            title="
                    @if ($laporanStatus == 'Selesai' && $currentStatus != 'Selesai') Status pelaporan sudah selesai, status perbaikan belum selesai
                    @elseif($currentStatus == 'Selesai' && $laporanStatus == 'Selesai')
                        Laporan sudah selesai
                    @elseif($currentStatus != 'Selesai')
                        Laporan harus dalam status Selesai @endif
                ">
                            <i class="bi bi-check-circle-fill mr-1"></i> Laporan Selesai
                        </button>
                    @else
                        <button class="btn btn-info mr-3 opacity-60 cursor-not-allowed" disabled
                            title="Data perbaikan tidak tersedia">
                            <i class="bi bi-check-circle-fill mr-1"></i> Laporan Selesai
                        </button>
                    @endif
                    <button wire:click="closeDetailModal" class="btn btn-outline">
                        <i class="bi bi-x mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('showSuccessToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-check-circle-fill text-xl"></i>
                              <span>${message}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });

            Livewire.on('showErrorToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-exclamation-circle-fill text-xl"></i>
                              <span>${message}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });
        });
    </script>
@endpush
