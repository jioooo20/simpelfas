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
        // Sample data for demonstration
        // In a real implementation, this would fetch from the database
        $riwayatPerbaikan = collect([
            [
                'id' => 1,
                'kode_perbaikan' => 'PRB-001',
                'fasilitas_id' => 1,
                'gedung_id' => 1,
                'ruang_id' => 1,
                'lokasi' => 'Gedung A - Ruang 101',
                'deskripsi_masalah' => 'AC tidak dingin',
                'status' => 'Selesai',
                'teknisi_id' => 1,
                'tanggal_lapor' => '2023-11-01',
                'tanggal_selesai' => '2023-11-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'kode_perbaikan' => 'PRB-002',
                'fasilitas_id' => 2,
                'gedung_id' => 1,
                'ruang_id' => 2,
                'lokasi' => 'Gedung A - Ruang 102',
                'deskripsi_masalah' => 'Proyektor tidak menyala',
                'status' => 'Selesai',
                'teknisi_id' => 1,
                'tanggal_lapor' => '2023-11-02',
                'tanggal_selesai' => '2023-11-06',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'kode_perbaikan' => 'PRB-003',
                'fasilitas_id' => 3,
                'gedung_id' => 2,
                'ruang_id' => 3,
                'lokasi' => 'Gedung B - Ruang 201',
                'deskripsi_masalah' => 'Kebocoran air',
                'status' => 'Selesai',
                'teknisi_id' => 2,
                'tanggal_lapor' => '2023-11-03',
                'tanggal_selesai' => '2023-11-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $fasilitas = collect([
            ['id' => 1, 'nama' => 'AC'],
            ['id' => 2, 'nama' => 'Proyektor'],
            ['id' => 3, 'nama' => 'Pipa Air'],
        ]);

        $gedung = collect([
            ['id' => 1, 'gedung_nama' => 'Gedung A'],
            ['id' => 2, 'gedung_nama' => 'Gedung B'],
        ]);

        $ruang = collect([
            ['id' => 1, 'ruang_nama' => 'Ruang 101', 'gedung_id' => 1],
            ['id' => 2, 'ruang_nama' => 'Ruang 102', 'gedung_id' => 1],
            ['id' => 3, 'ruang_nama' => 'Ruang 201', 'gedung_id' => 2],
        ]);

        $teknisi = collect([
            ['id' => 1, 'nama' => 'Teknisi A', 'role_id' => 3], 
            ['id' => 2, 'nama' => 'Teknisi B', 'role_id' => 3],
        ]);

        // First map the items to include all related data
        $riwayatPerbaikan = $riwayatPerbaikan->map(function ($item) use ($fasilitas, $gedung, $ruang, $teknisi) {
            $item['fasilitas_nama'] = $fasilitas->firstWhere('id', $item['fasilitas_id'])['nama'] ?? '-';
            $item['gedung_nama'] = $gedung->firstWhere('id', $item['gedung_id'])['gedung_nama'] ?? '-';
            $item['ruang_nama'] = $ruang->firstWhere('id', $item['ruang_id'])['ruang_nama'] ?? '-';
            $item['teknisi_nama'] = $teknisi->firstWhere('id', $item['teknisi_id'])['nama'] ?? '-';
            return $item;
        });

        // Then apply status filter if selected
        if ($this->selectedStatus) {
            $riwayatPerbaikan = $riwayatPerbaikan->filter(function ($item) {
                return $item['status'] === $this->selectedStatus;
            });
        }

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

    /**
     * Get actual repair history data from database
     * This is the method that would be used in a real implementation
     */
    protected function getRiwayatPerbaikanData()
    {
        // Example query to fetch completed or canceled repairs
        $query = PerbaikanModel::query()
            ->select(
                'perbaikan_id as id',
                'perbaikan_kode as kode_perbaikan',
                'perbaikan_deskripsi as deskripsi_masalah',
                't_status_perbaikan.perbaikan_status as status',
                't_status_perbaikan.created_at as tanggal_selesai',
                'users.id as teknisi_id',
                'users.name as teknisi_nama',
                'gedung.gedung_id',
                'gedung.gedung_nama',
                'ruang.ruang_id',
                'ruang.ruang_nama',
                't_perbaikan.created_at as tanggal_lapor',
                't_perbaikan.updated_at'
            )
            ->join('t_status_perbaikan', 't_perbaikan.perbaikan_id', '=', 't_status_perbaikan.perbaikan_id')
            ->join('t_pelaporan', 't_perbaikan.pelaporan_id', '=', 't_pelaporan.pelaporan_id')
            ->join('m_gedung as gedung', 't_pelaporan.gedung_id', '=', 'gedung.gedung_id')
            ->join('m_ruang as ruang', 't_pelaporan.ruang_id', '=', 'ruang.ruang_id')
            ->leftJoin('t_perbaikan_petugas', 't_perbaikan.perbaikan_id', '=', 't_perbaikan_petugas.perbaikan_id')
            ->leftJoin('users', 't_perbaikan_petugas.user_id', '=', 'users.id')
            ->whereIn('t_status_perbaikan.perbaikan_status', ['Selesai', 'Dibatalkan']);

        // Apply search filter if provided
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('t_perbaikan.perbaikan_kode', 'like', $search)
                  ->orWhere('t_perbaikan.perbaikan_deskripsi', 'like', $search)
                  ->orWhere('gedung.gedung_nama', 'like', $search)
                  ->orWhere('ruang.ruang_nama', 'like', $search)
                  ->orWhere('users.name', 'like', $search);
            });
        }

        // Apply status filter if selected
        if ($this->selectedStatus) {
            $query->where('t_status_perbaikan.perbaikan_status', $this->selectedStatus);
        }

        // Get user role and apply additional filters for technicians
        $user = Auth::user();
        if ($user && $user->role_id == 3) { // Assuming role_id 3 is for technicians
            $query->whereHas('perbaikanPetugas', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Order by most recent completion date
        $query->orderBy('t_status_perbaikan.created_at', 'desc');

        return $query->paginate(10);
    }
}