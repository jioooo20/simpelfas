<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PerbaikanModel;
use App\Models\PelaporanModel;
use App\Models\UserModel;
use App\Models\PerbaikanPetugasModel;
use App\Models\StatusPerbaikanModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PenugasanPerbaikanTable extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $statusFilter = '';
    public $teknisiFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showAssignModal = false;
    public $showDetailModal = false;
    public $selectedPerbaikan = null;
    public $selectedTeknisi = [];
    public $catatan_penugasan = '';    // Data properties
    public $teknisiList = [];
    public $statusList =
    [
        'Diterima',
        'Diproses',
        'Selesai'
    ];

    protected $listeners =
    [
        'refreshTable' => '$refresh',
    ];

    public function mount()
    {
        $this->loadTeknisiList();
    }

    public function loadTeknisiList()
    {
        // Load teknisi (users with role teknisi)
        $this->teknisiList = UserModel::whereHas('role', function ($query) {
            $query->where('role_nama', 'teknisi');
        })->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedTeknisiFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->teknisiFilter = '';
        $this->resetPage();
    }

    public function getPerbaikanData()
    {
        $latestStatuses = DB::table('t_status_pelaporan as sp1')
            ->select('sp1.pelaporan_id', 'sp1.status_pelaporan', 'sp1.created_at')
            ->whereRaw('sp1.created_at = (
                SELECT MAX(sp2.created_at)
                FROM t_status_pelaporan sp2
                WHERE sp2.pelaporan_id = sp1.pelaporan_id
            )')
            ->whereIn('sp1.status_pelaporan', ['Diterima', 'Menunggu', 'Diproses', 'Selesai'])
            ->orderBy('sp1.created_at', 'desc')
            ->get();

        $orderedIds = $latestStatuses->pluck('pelaporan_id')->toArray();


        $query = PelaporanModel::with([
            'fasilitas.barang',
            'fasilitas.ruang.lantai.gedung',
            'user',
            'perbaikan.latestStatusPerbaikan', // Tambahkan relasi ke status perbaikan terbaru
            'statusPelaporan' => function ($query) {
                $query->latest(); // Ambil status pelaporan terbaru
            },
        ])->whereIn('pelaporan_id', $orderedIds);

        // Search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('pelaporan_kode', 'like', '%' . $this->search . '%')
                    ->orWhere('pelaporan_deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhereHas('fasilitas.barang', function ($subQ) {
                        $subQ->where('barang_nama', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('fasilitas.ruang.lantai.gedung', function ($subQ) {
                        $subQ->where('gedung_nama', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('fasilitas.ruang', function ($subQ) {
                        $subQ->where('ruang_nama', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter status berdasarkan status terakhir
        if (!empty($this->statusFilter)) {
            // Jika statusFilter adalah 'Diterima', kita perlu mengambil semua pelaporan yang memiliki status 'Diterima'
            if ($this->statusFilter == 'Diterima') {
                $filteredIds = DB::table('t_status_pelaporan as sp1')
                    ->select('sp1.pelaporan_id')
                    ->whereRaw('sp1.created_at = (
                        SELECT MAX(sp2.created_at)
                        FROM t_status_pelaporan sp2
                        WHERE sp2.pelaporan_id = sp1.pelaporan_id
                    )')
                    ->where('sp1.status_pelaporan', $this->statusFilter)
                    ->pluck('pelaporan_id')
                    ->toArray();
                $query->whereIn('pelaporan_id', $filteredIds);
            } else {
                // Ambil data dari t_status_perbaikan untuk status selain 'Diterima'
                $query->where(function ($q) {
                    $q->whereHas('perbaikan.statusPerbaikan', function ($subQ) {
                        $subQ->where('perbaikan_status', $this->statusFilter);
                    });
                    // Jika tidak ada di status perbaikan, cek di status pelaporan
                    // (hanya jika status pelaporan adalah 'Diproses' atau 'Selesai')
                    if (in_array($this->statusFilter, ['Diproses', 'Selesai'])) {
                        $q->orWhereHas('statusPelaporan', function ($subQ) {
                            $subQ->where('status_pelaporan', $this->statusFilter)
                                ->whereRaw('created_at = (
                                    SELECT MAX(created_at)
                                    FROM t_status_pelaporan
                                    WHERE pelaporan_id = m_pelaporan.pelaporan_id
                                )');
                        });
                    }
                });
            }
        }

        // Filter teknisi
        if (!empty($this->teknisiFilter)) {
            $query->whereHas('perbaikan.perbaikanPetugas', function ($subQ) {
                $subQ->where('user_id', $this->teknisiFilter);
            });
        }

        // Urutkan sesuai urutan pelaporan_id terbaru dari status pelaporan
        if (!empty($orderedIds)) {
            $query->orderByRaw('FIELD(pelaporan_id, ' . implode(',', $orderedIds) . ')');
        }

        return $query->paginate($this->perPage);
    }

    public function openAssignModal($pelaporanId)
    {
        try {
            // Get the pelaporan record with correct relationships
            $this->selectedPerbaikan = PelaporanModel::with([
                'fasilitas.barang',
                'fasilitas.ruang.lantai.gedung',
                'user',
                'perbaikan.perbaikanPetugas.user',
                'perbaikan.latestStatusPerbaikan',
                'statusPelaporan' => function ($query) {
                    $query->latest(); // Ambil status pelaporan terbaru
                }
            ])->find($pelaporanId);

            if (!$this->selectedPerbaikan) {
                $this->dispatch('showErrorToast', 'Data laporan tidak ditemukan');
                return;
            }

            // Load currently assigned technicians if perbaikan exists
            if ($this->selectedPerbaikan->perbaikan) {
                $this->selectedTeknisi = $this->selectedPerbaikan->perbaikan->perbaikanPetugas->pluck('user_id')->toArray();
            } else {
                $this->selectedTeknisi = [];
            }

            $this->catatan_penugasan = '';
            $this->showAssignModal = true;
        } catch (\Exception $e) {
            Log::error('Error opening assign modal: ' . $e->getMessage());
            $this->dispatch('showErrorToast', 'Terjadi kesalahan saat membuka modal penugasan');
        }
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->selectedPerbaikan = null;
        $this->selectedTeknisi = [];
        $this->catatan_penugasan = '';
        $this->resetValidation();
    }

    public function assignTeknisi()
    {
        $this->validate([
            'selectedTeknisi' => 'required|array|min:1',
            'selectedTeknisi.*' => 'exists:m_user,user_id',
        ], [
            'selectedTeknisi.required' => 'Pilih minimal satu teknisi',
            'selectedTeknisi.min' => 'Pilih minimal satu teknisi',
            'selectedTeknisi.*.exists' => 'Teknisi tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Ambil semua laporan dengan fasilitas yang sama
            $fasilitasId = $this->selectedPerbaikan->fasilitas_id;
            $laporanList = PelaporanModel::where('fasilitas_id', $fasilitasId)->get();
            $index = 1;
            // Generate kode dasar perbaikan
            $tgl = date('ymd');
            // Hitung jumlah perbaikan unik berdasarkan fasilitas_id
            if (PerbaikanModel::count() != 0) {
                $total_perbaikan_unik = PerbaikanModel::join('m_pelaporan', 'm_pelaporan.pelaporan_id', '=', 't_perbaikan.pelaporan_id')
                    ->distinct('m_pelaporan.fasilitas_id')
                    ->count();
            } else {
                $total_perbaikan_unik = 0;
            }

            $kodeDasar = 'PRBK-' . ($total_perbaikan_unik + 1) . '-' . $tgl . '-';
            foreach ($laporanList as $laporan) {
                // Cek jika belum ada perbaikan untuk laporan ini
                $perbaikanLaporan = $laporan->perbaikan;
                if (!$perbaikanLaporan) {
                    $kodePerbaikan = $kodeDasar . $laporan->pelaporan_id;
                    $perbaikanLaporan = PerbaikanModel::create([
                        'perbaikan_kode' => $kodePerbaikan,
                        'pelaporan_id' => $laporan->pelaporan_id,
                        'perbaikan_deskripsi' => $this->catatan_penugasan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                // Remove existing assignments
                PerbaikanPetugasModel::where('perbaikan_id', $perbaikanLaporan->perbaikan_id)->delete();
                // Tambahkan assignment teknisi
                foreach ($this->selectedTeknisi as $teknisiId) {
                    PerbaikanPetugasModel::create([
                        'perbaikan_id' => $perbaikanLaporan->perbaikan_id,
                        'user_id' => $teknisiId,
                    ]);
                }
                // Update/create status perbaikan
                $statusPerbaikan = StatusPerbaikanModel::where('perbaikan_id', $perbaikanLaporan->perbaikan_id)->first();
                if ($statusPerbaikan) {
                    $statusPerbaikan->update([
                        'perbaikan_status' => 'Menunggu'
                    ]);
                } else {
                    StatusPerbaikanModel::create([
                        'perbaikan_id' => $perbaikanLaporan->perbaikan_id,
                        'perbaikan_status' => 'Menunggu'
                    ]);
                }
                // Update status pelaporan
                StatusPelaporanModel::create([
                    'pelaporan_id' => $laporan->pelaporan_id,
                    'status_pelaporan' => 'Diproses'
                ]);
                $index++;
            }

            //url
            $perbaikanFirst = PerbaikanModel::whereHas('pelaporan', function ($query) use ($fasilitasId) {
                $query->where('fasilitas_id', $fasilitasId);
            })->first();
            $idperbaikan = $perbaikanFirst ? $perbaikanFirst->perbaikan_id : null;
            $url = '/teknisi/perbaikan/detail/' . $idperbaikan;
            //notif
            sendRoleNotification(
                [],
                'Penugasan Perbaikan Fasilitas',
                'Anda telah ditugaskan untuk melakukan perbaikan fasilitas. Silakan periksa detail perbaikan dan lakukan tindakan yang diperlukan.',
                $url,
                $this->selectedTeknisi
            );

            sendRoleNotification(
                ['1'],
                'Fasilitas Dalam Perbaikan',
                'Pantau perbaikan fasilitas yang sedang berlangsung.',
                route('laporan.index')
            );

            DB::commit();

            $teknisiNames = UserModel::whereIn('user_id', $this->selectedTeknisi)->pluck('nama')->join(', ');
            $message = count($this->selectedTeknisi) > 1 ?
                "Berhasil menugaskan teknisi: {$teknisiNames}" :
                "Berhasil menugaskan teknisi: {$teknisiNames}";

            $this->dispatch('showSuccessToast', $message);
            $this->closeAssignModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error assigning technicians: ' . $e->getMessage());
            $this->dispatch('showErrorToast', 'Terjadi kesalahan saat menugaskan teknisi: ' . $e->getMessage());
        }
    }

    public function openDetailModal($pelaporanId)
    {
        try {
            $this->selectedPerbaikan = PelaporanModel::with([
                'fasilitas.barang',
                'fasilitas.ruang.lantai.gedung',
                'user',
                'perbaikan.perbaikanPetugas.user',
                'perbaikan.latestStatusPerbaikan',
                'statusPelaporan' => function ($query) {
                    $query->latest(); // Ambil status pelaporan terbaru
                }
            ])->find($pelaporanId);

            if (!$this->selectedPerbaikan) {
                $this->dispatch('showErrorToast', 'Data laporan tidak ditemukan');
                return;
            }

            $this->showDetailModal = true;
        } catch (\Exception $e) {
            Log::error('Error opening detail modal: ' . $e->getMessage());
            $this->dispatch('showErrorToast', 'Terjadi kesalahan saat membuka detail');
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPerbaikan = null;
    }

    /**
     * Mark a repair report as completed
     *
     * @param int $pelaporanId
     * @return void
     */
    public function markAsCompleted($pelaporanId)
    {
        try {
            DB::beginTransaction();

            // Get the pelaporan record with necessary relationships
            $pelaporan = PelaporanModel::with(['perbaikan.latestStatusPerbaikan'])->find($pelaporanId);

            if (!$pelaporan) {
                $this->dispatch('showErrorToast', 'Data laporan tidak ditemukan');
                return;
            }

            if (!$pelaporan->perbaikan) {
                $this->dispatch('showErrorToast', 'Data perbaikan tidak ditemukan');
                return;
            }

            // Check if current status is "Selesai"
            $currentStatus = $pelaporan->perbaikan->latestStatusPerbaikan?->perbaikan_status ?? null;
            if ($currentStatus !== 'Selesai') {
                $this->dispatch('showErrorToast', 'Hanya laporan dengan status "Selesai" yang dapat dilaporkan selesai.');
                return;
            }

            // Get the base repair code to find related repairs
            $perbaikanKode = $pelaporan->perbaikan->perbaikan_kode;
            $baseKode = substr($perbaikanKode, 0, strrpos($perbaikanKode, '-'));

            // Find all repairs with similar code (prefix match)
            $relatedPerbaikan = PerbaikanModel::where('perbaikan_kode', 'like', $baseKode . '-%')->get();
            $completedCount = 0;
            foreach ($relatedPerbaikan as $perbaikan) {
                // Only update if status pelaporan terakhir belum 'Selesai'
                $latestStatus = StatusPelaporanModel::where('pelaporan_id', $perbaikan->pelaporan_id)
                    ->orderByDesc('created_at')
                    ->first();
                if ($latestStatus && $latestStatus->status_pelaporan === 'Selesai') {
                    continue;
                }
                // Update status pelaporan saja
                StatusPelaporanModel::create([
                    'pelaporan_id' => $perbaikan->pelaporan_id,
                    'status_pelaporan' => 'Selesai',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $completedCount++;

                //notip
                $url = '/users/laporan-detail/' . $perbaikan->pelaporan_id;
                $pelaporID = DB::table('m_pelaporan')
                    ->where('pelaporan_id', $perbaikan->pelaporan_id)
                    ->pluck('user_id')
                    ->toArray();
                sendRoleNotification(
                    [],
                    'Perbaikan Selesai',
                    'Perbaikan fasilitas telah selesai dikerjakan. Silakan periksa hasil perbaikan dan berikan feedback jika diperlukan.',
                    $url,
                    $pelaporID
                );
            }
            DB::commit();
            $message = $completedCount > 1
                ? "Berhasil menyelesaikan {$completedCount} laporan perbaikan terkait"
                : "Laporan perbaikan berhasil diselesaikan";
            $this->dispatch('showSuccessToast', $message);
            $this->closeDetailModal();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking repair as completed: ' . $e->getMessage());
            $this->dispatch('showErrorToast', 'Terjadi kesalahan saat menyelesaikan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Get the CSS class for status badge color
     *
     * @param string $status
     * @return string
     */
    public function getStatusBadgeColor($status)
    {
        return match ($status) {
            'Diproses' => 'badge-info',
            'Selesai' => 'badge-success',
            'Diterima' => 'badge-warning',
            'Menunggu' => 'badge-secondary',
            default => 'badge-ghost'
        };
    }

    public function render()
    {
        return view('livewire.penugasan-perbaikan-table', [
            'perbaikanData' => $this->getPerbaikanData(),
            'teknisiList' => $this->teknisiList
        ]);
    }
}
