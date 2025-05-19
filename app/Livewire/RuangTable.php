<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RuangModel;
use App\Models\LantaiModel;
use App\Models\GedungModel;

class RuangTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filterGedungId = '';
    public $filterLantaiId = '';
    public $ruang_id;
    public $lantai_id;
    public $ruang_kode;
    public $ruang_nama;
    public $ruang_keterangan;
    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;
    public $lantaiOptions = [];

    protected $listeners = [
        'ruangCreated' => '$refresh',
        'ruangUpdated' => '$refresh',
        'ruangDeleted' => '$refresh'
    ];

    protected $rules = [
        'lantai_id' => 'required',
        'ruang_kode' => 'required|string|max:50',
        'ruang_nama' => 'required|string|max:255',
        'ruang_keterangan' => 'nullable|string|max:255'
    ];

    public function render()
    {
        $query = RuangModel::with(['lantai', 'lantai.gedung']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('ruang_kode', 'like', '%' . $this->search . '%')
                    ->orWhere('ruang_nama', 'like', '%' . $this->search . '%')
                    ->orWhere('ruang_keterangan', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterGedungId)) {
            $query->whereHas('lantai', function ($q) {
                $q->where('gedung_id', $this->filterGedungId);
            });

            $this->lantaiOptions = LantaiModel::where('gedung_id', $this->filterGedungId)->get();
        } else {
            $this->lantaiOptions = [];
        }

        if (!empty($this->filterLantaiId)) {
            $query->where('lantai_id', $this->filterLantaiId);
        }

        $ruang = $query->orderBy('ruang_id', 'asc')->paginate(6);

        $gedungs = GedungModel::all();
        $lantais = LantaiModel::when($this->filterGedungId, function ($query) {
            return $query->where('gedung_id', $this->filterGedungId);
        })->get();

        return view('livewire.ruang-table', [
            'ruang' => $ruang,
            'gedungs' => $gedungs,
            'lantais' => $lantais
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $this->ruang_id = $id;

        $ruang = RuangModel::findOrFail($id);
        $this->lantai_id = $ruang->lantai_id;
        $this->ruang_kode = $ruang->ruang_kode;
        $this->ruang_nama = $ruang->ruang_nama;
        $this->ruang_keterangan = $ruang->ruang_keterangan;

        $lantai = LantaiModel::find($this->lantai_id);
        if ($lantai) {
            $this->filterGedungId = $lantai->gedung_id;
            $this->lantaiOptions = LantaiModel::where('gedung_id', $this->filterGedungId)->get();
        }

        $this->showModal = true;
    }

    public function updatedFilterGedungId()
    {
        $this->resetPage();
        $this->filterLantaiId = '';
        $this->lantaiOptions = LantaiModel::where('gedung_id', $this->filterGedungId)->get();
    }

    public function updatedFilterLantaiId()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $existingRuang = RuangModel::where('ruang_kode', $this->ruang_kode)
                ->where('ruang_id', '!=', $this->ruang_id)
                ->where('lantai_id', $this->lantai_id)
                ->first();

            if ($existingRuang) {
                $this->dispatch('showErrorToast', 'Gagal mengubah data, Kode ruang pada lantai ini sudah digunakan!');
                return;
            }

            $ruang = RuangModel::find($this->ruang_id);
            $ruang->update([
                'lantai_id' => $this->lantai_id,
                'ruang_kode' => $this->ruang_kode,
                'ruang_nama' => $this->ruang_nama,
                'ruang_keterangan' => $this->ruang_keterangan,
            ]);

            $this->dispatch('showSuccessToast', 'Data ruang berhasil diperbarui!');
            $this->dispatch('ruangUpdated');
        } else {
            $existingRuang = RuangModel::where('ruang_kode', $this->ruang_kode)
                ->where('lantai_id', $this->lantai_id)
                ->first();

            if ($existingRuang) {
                $this->dispatch('showErrorToast', 'Gagal menambahkan data, Kode ruang pada lantai ini sudah digunakan!');
                return;
            }

            RuangModel::create([
                'lantai_id' => $this->lantai_id,
                'ruang_kode' => $this->ruang_kode,
                'ruang_nama' => $this->ruang_nama,
                'ruang_keterangan' => $this->ruang_keterangan,
            ]);

            $this->dispatch('showSuccessToast', 'Data ruang berhasil ditambahkan!');
            $this->dispatch('ruangCreated');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->ruang_id = $id;
        $ruang = RuangModel::findOrFail($id);
        $this->ruang_kode = $ruang->ruang_kode;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $ruang = RuangModel::find($this->ruang_id);

            if (!$ruang) {
                $this->dispatch('showErrorToast', 'Data ruang tidak ditemukan!');
                $this->closeModal();
                return;
            }

            $ruang->delete();
            $this->dispatch('showSuccessToast', 'Data ruang berhasil dihapus!');
            $this->dispatch('ruangDeleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->ruang_id = null;
        $this->lantai_id = '';
        $this->ruang_kode = '';
        $this->ruang_nama = '';
        $this->ruang_keterangan = '';
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
}
