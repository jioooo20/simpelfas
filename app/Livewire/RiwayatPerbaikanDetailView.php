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
                'status' => $status->perbaikan_status,
                'keterangan' => $this->getStatusDescription($status->perbaikan_status, $perbaikan),
                'oleh' => $status->user?->nama ?? ($perbaikan->perbaikanPetugas->first()?->user?->nama ?? 'Sistem')
            ];
        })->toArray();

        // Prepend pelaporan event at the beginning of history
        array_unshift($this->historyInfo, [
            'tanggal' => $perbaikan->pelaporan->created_at->format('d/m/Y H:i'),
            'status' => 'Dilaporkan',
            'keterangan' => 'Penugasan Perbaikan Fasilitas dibuat',
            'oleh' => $perbaikan->pelaporan->user?->nama . ' (' . ($perbaikan->pelaporan->user?->role?->role_nama ?? 'Pengguna') . ')'
        ]);

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
                    'keterangan' => $this->getImageDescription($status->perbaikan_status)
                ];
            })
            ->values()
            ->toArray();
    } 

    protected function getStatusDescription($status, $perbaikan)
    {
        switch ($status) {
            case 'Dilaporkan':
                return 'Pelaporan kerusakan fasilitas dibuat';
            case 'Diverifikasi':
                return 'Tiket diverifikasi dan diteruskan ke teknisi';
            case 'Ditugaskan':
                return 'Perbaikan ditugaskan kepada teknisi';
            case 'Dalam Proses':
                return 'Perbaikan sedang dalam proses pengerjaan';
            case 'Menunggu Komponen':
                return 'Menunggu komponen pengganti untuk melanjutkan perbaikan';
            case 'Selesai':
                return 'Perbaikan selesai, fasilitas sudah dapat digunakan kembali';
            case 'Dibatalkan':
                return 'Perbaikan dibatalkan';
            default:
                return 'Status perbaikan diperbarui';
        }
    }

    /**
     * Get description for image based on status
     */    protected function getImageDescription($status)
    {
        switch ($status) {
            case 'Dilaporkan':
                return 'Foto saat kerusakan dilaporkan';
            case 'Diverifikasi':
                return 'Foto verifikasi kerusakan';
            case 'Dalam Proses':
                return 'Foto proses perbaikan';
            case 'Menunggu Komponen':
                return 'Foto komponen yang perlu diganti';
            case 'Selesai':
                return 'Foto setelah perbaikan selesai';
            default:
                return 'Dokumentasi foto perbaikan';
        }
    }
    
    /**
     * Set default preview data for UI when no actual data is available
     */
    protected function setDefaultPreviewData()
    {
        // Default perbaikan data
        $this->perbaikan = [
            'id' => null,
            'kode' => 'PBR-001-2',  // Contoh kode dengan versi terbaru
            'deskripsi' => 'Proyektor tidak menyala saat dihidupkan',
            'created_at' => '19/05/2025 09:30',
            'updated_at' => '21/05/2025 14:35',
            'completion_date' => '21/05/2025 14:35',
            'status' => 'Selesai'
        ];
          // Default pelaporan info
        $this->pelaporanInfo = [
            'id' => null,
            'deskripsi' => 'Proyektor tidak menyala saat dihidupkan. Lampu indikator power berkedip merah sebanyak 6 kali secara berulang.',
            'fasilitas' => 'Proyektor Sony VPL-EX455',
            'lokasi' => 'Gedung Informatika Lt. 2',
            'ruang' => 'Lab Komputer',
            'pelapor' => 'Ahmad Santoso',
            'pelapor_role' => 'Dosen',
            'total_laporan' => 3 // Contoh data untuk total laporan
        ];

        // Default teknisi info
        $this->teknisiInfo = [
            'id' => null,
            'nama' => 'Budi Setiawan',
            'kontak' => '08123456789',
            'deskripsi_perbaikan' => 'Berdasarkan pemeriksaan awal, lampu proyektor perlu diganti. Sudah memesan komponen baru, estimasi tiba 2 hari lagi.'
        ];

        // Default history info
        $this->historyInfo = [
            [
                'tanggal' => '19/05/2025 09:30',
                'status' => 'Dilaporkan',
                'keterangan' => 'Penugasan Perbaikan Fasilitas dibuat',
                'oleh' => 'Ahmad Santoso (Dosen)'
            ],
            [
                'tanggal' => '19/05/2025 10:15',
                'status' => 'Diverifikasi',
                'keterangan' => 'Tiket diverifikasi dan diteruskan ke teknisi',
                'oleh' => 'Admin'
            ],
            [
                'tanggal' => '19/05/2025 13:20',
                'status' => 'Dalam Proses',
                'keterangan' => 'Mulai diagnosis masalah',
                'oleh' => 'Budi Setiawan'
            ],
            [
                'tanggal' => '19/05/2025 14:45',
                'status' => 'Menunggu Komponen',
                'keterangan' => 'Perlu penggantian lampu proyektor. Komponen dipesan',
                'oleh' => 'Budi Setiawan'
            ],
            [
                'tanggal' => '20/05/2025 13:30',
                'status' => 'Dalam Proses',
                'keterangan' => 'Komponen diterima, mulai penggantian',
                'oleh' => 'Budi Setiawan'
            ],
            [
                'tanggal' => '21/05/2025 14:35',
                'status' => 'Selesai',
                'keterangan' => 'Perbaikan selesai, proyektor sudah berfungsi normal',
                'oleh' => 'Budi Setiawan'
            ]
        ];

        // Default documentation images
        $this->documentationImages = [
            [
                'url' => 'https://placehold.co/400x300',
                'status' => 'Dilaporkan',
                'tanggal' => '19/05/2025 09:30',
                'keterangan' => 'Lampu indikator menunjukkan kedipan merah 6 kali berulang.'
            ],
            [
                'url' => 'https://placehold.co/400x300',
                'status' => 'Dalam Proses',
                'tanggal' => '20/05/2025 13:45',
                'keterangan' => 'Proses penggantian lampu proyektor dan pembersihan komponen.'
            ],
            [
                'url' => 'https://placehold.co/400x300',
                'status' => 'Selesai',
                'tanggal' => '21/05/2025 14:35',
                'keterangan' => 'Proyektor berfungsi normal setelah penggantian lampu.'
            ]
        ];
    }
}