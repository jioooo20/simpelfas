<?php

namespace App\Livewire;

use App\Models\StatusPerbaikanModel;
use App\Models\PerbaikanModel;
use App\Models\PerbaikanPetugasModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RiwayatPerbaikanDetailView extends Component
{
    public $id;
    public $perbaikan;
    public $statuses;
    public $pelaporanInfo;
    public $teknisiInfo;
    public $historyInfo;
    public $documentationImages;
    protected $listeners = [
        'refreshDetailView' => '$refresh'
    ];

    public function mount($id = null)
    {
        $this->id = $id;
        $this->loadPerbaikanData();
    }

    public function render()
    {
        return view('livewire.riwayatPerbaikan-detail');
    }

    protected function loadPerbaikanData()
    {
        if (!$this->id) {
            $this->setDefaultPreviewData();
            return;
        }

        $perbaikan = PerbaikanModel::with([
            'pelaporan',
            'pelaporan.user',
            'pelaporan.fasilitas',
            'pelaporan.fasilitas.barang',
            'pelaporan.fasilitas.ruang',
            'pelaporan.fasilitas.ruang.lantai',
            'pelaporan.fasilitas.ruang.lantai.gedung',
            'perbaikanPetugas',
            'perbaikanPetugas.user',
            'statusPerbaikan' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])->find($this->id);

        if (!$perbaikan) {
            $this->setDefaultPreviewData();
            return;
        }

        $latestStatus = $perbaikan->statusPerbaikan->count() > 0
            ? $perbaikan->statusPerbaikan->sortByDesc('created_at')->first()->perbaikan_status
            : 'Menunggu';

        // Get the latest repair code
        $baseCode = preg_replace('/-\d+[A-Z]*$/i', '', $perbaikan->perbaikan_kode);
        $latestCode = $perbaikan->perbaikan_kode;

        $relatedCodes = \App\Models\PerbaikanModel::where('perbaikan_kode', 'LIKE', $baseCode . '%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('perbaikan_kode')
            ->toArray();

        if (!empty($relatedCodes)) {
            $latestCode = $relatedCodes[0];
        }

        $completionDate = null;
        $completionStatus = $perbaikan->statusPerbaikan->firstWhere('perbaikan_status', 'Selesai');
        if ($completionStatus) {
            $completionDate = $completionStatus->created_at->format('d/m/Y H:i');
        }

        $this->perbaikan = [
            'id' => $perbaikan->perbaikan_id,
            'kode' => $latestCode,
            'deskripsi' => $perbaikan->perbaikan_deskripsi,
            'created_at' => $perbaikan->created_at->format('d/m/Y H:i'),
            'updated_at' => $completionDate ?? $perbaikan->updated_at->format('d/m/Y H:i'),
            'completion_date' => $completionDate,
            'status' => $latestStatus
        ];        // Set pelaporan info
        $fasilitas = $perbaikan->pelaporan->fasilitas ?? null;
        $ruang = $fasilitas?->ruang ?? null;
        $lantai = $ruang?->lantai ?? null;
        $gedung = $lantai?->gedung ?? null;

        $baseCode = preg_replace('/-\d+[A-Z]*$/i', '', $perbaikan->perbaikan_kode);
        $totalLaporan = \App\Models\PerbaikanModel::where('perbaikan_kode', 'LIKE', $baseCode . '%')
            ->count();

        $this->pelaporanInfo = [
            'id' => $perbaikan->pelaporan->pelaporan_id ?? null,
            'deskripsi' => $perbaikan->pelaporan->pelaporan_deskripsi ?? 'Tidak ada deskripsi',
            'fasilitas' => $fasilitas?->barang?->barang_nama ?? 'Tidak diketahui',
            'lokasi' => ($gedung?->gedung_nama ?? 'Gedung tidak diketahui') . ' ' . ($lantai?->lantai_nama ?? '?') . ' - ' . ($ruang?->ruang_nama ?? 'Ruang tidak diketahui'),
            'ruang' => $ruang?->ruang_nama ?? 'Ruang tidak diketahui',
            'pelapor' => $perbaikan->pelaporan->user?->nama ?? 'Tidak diketahui',
            'pelapor_role' => $perbaikan->pelaporan->user?->role?->role_nama ?? 'Pengguna',
            'total_laporan' => $totalLaporan
        ];

        $teknisi = $perbaikan->perbaikanPetugas->first()?->user ?? null;
        $this->teknisiInfo = [
            'id' => $teknisi?->id ?? null,
            'nama' => $teknisi?->nama ?? 'Belum ditugaskan',
            'kontak' => $teknisi?->no_hp ?? '-',
            'deskripsi_perbaikan' => $perbaikan->perbaikan_deskripsi ?? 'Tidak ada deskripsi perbaikan'
        ];
        $this->historyInfo = $perbaikan->statusPerbaikan->map(function ($status) use ($perbaikan) {
            return [
                'tanggal' => $status->created_at->format('d/m/Y H:i'),
                'perbaikan_status' => $status->perbaikan_status,
                'oleh' => $status->user?->nama ?? ($perbaikan->perbaikanPetugas->first()?->user?->nama ?? 'Sistem')
            ];
        })->toArray();

        $this->documentationImages = $perbaikan->statusPerbaikan
            ->filter(function ($status) {
                return !empty($status->perbaikan_gambar);
            })
            ->map(function ($status) {
                return [
                    'url' => $status->perbaikan_gambar,
                    'status' => $status->perbaikan_status,
                    'tanggal' => $status->created_at->format('d/m/Y H:i'),
                ];
            })
            ->values()
            ->toArray();
    }
}
