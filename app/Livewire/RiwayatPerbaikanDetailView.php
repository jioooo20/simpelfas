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

    /**
     * Load data perbaikan dan semua informasi terkait
     */    protected function loadPerbaikanData()
    {
        if (!$this->id) {
            // Default data for preview if no ID is provided
            $this->setDefaultPreviewData();
            return;
        }

        // Get perbaikan data with all necessary relationships
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
                'statusPerbaikan' => function($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->find($this->id);        if (!$perbaikan) {
            // If perbaikan not found, set default preview data
            $this->setDefaultPreviewData();
            return;
        }
          // Set perbaikan data        // Get the latest status
        $latestStatus = $perbaikan->statusPerbaikan->count() > 0 
            ? $perbaikan->statusPerbaikan->sortByDesc('created_at')->first()->perbaikan_status 
            : 'Menunggu';
            
        // Get the latest repair code
        $baseCode = preg_replace('/-\d+[A-Z]*$/i', '', $perbaikan->perbaikan_kode);
        $latestCode = $perbaikan->perbaikan_kode;
        
        // Find all repair records with the same base code
        $relatedCodes = \App\Models\PerbaikanModel::where('perbaikan_kode', 'LIKE', $baseCode.'%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('perbaikan_kode')
            ->toArray();
            
        // Get the latest code (first one since we sorted by created_at desc)
        if (!empty($relatedCodes)) {
            $latestCode = $relatedCodes[0];
        }
        
        // Find the completion date (when status was set to 'Selesai')
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

        // Hitung total laporan dengan kode perbaikan yang sama
        $baseCode = preg_replace('/-\d+[A-Z]*$/i', '', $perbaikan->perbaikan_kode);
        $totalLaporan = \App\Models\PerbaikanModel::where('perbaikan_kode', 'LIKE', $baseCode.'%')
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

        // Set teknisi info (get the first assigned technician)
        $teknisi = $perbaikan->perbaikanPetugas->first()?->user ?? null;
        $this->teknisiInfo = [
            'id' => $teknisi?->id ?? null,
            'nama' => $teknisi?->nama ?? 'Belum ditugaskan',
            'kontak' => $teknisi?->no_hp ?? '-',
            'deskripsi_perbaikan' => $perbaikan->perbaikan_deskripsi ?? 'Tidak ada deskripsi perbaikan'
        ];

        // Set status history
        $this->historyInfo = $perbaikan->statusPerbaikan->map(function($status) use ($perbaikan) {
            return [
                'tanggal' => $status->created_at->format('d/m/Y H:i'),
                'perbaikan_status' => $status->perbaikan_status,
                'oleh' => $status->user?->nama ?? ($perbaikan->perbaikanPetugas->first()?->user?->nama ?? 'Sistem')
            ];
        })->toArray();

        // Set documentation images from status updates
        $this->documentationImages = $perbaikan->statusPerbaikan
            ->filter(function($status) {
                return !empty($status->perbaikan_gambar);
            })
            ->map(function($status) {
                return [
                    'url' => asset('storage/' . $status->perbaikan_gambar),
                    'status' => $status->perbaikan_status,
                    'tanggal' => $status->created_at->format('d/m/Y H:i'),
                ];
            })
            ->values()
            ->toArray();
    } 
}