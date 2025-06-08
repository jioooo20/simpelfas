<?php

namespace App\Livewire;

use App\Models\PerbaikanModel;
use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\RuangModel;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PerbaikanFasilitasTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $selectedStatus = '';

    public $selectedFacilityId = null;
    public $selectedFacilityName = null;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $page = 1;

    protected $listeners = [
        'refreshPerbaikanTable' => '$refresh',
        'perbaikanCreated' => '$refresh',
        'perbaikanUpdated' => '$refresh',
        'perbaikanDeleted' => '$refresh',
        'showAllRepairsForFacility' => 'showAllForFacility',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'perPage' => ['except' => 10],
        'groupByFasilitas' => ['except' => true],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $rules = [
        'kode_perbaikan' => 'required|string|max:15',
        'fasilitas_id' => 'required',
        'gedung_id' => 'required',
        'ruang_id' => 'required',
        'deskripsi_masalah' => 'required|string',
        'status' => 'required|string',
        'teknisi_id' => 'nullable',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->getPerbaikanQuery();
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
        $allPerbaikanData = $query->orderBy($this->sortField, $this->sortDirection)->get();
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
        }
        $perbaikanData = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedByPrefix->forPage($this->page ?: 1, $this->perPage),
            $groupedByPrefix->count(),
            $this->perPage,
            $this->page ?: 1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $perbaikan = collect($perbaikanData->items())->map(function($item) use ($allPerbaikanData) {
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
                'tanggal_perbaikan' => $item->created_at,
                'status' => $status,
                'teknisi_collection' => $teknisiCollection,
                'teknisi_nama' => $teknisiCollection->isNotEmpty() ? $teknisiCollection->first()->nama : '-',
                'jumlah_teknisi' => $teknisiCollection->count(),
                'fasilitas_id' => $fasilitas->fasilitas_id ?? null,
                'fasilitas_nama' => $fasilitas->barang->barang_nama ?? '-',
                'additional_repairs' => $additionalRepairs
            ];
        });
        return view('livewire.perbaikanFasilitas-table', [
            'perbaikan' => $perbaikan,
            'perbaikanData' => $perbaikanData
        ]);
    }

    public function getPerbaikanQuery()
    {
        return PerbaikanModel::with([
            'pelaporan.fasilitas.barang',
            'pelaporan.fasilitas.ruang.lantai.gedung',
            'pelaporan.user',
            'latestStatusPerbaikan',
            'perbaikanPetugas.user'
        ]);
    }

    private function getKodePerbaikanPrefix($kode)
    {
        return preg_replace('/\d+$/', '', $kode);
    }

    public function updatePerbaikanMassal($perbaikanId, $data)
    {
        $perbaikan = PerbaikanModel::find($perbaikanId);
        if (!$perbaikan) return;
        $prefix = $this->getKodePerbaikanPrefix($perbaikan->perbaikan_kode);
        PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->update($data);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function goToDetail($perbaikanId)
    {
        return redirect()->route('detail-perbaikan', ['id' => $perbaikanId]);
    }
    
    // Tambahkan method untuk filter status
    public function setStatusFilter($status)
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }
    
    public function clearStatusFilter()
    {
        $this->selectedStatus = '';
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->selectedStatus = '';
        $this->resetPage();
    }
    
    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }
    
    // Method untuk mendapatkan nama fasilitas dari ID dalam pencarian
    public function getFacilityNameFromSearch()
    {
        if (preg_match('/^fasilitas_id:(\d+)$/', $this->search, $matches)) {
            $facilityId = $matches[1];
            $facility = FasilitasModel::with('barang')->find($facilityId);
            if ($facility && $facility->barang) {
                return $facility->barang->barang_nama;
            }
        }
        return null;
    }
}
