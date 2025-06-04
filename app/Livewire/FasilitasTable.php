<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\LantaiModel;
use App\Models\RuangModel;
use App\Models\BarangModel;

class FasilitasTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $gedungFilter = '';
    public $lantaiFilter = '';
    public $ruangFilter = '';

    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $selectedGedung = '';
    public $selectedLantai = '';
    public $selectedRuang = '';
    public $selectedBarang = '';
    public $fasilitasKode = '';
    public $fasilitasNumber = '';
    public $fasilitasStatus = 'Baik';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'gedungFilter' => ['except' => ''],
        'lantaiFilter' => ['except' => ''],
        'ruangFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGedungFilter()
    {
        $this->lantaiFilter = '';
        $this->ruangFilter = '';
        $this->resetPage();
    }

    public function updatingLantaiFilter()
    {
        $this->ruangFilter = '';
        $this->resetPage();
    }

    public function updatingRuangFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->editingId = null;
        $this->resetForm();
    }

    public function editFacility($id)
    {
        $this->editingId = $id;
        $facility = FasilitasModel::with(['ruang.lantai.gedung', 'barang'])->find($id);

        $this->selectedGedung = $facility->ruang->lantai->gedung->gedung_id;
        $this->selectedLantai = $facility->ruang->lantai->lantai_id;
        $this->selectedRuang = $facility->ruang_id;
        $this->selectedBarang = $facility->barang_id;
        $this->fasilitasKode = $facility->fasilitas_kode;
        $this->fasilitasStatus = $facility->fasilitas_status;

        // Extract number from existing facility code
        $ruangKode = $facility->ruang->ruang_kode;
        $barangKode = $facility->barang->barang_kode;
        $baseCode = $ruangKode . $barangKode;
        $this->fasilitasNumber = str_replace($baseCode, '', $facility->fasilitas_kode);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->selectedGedung = '';
        $this->selectedLantai = '';
        $this->selectedRuang = '';
        $this->selectedBarang = '';
        $this->fasilitasKode = '';
        $this->fasilitasNumber = '';
        $this->fasilitasStatus = 'Baik';
        $this->resetErrorBag();
    }

    public function updatedSelectedGedung()
    {
        $this->selectedLantai = '';
        $this->selectedRuang = '';
        $this->updateFasilitasKode();
    }

    public function updatedSelectedLantai()
    {
        $this->selectedRuang = '';
        $this->updateFasilitasKode();
    }

    public function updatedSelectedRuang()
    {
        $this->updateFasilitasKode();
    }

    public function updatedSelectedBarang()
    {
        $this->updateFasilitasKode();
    }

    public function updatedFasilitasNumber()
    {
        // Format the number with leading zeros
        if ($this->fasilitasNumber) {
            $this->fasilitasNumber = str_pad($this->fasilitasNumber, 3, '0', STR_PAD_LEFT);
        }
        $this->updateFasilitasKode();
    }

    private function updateFasilitasKode()
    {
        if ($this->selectedRuang && $this->selectedBarang) {
            $ruang = RuangModel::find($this->selectedRuang);
            $barang = BarangModel::find($this->selectedBarang);

            if ($ruang && $barang) {
                $baseCode = $ruang->ruang_kode . $barang->barang_kode;
                $this->fasilitasKode = $baseCode . $this->fasilitasNumber;
            }
        } else {
            $this->fasilitasKode = '';
        }
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function save()
    {
        $rules = [
            'selectedRuang' => 'required',
            'selectedBarang' => 'required',
            'fasilitasNumber' => 'required|numeric',
            'fasilitasStatus' => 'required',
        ];

        // Update facility code before validation
        $this->updateFasilitasKode();

        if ($this->editingId) {
            $rules['fasilitasKode'] = 'required|unique:t_fasilitas,fasilitas_kode,' . $this->editingId . ',fasilitas_id';
        } else {
            $rules['fasilitasKode'] = 'required|unique:t_fasilitas,fasilitas_kode';
        }

        $messages = [
            'selectedRuang.required' => 'Ruangan harus dipilih.',
            'selectedBarang.required' => 'Barang harus dipilih.',
            'fasilitasNumber.required' => 'Nomor fasilitas harus diisi.',
            'fasilitasNumber.numeric' => 'Nomor fasilitas harus berupa angka.',
            'fasilitasKode.required' => 'Kode fasilitas harus diisi.',
            'fasilitasKode.unique' => 'Kode fasilitas sudah digunakan.',
            'fasilitasStatus.required' => 'Status harus dipilih.',
        ];

        try {
            $this->validate($rules, $messages);

            $data = [
                'ruang_id' => $this->selectedRuang,
                'barang_id' => $this->selectedBarang,
                'fasilitas_kode' => $this->fasilitasKode,
                'fasilitas_status' => $this->fasilitasStatus,
            ];

            if ($this->editingId) {
                FasilitasModel::find($this->editingId)->update($data);
                $this->dispatch('showSuccessToast', 'Fasilitas berhasil diperbarui!');
            } else {
                FasilitasModel::create($data);
                $this->dispatch('showSuccessToast', 'Fasilitas berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if the error is specifically about duplicate facility code
            if ($e->validator->errors()->has('fasilitasKode')) {
            $this->dispatch('showErrorToast', 'Kode fasilitas sudah digunakan. Silakan gunakan kode yang berbeda.');
            } else {
            $this->dispatch('showErrorToast', 'Periksa kembali data yang diisi.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function confirmDelete()
    {
        if ($this->deleteId) {
            try {
                FasilitasModel::find($this->deleteId)->delete();
                $this->dispatch('showSuccessToast', 'Fasilitas berhasil dihapus!');
                $this->closeDeleteModal();
            } catch (\Exception $e) {
                $this->dispatch('showErrorToast', 'Terjadi kesalahan saat menghapus data.');
            }
        }
    }

    public function render()
    {
        // Get filtered data
        $facilities = FasilitasModel::with(['ruang.lantai.gedung', 'barang'])
            ->when($this->search, function($query) {
                $query->where('fasilitas_kode', 'like', '%' . $this->search . '%')
                    ->orWhereHas('ruang', function($q) {
                        $q->where('ruang_nama', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('barang', function($q) {
                        $q->where('barang_nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->gedungFilter, function($query) {
                $query->whereHas('ruang.lantai.gedung', function($q) {
                    $q->where('gedung_id', $this->gedungFilter);
                });
            })
            ->when($this->lantaiFilter, function($query) {
                $query->whereHas('ruang.lantai', function($q) {
                    $q->where('lantai_id', $this->lantaiFilter);
                });
            })
            ->when($this->ruangFilter, function($query) {
                $query->where('ruang_id', $this->ruangFilter);
            })
            ->orderBy('fasilitas_id', 'asc')
            ->paginate($this->perPage);

        // filter
        $gedungs = GedungModel::all();
        $lantais = $this->gedungFilter ? LantaiModel::where('gedung_id', $this->gedungFilter)->get() : collect();
        $ruangs = $this->lantaiFilter ? RuangModel::where('lantai_id', $this->lantaiFilter)->get() : collect();

        // opsi filter form
        $allGedungs = GedungModel::all();
        $formLantais = $this->selectedGedung ? LantaiModel::where('gedung_id', $this->selectedGedung)->get() : collect();
        $formRuangs = $this->selectedLantai ? RuangModel::where('lantai_id', $this->selectedLantai)->get() : collect();
        $barangs = BarangModel::all();

        return view('livewire.fasilitas-table', [
            'facilities' => $facilities,
            'gedungs' => $gedungs,
            'lantais' => $lantais,
            'ruangs' => $ruangs,
            'allGedungs' => $allGedungs,
            'formLantais' => $formLantais,
            'formRuangs' => $formRuangs,
            'barangs' => $barangs,
        ]);
    }
}
