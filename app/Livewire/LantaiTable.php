<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LantaiModel;
use App\Models\GedungModel;

class LantaiTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filterGedungId = '';
    public $lantai_id;
    public $gedung_id;
    public $lantai_kode;
    public $lantai_nama;
    public $lantai_deskripsi;
    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;
    public $selectedGedungKode = '';
    public $lantai_kode_suffix = '';

    protected $listeners = [
        'lantaiCreated' => '$refresh',
        'lantaiUpdated' => '$refresh',
        'lantaiDeleted' => '$refresh'
    ];

    protected $rules = [
        'gedung_id' => 'required',
        'lantai_kode' => 'required|string|max:50',
        'lantai_kode_suffix' => 'required|string|max:20',
        'lantai_nama' => 'required|string|max:255',
        'lantai_deskripsi' => 'nullable|string|max:255'
    ];

    public function mount()
    {
        // Initialize properties
        $this->resetInputFields();
    }

    public function render()
    {
        $lantai = LantaiModel::with('gedung')
            ->where(function ($query) {
                $query->where('lantai_kode', 'like', '%' . $this->search . '%')
                    ->orWhere('lantai_nama', 'like', '%' . $this->search . '%')
                    ->orWhere('lantai_deskripsi', 'like', '%' . $this->search . '%');
            });

        if (!empty($this->filterGedungId)) {
            $lantai->where('gedung_id', $this->filterGedungId);
        }

        $lantai = $lantai->orderBy('lantai_id', 'asc')
            ->paginate(6);

        $gedungs = GedungModel::all();

        return view('livewire.lantai-table', [
            'lantai' => $lantai,
            'gedungs' => $gedungs
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
        $this->resetInputFields();

        $this->isEditing = true;
        $this->lantai_id = $id;

        $lantai = LantaiModel::with('gedung')->findOrFail($id);
        $this->gedung_id = $lantai->gedung_id;
        $this->lantai_kode = $lantai->lantai_kode;
        $this->lantai_nama = $lantai->lantai_nama;
        $this->lantai_deskripsi = $lantai->lantai_deskripsi;

        if ($lantai->gedung) {
            $this->selectedGedungKode = $lantai->gedung->gedung_kode;

            // Debug the input values
            $debugInfo = "Original lantai_kode: " . $this->lantai_kode . ", selectedGedungKode: " . $this->selectedGedungKode;
            $this->debugLog($debugInfo);

            if (strpos($this->lantai_kode, $this->selectedGedungKode) === 0) {
                $this->lantai_kode_suffix = substr($this->lantai_kode, strlen($this->selectedGedungKode));

                if (substr($this->lantai_kode_suffix, 0, 1) === '-') {
                    $this->lantai_kode_suffix = substr($this->lantai_kode_suffix, 1);
                }

                $this->debugLog("Suffix extracted: " . $this->lantai_kode_suffix);
            } else {
                $this->lantai_kode_suffix = $this->lantai_kode;
                $this->debugLog("Pattern didn't match, using full code as suffix: " . $this->lantai_kode_suffix);
            }
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->updateLantaiKode();

        $messages = [
            'lantai_kode_suffix.required' => 'Kode lantai tidak boleh kosong.',
        ];

        $this->validate([
            'gedung_id' => 'required',
            'lantai_kode_suffix' => 'required|string|max:20',
            'lantai_kode' => 'required|string|max:50',
            'lantai_nama' => 'required|string|max:255',
            'lantai_deskripsi' => 'nullable|string|max:255'
        ], $messages);

        if ($this->isEditing) {
            $existingLantai = LantaiModel::where('lantai_kode', $this->lantai_kode)
                ->where('lantai_id', '!=', $this->lantai_id)
                ->where('gedung_id', $this->gedung_id)
                ->first();
            if ($existingLantai) {
                $this->dispatch('showErrorToast', 'Gagal mengubah data, Kode lantai pada gedung ini sudah digunakan!');
                return;
            }
            $lantai = LantaiModel::find($this->lantai_id);
            $lantai->update([
                'gedung_id' => $this->gedung_id,
                'lantai_kode' => $this->lantai_kode,
                'lantai_nama' => $this->lantai_nama,
                'lantai_deskripsi' => $this->lantai_deskripsi,
            ]);

            $this->dispatch('showSuccessToast', 'Data lantai berhasil diperbarui!');
            $this->dispatch('lantaiUpdated');
        } else {
            $existingLantai = LantaiModel::where('lantai_kode', $this->lantai_kode)
                ->where('gedung_id', $this->gedung_id)
                ->first();
            if ($existingLantai) {
                $this->dispatch('showErrorToast', 'Gagal menambahkan data, Kode lantai pada gedung ini sudah digunakan!');
                return;
            }
            LantaiModel::create([
                'gedung_id' => $this->gedung_id,
                'lantai_kode' => $this->lantai_kode,
                'lantai_nama' => $this->lantai_nama,
                'lantai_deskripsi' => $this->lantai_deskripsi,
            ]);

            $this->dispatch('showSuccessToast', 'Data lantai berhasil ditambahkan!');
            $this->dispatch('lantaiCreated');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->lantai_id = $id;
        $lantai = LantaiModel::findOrFail($id);
        $this->lantai_kode = $lantai->lantai_kode;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $lantai = LantaiModel::find($this->lantai_id);

            if (!$lantai) {
                $this->dispatch('showErrorToast', 'Data lantai tidak ditemukan!');
                $this->closeModal();
                return;
            }

            if ($lantai->ruang()->count() > 0) {
                $this->dispatch('showErrorToast', 'Lantai ini memiliki data ruang terkait. Hapus data ruang terlebih dahulu!');
                $this->closeModal();
                return;
            }

            $lantai->delete();
            $this->dispatch('showSuccessToast', 'Data lantai berhasil dihapus!');
            $this->dispatch('lantaiDeleted');
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
        $this->lantai_id = null;
        $this->gedung_id = '';
        $this->lantai_kode = '';
        $this->lantai_nama = '';
        $this->lantai_deskripsi = '';
        $this->selectedGedungKode = '';
        $this->lantai_kode_suffix = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterGedungId()
    {
        $this->resetPage();
    }

    public function updatedGedungId()
    {
        if (!empty($this->gedung_id)) {
            $gedung = GedungModel::find($this->gedung_id);
            if ($gedung) {
                $this->selectedGedungKode = $gedung->gedung_kode;
                $this->updateLantaiKode();
            }
        } else {
            $this->selectedGedungKode = '';
            $this->lantai_kode = '';
            $this->lantai_kode_suffix = '';
        }
    }

    public function updatedLantaiKodeSuffix()
    {
        $this->updateLantaiKode();
    }

    private function updateLantaiKode()
    {
        if (!empty($this->selectedGedungKode)) {
            if (!empty($this->lantai_kode_suffix)) {
                $this->lantai_kode = $this->selectedGedungKode . '' . $this->lantai_kode_suffix;
            } else {
                $this->lantai_kode = $this->selectedGedungKode . '';
            }
        } else {
            $this->lantai_kode = '';
        }
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

    private function debugLog($message) //jangan dihapus jir yg ini
    {
        // You can comment out this line when not debugging
        // $this->dispatch('showSuccessToast', 'Debug: ' . $message);
    }
}
