<?php

namespace App\Livewire;

use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;
use App\Models\PerbaikanPetugasModel;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RiwayatPerbaikanTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $selectedStatus = '';
    public $page = 1;

    // Properties for sorting and pagination
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // Enable deep-linking with URL parameters
    protected $queryString = [
        'page' => ['except' => 1],
        'search' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];
    
    // Add updatedProperty listeners to reset pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        // Get completed repairs from database
        $riwayatPerbaikan = $this->getRiwayatPerbaikanData();

        return view('livewire.riwayatPerbaikan-table', ['riwayatPerbaikan' => $riwayatPerbaikan]);
    }

    // Navigation methods for pagination
    public function nextPage()
    {
        $this->page = $this->page + 1;
    }

    public function previousPage()
    {
        $this->page = max($this->page - 1, 1);
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }

    /**
     * Reset pagination to first page
     * 
     * @return void
     */
    public function resetPage()
    {
        $this->page = 1;
    }

    /**
     * Reset all filters and return to first page
     * 
     * @return void
     */
    public function resetFilters()
    {
        $this->selectedStatus = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Clear status filter and return to first page
     * 
     * @return void
     */
    public function clearStatusFilter()
    {
        $this->selectedStatus = '';
        $this->resetPage();
    }

    /**
     * Clear search filter and return to first page
     * 
     * @return void
     */
    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Set status filter and return to first page
     * 
     * @param string $status Status to filter by
     * @return void
     */
    public function setStatusFilter($status)
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }

    /**
     * Get completed repair history data from database
     * 
     * This method fetches repair records with 'Selesai' status,
     * groups them by repair code to avoid duplicates,
     * and applies any search or role-based filters.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getRiwayatPerbaikanData()
    {
        // Start building the query - only get repairs with 'Selesai' status
        $query = PerbaikanModel::query()
            ->select([
                'perbaikan_id',
                'perbaikan_kode',
                'perbaikan_deskripsi',
                'pelaporan_id',
                'created_at',
                'updated_at'
            ])
            ->with([
                'pelaporan',
                'pelaporan.fasilitas',
                'pelaporan.fasilitas.ruang',
                'pelaporan.fasilitas.ruang.lantai',
                'pelaporan.fasilitas.ruang.lantai.gedung',
                'pelaporan.fasilitas.barang',
                'perbaikanPetugas.user',
                'latestStatusPerbaikan'
            ])
            ->whereHas('latestStatusPerbaikan', function($q) {
                $q->where('perbaikan_status', 'Selesai');
            });

        // Apply search filter if provided
        if ($this->search) {
            $search = trim($this->search);
            if (preg_match('/^fasilitas_id:(\d+)$/', $search, $matches)) {
                $facilityId = $matches[1];
                $query->whereHas('pelaporan', function($subq) use ($facilityId) {
                    $subq->where('fasilitas_id', $facilityId);
                });
            } else {
                $search = '%' . $search . '%';
                $query->where(function($q) use ($search) {
                    $q->where('perbaikan_kode', 'like', $search)
                      ->orWhere('perbaikan_deskripsi', 'like', $search)
                      ->orWhereHas('pelaporan', function($subq) use ($search) {
                          $subq->where('pelaporan_deskripsi', 'like', $search);
                      })
                      ->orWhereHas('pelaporan.fasilitas.ruang.lantai.gedung', function($subq) use ($search) {
                          $subq->where('gedung_nama', 'like', $search);
                      })
                      ->orWhereHas('pelaporan.fasilitas.ruang', function($subq) use ($search) {
                          $subq->where('ruang_nama', 'like', $search);
                      })
                      ->orWhereHas('perbaikanPetugas.user', function($subq) use ($search) {
                          $subq->where('nama', 'like', $search);
                      });
                });
            }
        }

        // Apply role-based filters for technicians
        $user = Auth::user();
        if ($user && $user->role_id == 3) { // Role 3 is for technicians
            $query->whereHas('perbaikanPetugas', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Define sort options
        $sortField = $this->sortField ?? 'created_at';
        $sortDirection = $this->sortDirection ?? 'desc';
        
        $allPerbaikanData = $query->orderBy($sortField, $sortDirection)->get();
        $groupedByPrefix = $allPerbaikanData->groupBy(function($item) {
            return $this->getKodePerbaikanPrefix($item->perbaikan_kode);
        })->map(function($group) {
            // Ambil item terakhir (terbaru) berdasarkan created_at
            return $group->sortByDesc('created_at')->first();
        })->values();
        
        // Tambahkan filter status di sini
        if ($this->selectedStatus) {
            $groupedByPrefix = $groupedByPrefix->filter(function($item) {
                // Ambil status terbaru dari relasi latestStatusPerbaikan
                $latestStatus = $item->latestStatusPerbaikan ? $item->latestStatusPerbaikan->perbaikan_status : 'Menunggu';
                return $latestStatus === $this->selectedStatus;
            })->values();
        } else {
            // Jika tidak ada filter status khusus, tetap filter hanya yang 'Selesai'
            $groupedByPrefix = $groupedByPrefix->filter(function($item) {
                $latestStatus = $item->latestStatusPerbaikan ? $item->latestStatusPerbaikan->perbaikan_status : 'Menunggu';
                return $latestStatus === 'Selesai';
            })->values();
        }

        // Setup pagination
        $perPage = $this->perPage ?? 10;
        
        $perbaikanData = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedByPrefix->forPage($this->page, $perPage),
            $groupedByPrefix->count(),
            $perPage,
            $this->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Format data untuk view
        return collect($perbaikanData->items())->map(function($item) use ($allPerbaikanData) {
            $pelaporan = $item->pelaporan;
            $fasilitas = $pelaporan->fasilitas ?? null;
            $gedung = $fasilitas && $fasilitas->ruang && $fasilitas->ruang->lantai ? $fasilitas->ruang->lantai->gedung : null;
            $ruang = $fasilitas ? $fasilitas->ruang : null;
            
            // Ambil semua teknisi dari relasi perbaikanPetugas
            $teknisiCollection = $item->perbaikanPetugas->map(function($petugas) {
                return $petugas->user ?? null;
            })->filter()->values();
            
            // Ambil status terbaru dari relasi latestStatusPerbaikan
            $status = $item->latestStatusPerbaikan ? $item->latestStatusPerbaikan->perbaikan_status : 'Menunggu';
            
            $additionalRepairs = 0;
            if ($fasilitas) {
                $additionalRepairs = $allPerbaikanData->filter(function($repairItem) use ($fasilitas, $item) {
                    return $repairItem->pelaporan && $repairItem->pelaporan->fasilitas_id == $fasilitas->fasilitas_id && $repairItem->perbaikan_id != $item->perbaikan_id;
                })->count();
            }
            
            return [
                'id' => $item->perbaikan_id,
                'kode_perbaikan' => $item->perbaikan_kode,
                'deskripsi_masalah' => $pelaporan->pelaporan_deskripsi ?? $item->perbaikan_deskripsi,
                'gedung_nama' => $gedung->gedung_nama ?? '-',
                'ruang_nama' => $ruang->ruang_nama ?? '-',
                'tanggal_selesai' => $item->latestStatusPerbaikan->created_at ?? $item->updated_at,
                'tanggal_lapor' => $item->created_at,
                'updated_at' => $item->updated_at,
                'status' => $status,
                'teknisi_id' => $teknisiCollection->isNotEmpty() ? $teknisiCollection->first()->id : null,
                'teknisi_nama' => $teknisiCollection->isNotEmpty() ? $teknisiCollection->first()->nama : '-',
                'jumlah_teknisi' => $teknisiCollection->count(),
                'fasilitas_id' => $fasilitas->fasilitas_id ?? null,
                'fasilitas_nama' => $fasilitas->barang->barang_nama ?? '-',
                'additional_repairs' => $additionalRepairs
            ];
        });
    }

    /**
     * Mendapatkan prefix dari kode perbaikan
     * 
     * Ekstrak bagian awal kode untuk mengelompokkan perbaikan yang serupa
     * Format kode perbaikan biasanya seperti: PR-123-456 atau PR-123
     * Kita ekstrak bagian PR-123 untuk grouping
     * 
     * @param string $code
     * @return string
     */
    private function getKodePerbaikanPrefix($code)
    {
        // Ekstrak bagian awal kode sampai dengan angka terakhir dari kode utama
        if (preg_match('/^([A-Z]+-\d+)/', $code, $matches)) {
            return $matches[1];
        }
        return $code;
    }
}