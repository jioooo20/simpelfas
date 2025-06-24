<?php

namespace App\Livewire;

use App\Models\FasilitasModel;
use App\Models\PerbaikanModel;
use App\Models\SkorAltModel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PelaporanModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\DB;

class LaporanKerusakan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $statusFilter = 'Menunggu';

    // Modal detail properties
    public $showDetailModal = false;
    public $selectedLaporan = null;

    // Available status options
    public $statusOptions = [
        'Menunggu',
        'Diterima',
        'Diproses',
        'Selesai',
        'Ditolak'
    ];

    // Available per page options
    public $perPageOptions = [5, 10, 25, 50, 100];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'Menunggu'],
        'perPage' => ['except' => 10],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        // Validate perPage value
        if (!in_array((int)$value, $this->perPageOptions)) {
            $this->perPage = 10;
        }
        $this->resetPage();
    }

    public function mount()
    {
        // Set default values
        $this->perPage = 10;
        $this->statusFilter = 'Menunggu';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = 'Menunggu';
        $this->perPage = 10;
        $this->resetPage();
    }

    public function render()
    {
        // Get latest status for each pelaporan to avoid N+1 queries
        $latestStatuses = DB::table('t_status_pelaporan as sp1')
            ->select('sp1.pelaporan_id', 'sp1.status_pelaporan')
            ->whereRaw('sp1.created_at = (
                SELECT MAX(sp2.created_at)
                FROM t_status_pelaporan sp2
                WHERE sp2.pelaporan_id = sp1.pelaporan_id
            )')
            ->pluck('status_pelaporan', 'pelaporan_id')
            ->toArray();

        // Base query with proper eager loading
        $query = PelaporanModel::with([
            'user:user_id,nama,email',
            'fasilitas.ruang.lantai.gedung:gedung_id,gedung_nama',
            'fasilitas.ruang.lantai:lantai_id,lantai_nama,gedung_id',
            'fasilitas.ruang:ruang_id,ruang_nama,lantai_id',
            'fasilitas.barang:barang_id,barang_nama'
        ]);

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pelaporan_kode', 'like', $searchTerm)
                    ->orWhere('pelaporan_deskripsi', 'like', $searchTerm)
                    ->orWhereHas('user', function ($subQ) use ($searchTerm) {
                        $subQ->where('nama', 'like', $searchTerm);
                    })
                    ->orWhereHas('fasilitas.barang', function ($subQ) use ($searchTerm) {
                        $subQ->where('barang_nama', 'like', $searchTerm);
                    })
                    ->orWhereHas('fasilitas.ruang.lantai.gedung', function ($subQ) use ($searchTerm) {
                        $subQ->where('gedung_nama', 'like', $searchTerm);
                    })
                    ->orWhereHas('fasilitas.ruang.lantai', function ($subQ) use ($searchTerm) {
                        $subQ->where('lantai_nama', 'like', $searchTerm);
                    })
                    ->orWhereHas('fasilitas.ruang', function ($subQ) use ($searchTerm) {
                        $subQ->where('ruang_nama', 'like', $searchTerm);
                    });
            });
        }

        // Apply status filter
        if (!empty($this->statusFilter)) {
            $filteredIds = collect($latestStatuses)
                ->filter(function ($status) {
                    return $status === $this->statusFilter;
                })
                ->keys()
                ->toArray();

            if (!empty($filteredIds)) {
                $query->whereIn('pelaporan_id', $filteredIds);
            } else {
                // If no reports match the status, return empty result
                $query->whereRaw('1 = 0');
            }
        }

        // Get paginated results
        $laporans = $query->orderBy('created_at', 'asc')
            ->paginate((int)$this->perPage, ['*'], 'page');

        // Attach latest status to each laporan
        $laporans->getCollection()->transform(function ($laporan) use ($latestStatuses) {
            $laporan->latest_status = $latestStatuses[$laporan->pelaporan_id] ?? 'Menunggu';
            return $laporan;
        });

        return view('livewire.laporan-kerusakan', compact('laporans'));
    }

    public function showDetail($laporanId)
    {
        $this->selectedLaporan = PelaporanModel::with([
            'user',
            'fasilitas.ruang.lantai.gedung',
            'fasilitas.barang',
            'statusPelaporan' => function ($q) {
                $q->latest('created_at');
            }
        ])->findOrFail($laporanId);

        // Get the actual latest status from database
        $latestStatus = DB::table('t_status_pelaporan')
            ->where('pelaporan_id', $laporanId)
            ->orderBy('created_at', 'desc')
            ->value('status_pelaporan');

        $this->selectedLaporan->latest_status = $latestStatus ?? 'Menunggu';

        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedLaporan = null;
    }

    public function terimaLaporan()
    {
        try {
            DB::beginTransaction();

            // Update fasilitas status to 'Rusak'
            FasilitasModel::where('fasilitas_id', $this->selectedLaporan->fasilitas_id)
                ->update(['fasilitas_status' => 'Rusak']);

            // Cari semua pelaporan dengan fasilitas_id yang sama dan status 'Menunggu' atau 'Diterima'
            $existingLaporans = PelaporanModel::where('fasilitas_id', $this->selectedLaporan->fasilitas_id)
                ->whereHas('statusPelaporan', function ($q) {
                    $q->whereIn('status_pelaporan', ['Menunggu', 'Diterima'])
                      ->whereNotExists(function ($subQ) {
                          $subQ->select(DB::raw(1))
                               ->from('t_status_pelaporan as sp2')
                               ->whereRaw('sp2.pelaporan_id = t_status_pelaporan.pelaporan_id')
                               ->where('sp2.status_pelaporan', 'Selesai');
                      });
                })
                ->get();

            // Tambahkan laporan saat ini jika belum ada di collection
            if (!$existingLaporans->contains('pelaporan_id', $this->selectedLaporan->pelaporan_id)) {
                $existingLaporans->push($this->selectedLaporan);
            }

            $pelaporanIds = $existingLaporans->pluck('pelaporan_id')->toArray();
            $jumlahLaporan = count($pelaporanIds);

            // Ambil nilai C4 dari semua laporan dengan fasilitas yang sama
            $existingC4Scores = SkorAltModel::where('kriteria_id', 4)
                ->whereIn('pelaporan_id', $pelaporanIds)
                ->pluck('nilai_skor')
                ->toArray();

            // Hitung rata-rata C4 atau ambil nilai tunggal
            $avgC4 = !empty($existingC4Scores) ?
                (count($existingC4Scores) > 1 ?
                    array_sum($existingC4Scores) / count($existingC4Scores) :
                    $existingC4Scores[0]) : 0;

            if ($jumlahLaporan > 1) {
                // Ambil semua skor C2 dan C3 yang sudah ada
                $existingC2Scores = SkorAltModel::where('kriteria_id', 2)
                    ->whereIn('pelaporan_id', $pelaporanIds)
                    ->pluck('nilai_skor')
                    ->toArray();

                $existingC3Scores = SkorAltModel::where('kriteria_id', 3)
                    ->whereIn('pelaporan_id', $pelaporanIds)
                    ->pluck('nilai_skor')
                    ->toArray();

                // Hitung rata-rata
                $avgC2 = !empty($existingC2Scores) ? array_sum($existingC2Scores) / count($existingC2Scores) : 0;
                $avgC3 = !empty($existingC3Scores) ? array_sum($existingC3Scores) / count($existingC3Scores) : 0;

                // Update atau create untuk semua pelaporan
                foreach ($pelaporanIds as $pelaporanId) {
                    // Create status 'Diterima' jika belum ada
                    $hasAcceptedStatus = StatusPelaporanModel::where('pelaporan_id', $pelaporanId)
                        ->where('status_pelaporan', 'Diterima')
                        ->exists();

                    if (!$hasAcceptedStatus) {
                        StatusPelaporanModel::create([
                            'pelaporan_id' => $pelaporanId,
                            'status_pelaporan' => 'Diterima'
                        ]);
                    }

                    // Update atau create skor C1 (jumlah laporan)
                    SkorAltModel::updateOrCreate(
                        [
                            'pelaporan_id' => $pelaporanId,
                            'kriteria_id' => 1
                        ],
                        [
                            'skor_alt_kode' => $pelaporanId . '-C1',
                            'nilai_skor' => $jumlahLaporan
                        ]
                    );

                    // Update skor C2 dengan rata-rata jika ada data
                    if (!empty($existingC2Scores)) {
                        SkorAltModel::updateOrCreate(
                            [
                                'pelaporan_id' => $pelaporanId,
                                'kriteria_id' => 2
                            ],
                            [
                                'skor_alt_kode' => $pelaporanId . '-C2',
                                'nilai_skor' => $avgC2
                            ]
                        );
                    }

                    // Update skor C3 dengan rata-rata jika ada data
                    if (!empty($existingC3Scores)) {
                        SkorAltModel::updateOrCreate(
                            [
                                'pelaporan_id' => $pelaporanId,
                                'kriteria_id' => 3
                            ],
                            [
                                'skor_alt_kode' => $pelaporanId . '-C3',
                                'nilai_skor' => $avgC3
                            ]
                        );
                    }
                    // Update atau create skor C14 (biaya)
                    SkorAltModel::updateOrCreate(
                        [
                            'pelaporan_id' => $pelaporanId,
                            'kriteria_id' => 4
                        ],
                        [
                            'skor_alt_kode' => $pelaporanId . '-C1',
                            'nilai_skor' => $avgC4
                        ]
                    );
                }

                // Ambil user_id dari semua pelaporan untuk notifikasi
                $userIds = PelaporanModel::whereIn('pelaporan_id', $pelaporanIds)
                    ->pluck('user_id')
                    ->unique()
                    ->toArray();

                // Notifikasi
                sendRoleNotification(
                    [],
                    'Laporan Diterima',
                    'Laporan kerusakan ' . $this->selectedLaporan->fasilitas->barang->barang_nama . ' - ' . substr($this->selectedLaporan->fasilitas->fasilitas_kode, -2) . ' telah diterima dan akan segera diproses.',
                    'users/status-laporan',
                    $userIds
                );
            } else {
                // Hanya ada 1 laporan
                StatusPelaporanModel::create([
                    'pelaporan_id' => $this->selectedLaporan->pelaporan_id,
                    'status_pelaporan' => 'Diterima'
                ]);

                // Create skor C1 dengan nilai 1
                SkorAltModel::create([
                    'pelaporan_id' => $this->selectedLaporan->pelaporan_id,
                    'skor_alt_kode' => $this->selectedLaporan->pelaporan_id . '-C1',
                    'kriteria_id' => 1,
                    'nilai_skor' => 1
                ]);

                // Create skor C4 dengan nilai 0
                SkorAltModel::create([
                    'pelaporan_id' => $this->selectedLaporan->pelaporan_id,
                    'skor_alt_kode' => $this->selectedLaporan->pelaporan_id . '-C4',
                    'kriteria_id' => 4,
                    'nilai_skor' => 0
                ]);

                // Ambil user_id untuk notifikasi
                $userId = $this->selectedLaporan->user_id;

                // Notifikasi
                sendRoleNotification(
                    [],
                    'Laporan Diterima',
                    'Laporan kerusakan ' . $this->selectedLaporan->fasilitas->barang->barang_nama . ' - ' . substr($this->selectedLaporan->fasilitas->fasilitas_kode, -2) . ' telah diterima dan akan segera diproses.',
                    'users/status-laporan',
                    [$userId]
                );
            }

            DB::commit();

            $this->dispatch('showSuccessToast', 'Laporan berhasil diterima dan akan diproses');
            $this->closeModal();

            // Force refresh the component
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showErrorToast', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolakLaporan()
    {
        try {
            DB::beginTransaction();

            // Update status to 'Ditolak'
            StatusPelaporanModel::create([
                'pelaporan_id' => $this->selectedLaporan->pelaporan_id,
                'status_pelaporan' => 'Ditolak'
            ]);

            DB::commit();

            $this->dispatch('showSuccessToast', 'Laporan berhasil ditolak');
            $this->closeModal();

            // Force refresh the component
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showErrorToast', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getLatestStatus($laporan)
    {
        // Use the preloaded status to prevent additional queries
        return $laporan->latest_status ?? 'Menunggu';
    }

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'Menunggu' => 'badge-warning',
            'Diterima' => 'badge-info',
            'Diproses' => 'badge-primary',
            'Selesai' => 'badge-success',
            'Ditolak' => 'badge-error',
            default => 'badge-ghost'
        };
    }
}
