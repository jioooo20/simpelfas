<?php

namespace App\Livewire;

use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;
use App\Models\PerbaikanPetugasModel;
use App\Models\GedungModel;
use App\Models\RuangModel;
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

    protected $listeners = [
        'refreshRiwayatPerbaikanTable' => '$refresh'
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

        return view('livewire.riwayatPerbaikan-table', compact('riwayatPerbaikan'));

        // Then apply search filter if provided
        if ($this->search) {
            $search = strtolower(trim($this->search));
            $riwayatPerbaikan = $riwayatPerbaikan->filter(function ($item) use ($search) {
                // Search in multiple fields including technician names
                return str_contains(strtolower($item['kode_perbaikan']), $search)
                    || str_contains(strtolower($item['deskripsi_masalah']), $search)
                    || str_contains(strtolower($item['lokasi']), $search)
                    || str_contains(strtolower($item['gedung_nama']), $search)
                    || str_contains(strtolower($item['ruang_nama']), $search)
                    || str_contains(strtolower($item['teknisi_nama']), $search);
            });
        }

        // For real implementation, use the commented code below
        // $riwayatPerbaikan = $this->getRiwayatPerbaikanData();

        return view('livewire.riwayatPerbaikan-table', compact('riwayatPerbaikan'));
    }

    // Navigation methods for pagination
    public function nextPage()
    {
        $this->setPage($this->page + 1);
    }

    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    // Filter management methods
    public function resetFilters()
    {
        $this->selectedStatus = '';
        $this->search = '';
        $this->resetPage();
    }

    public function clearStatusFilter()
    {
        $this->selectedStatus = '';
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }

    public function goToDetail($perbaikanId)
    {
        return redirect()->route('detail-riwayat-perbaikan', ['id' => $perbaikanId]);
    }
}