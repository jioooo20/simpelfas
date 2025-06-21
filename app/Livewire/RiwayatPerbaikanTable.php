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
        $this->teknisiList = UserModel::whereHas('role', function ($q) {
            $q->where('role_nama', 'teknisi');
        })->get();
    }

    public function render()
    {
        $query = StatusPerbaikanModel::query()
            ->with([
                'perbaikan.pelaporan.fasilitas.barang',
                'perbaikan.pelaporan.fasilitas.ruang.lantai.gedung',
                'perbaikan.perbaikanPetugas.user',
            ])
            ->where('perbaikan_status', 'Selesai');

        if (!empty($this->search)) {
            $search = strtolower(trim($this->search));
            $query->whereHas('perbaikan', function ($qPerbaikan) use ($search) {
                $qPerbaikan->where('perbaikan_kode', 'like', '%' . $search . '%')
                    ->orWhereHas('pelaporan', function ($qPelaporan) use ($search) {
                        $qPelaporan->where('pelaporan_deskripsi', 'like', '%' . $search . '%')
                            ->orWhereHas('fasilitas.ruang', function ($qRuang) use ($search) {
                                $qRuang->where('ruang_nama', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('perbaikanPetugas.user', function ($qUser) use ($search) {
                        $qUser->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        if (!empty($this->selectedTeknisi)) {
            $query->whereHas('perbaikan.perbaikanPetugas', function ($q) {
                $q->where('user_id', $this->selectedTeknisi);
            });
        }

        $riwayatPerbaikan = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $riwayatPerbaikan->getCollection()->transform(function ($item) {
            if ($item->perbaikan) { // Pastikan relasi perbaikan ada
                $item->latestCode = $item->perbaikan->perbaikan_kode;
            } else {
                $item->latestCode = 'N/A';
            }
            $item->tanggal_selesai = $item->created_at;
            return $item;
        });

        return view('livewire.riwayatPerbaikan-table', [
            'riwayatPerbaikan' => $riwayatPerbaikan,
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
