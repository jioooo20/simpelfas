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
    public $id;
    public $kode_perbaikan;
    public $fasilitas_id;
    public $gedung_id;
    public $ruang_id;
    public $lokasi;
    public $deskripsi_masalah;
    public $status;
    public $teknisi_id;
    public $tanggal_lapor;

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;

    public $selectedStatus = '';

    protected $listeners = [
        'refreshPerbaikanTable' => '$refresh',
        'perbaikanCreated' => '$refresh',
        'perbaikanUpdated' => '$refresh',
        'perbaikanDeleted' => '$refresh'
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
        $perbaikan = collect([
            [
                'id' => 1,
                'kode_perbaikan' => 'PRB-001',
                'fasilitas_id' => 1,
                'gedung_id' => 1,
                'ruang_id' => 1,
                'lokasi' => 'Gedung A - Ruang 101',
                'deskripsi_masalah' => 'AC tidak dingin',
                'status' => 'Diproses',
                'teknisi_id' => 1,
                'tanggal_lapor' => '2023-11-01',
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
                'status' => 'Diproses',
                'teknisi_id' => 1,
                'tanggal_lapor' => '2023-11-02',
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'kode_perbaikan' => 'PRB-004',
                'fasilitas_id' => 1,
                'gedung_id' => 2,
                'ruang_id' => 3,
                'lokasi' => 'Gedung B - Ruang 201',
                'deskripsi_masalah' => 'AC berisik',
                'status' => 'Selesai',
                'teknisi_id' => 2,
                'tanggal_lapor' => '2023-11-04',
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
            ['id' => 1, 'nama' => 'Teknisi A', 'role_id' => 2],
            ['id' => 2, 'nama' => 'Teknisi B', 'role_id' => 2],
        ]);

        // First map the items to include all related data
        $perbaikan = $perbaikan->map(function ($item) use ($fasilitas, $gedung, $ruang, $teknisi) {
            $item['fasilitas_nama'] = $fasilitas->firstWhere('id', $item['fasilitas_id'])['nama'] ?? '-';
            $item['gedung_nama'] = $gedung->firstWhere('id', $item['gedung_id'])['gedung_nama'] ?? '-';
            $item['ruang_nama'] = $ruang->firstWhere('id', $item['ruang_id'])['ruang_nama'] ?? '-';
            $item['teknisi_nama'] = $teknisi->firstWhere('id', $item['teknisi_id'])['nama'] ?? '-';
            return $item;
        });

        // Then apply status filter if selected
        if ($this->selectedStatus) {
            $perbaikan = $perbaikan->filter(function ($item) {
                return $item['status'] === $this->selectedStatus;
            });
        }

        // Then apply search filter if provided
        if ($this->search) {
            $search = strtolower(trim($this->search));
            $perbaikan = $perbaikan->filter(function ($item) use ($search) {
                // Search in multiple fields
                return str_contains(strtolower($item['kode_perbaikan']), $search)
                    || str_contains(strtolower($item['deskripsi_masalah']), $search)
                    || str_contains(strtolower($item['lokasi']), $search)
                    || str_contains(strtolower($item['gedung_nama']), $search)
                    || str_contains(strtolower($item['ruang_nama']), $search)
                    || str_contains(strtolower($item['teknisi_nama']), $search);
            });
        }

        return view('livewire.perbaikanFasilitas-table', compact('perbaikan'));
    }

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

    protected function getPerbaikanQuery()
    {
        $query = PerbaikanModel::query()
            ->join(/* your existing joins */);

        if ($this->search) {
            $query->where(/* your existing search logic */);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        return $query;
    }
}
