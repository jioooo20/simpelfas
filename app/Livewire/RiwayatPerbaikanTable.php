<?php

namespace App\Livewire;

use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;
use App\Models\PerbaikanPetugasModel;
use App\Models\User;
use App\Models\UserModel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RiwayatPerbaikanTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $selectedStatus = '';
    public $selectedTeknisi = '';
    public $page = 1;

    // Properties for sorting and pagination
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $teknisiList = [];
    
    // Enable deep-linking with URL parameters
    protected $queryString = [
        'page' => ['except' => 1],
        'search' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'selectedTeknisi' => ['except' => ''],
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
    
    public function updatedSelectedTeknisi()
    {
        $this->resetPage();
    }
    
    public function mount()
    {
        // Ambil semua teknisi (role teknisi = 3, atau sesuaikan dengan model User/Role Anda)
        $this->teknisiList = UserModel::whereHas('role', function($q) {
            $q->where('role_nama', 'teknisi');
        })->get();
    }
    
    public function render()
    {

        // Retrieve completed repairs with the latest status first
        $riwayatPerbaikan = StatusPerbaikanModel::with([
                'perbaikan.pelaporan.fasilitas.ruang.lantai.gedung', 
                'perbaikan.perbaikanPetugas.user',
                // Explicitly load all status history ordered by most recent first
                'perbaikan.statusPerbaikan' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->whereIn('perbaikan_status', ['Selesai'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->unique(function ($item) {
                // Extract the base code without suffix numbers/letters
                $kode = $item->perbaikan->perbaikan_kode;
                return preg_replace('/-\d+[A-Z]*$/i', '', $kode);
            });
            
        // Enhance each item with a latestCode property
        foreach ($riwayatPerbaikan as $item) {
            // Get the base code (e.g., PBR-001 from PBR-001-1)
            $baseCode = preg_replace('/-\d+[A-Z]*$/i', '', $item->perbaikan->perbaikan_kode);
            
            // Find all repair records with the same base code
            $relatedCodes = PerbaikanModel::where('perbaikan_kode', 'LIKE', $baseCode.'%')
                ->orderBy('created_at', 'desc')
                ->get()
                ->pluck('perbaikan_kode')
                ->toArray();
                
            // Get the latest code (first one since we sorted by created_at desc)
            $latestCode = !empty($relatedCodes) ? $relatedCodes[0] : $item->perbaikan->perbaikan_kode;
            
            $item->latestCode = $latestCode;
        }

        // Then apply search filter if provided
        if ($this->search) {
            $search = strtolower(trim($this->search));
            $riwayatPerbaikan = $riwayatPerbaikan->filter(function ($item) use ($search) {
                // Search in multiple fields including technician names
                return str_contains(strtolower($item->latestCode), $search)
                    || str_contains(strtolower($item->perbaikan->perbaikan_kode), $search)
                    || str_contains(strtolower($item->perbaikan->pelaporan->pelaporan_deskripsi), $search)
                    || str_contains(strtolower($item->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung->gedung_nama), $search)
                    || str_contains(strtolower($item->perbaikan->pelaporan->fasilitas->ruang->ruang_nama), $search)
                    || str_contains(strtolower($item->perbaikan->perbaikanPetugas->pluck('user.nama')->join(', ')), $search);
            });
        }

        // Filter by teknisi jika dipilih
        if ($this->selectedTeknisi) {
            $riwayatPerbaikan = $riwayatPerbaikan->filter(function ($item) {
                return $item->perbaikan->perbaikanPetugas->pluck('user_id')->contains($this->selectedTeknisi);
            });
        }

        return view('livewire.riwayatPerbaikan-table', [
            'riwayatPerbaikan' => $riwayatPerbaikan,
            'teknisiList' => $this->teknisiList,
            'selectedTeknisi' => $this->selectedTeknisi,
        ]);
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
        $this->selectedTeknisi = '';
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

    public function setTeknisiFilter($userId)
    {
        $this->selectedTeknisi = $userId;
        $this->resetPage();
    }

    public function goToDetail($perbaikanId)
    {
        return redirect()->route('detail-riwayat-perbaikan', ['id' => $perbaikanId]);
    }

    
}