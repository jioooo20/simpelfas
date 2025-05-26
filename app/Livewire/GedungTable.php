<?php

namespace App\Livewire;

use App\Models\GedungModel;
use Livewire\Component;
use Livewire\WithPagination;

class GedungTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $gedung_id;
    public $gedung_kode;
    public $gedung_nama;
    public $gedung_keterangan;

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;

    protected $listeners = [
        'refreshGedungTable' => '$refresh',
        'gedungCreated' => '$refresh',
        'gedungUpdated' => '$refresh',
        'gedungDeleted' => '$refresh'
    ];

    protected $rules = [
        'gedung_kode' => 'required|string|max:10',
        'gedung_nama' => 'required|string|max:100',
        'gedung_keterangan' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';

        $gedung = GedungModel::where('gedung_kode', 'like', $search)
                            ->orWhere('gedung_nama', 'like', $search)
                            ->orWhere('gedung_keterangan', 'like', $search)
                            ->orderBy('gedung_id', 'asc')
                            ->paginate(6);

        return view('livewire.gedung-table', compact('gedung'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->gedung_id = $id;

        $gedung = GedungModel::findOrFail($id);
        $this->gedung_kode = $gedung->gedung_kode;
        $this->gedung_nama = $gedung->gedung_nama;
        $this->gedung_keterangan = $gedung->gedung_keterangan;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                $existingGedung = GedungModel::where('gedung_kode', $this->gedung_kode)
                    ->where('gedung_id', '!=', $this->gedung_id)
                    ->first();
                if ($existingGedung) {
                    $this->dispatch('showErrorToast', 'Gagal mengubah data, Kode sudah digunakan!');
                    return;
                }
                $gedung = GedungModel::findOrFail($this->gedung_id);
                $gedung->update([
                    'gedung_kode' => $this->gedung_kode,
                    'gedung_nama' => $this->gedung_nama,
                    'gedung_keterangan' => $this->gedung_keterangan,
                ]);

                $this->dispatch('showSuccessToast', 'Data berhasil diperbarui');
                $this->dispatch('gedungUpdated');
            } else {
                $existingGedung = GedungModel::where('gedung_kode', $this->gedung_kode)->first();
                if ($existingGedung) {
                    $this->dispatch('showErrorToast', 'Gagal menambahkan data, Kode sudah digunakan!');
                    return;
                }
                GedungModel::create([
                    'gedung_kode' => $this->gedung_kode,
                    'gedung_nama' => $this->gedung_nama,
                    'gedung_keterangan' => $this->gedung_keterangan,
                ]);

                $this->dispatch('showSuccessToast', 'Data berhasil ditambahkan');
                $this->dispatch('gedungCreated');
            }

            $this->showModal = false;
            $this->resetForm();

            $this->reset(['search']);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $gedung = GedungModel::findOrFail($id);
        $this->gedung_id = $id;
        $this->gedung_kode = $gedung->gedung_kode;
        $this->gedung_nama = $gedung->gedung_nama;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {

            $gedung = GedungModel::findOrFail($this->gedung_id);
            $gedung->delete();

            $this->dispatch('showSuccessToast', 'Gedung berhasil dihapus');
            $this->dispatch('gedungDeleted');

            $this->showDeleteModal = false;
            $this->resetForm();

            $this->reset(['search']);
            $this->resetPage();
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                $this->dispatch('showErrorToast', 'Gedung ini memiliki data lantai terkait. Hapus data lantai terlebih dahulu!');
            } else {
                $this->dispatch('showErrorToast', 'Error: ' . $e->getMessage());
            }
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->gedung_id = null;
        $this->gedung_kode = '';
        $this->gedung_nama = '';
        $this->gedung_keterangan = '';
        $this->resetErrorBag();
        $this->resetValidation();
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
