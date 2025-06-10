<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PelaporanModel;
use App\Models\FasilitasModel;
use App\Models\UserModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\DB;

class LaporanStatistik extends Component
{
    use WithPagination;
    public $search = '';
    public function updatingSearch(){
        $this->resetPage();
    }


   public function render()
{
    $table = PelaporanModel::query()
            ->with([
                'fasilitas.barang',
                'user',
                'statusPelaporan' => function($query) {
                    $query->latest()->limit(1);
                },
                'skorAlternatif',
                'feedback' => function($query) {
                    $query->select('pelaporan_id', 'rating');
                }
            ])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('pelaporan_kode', 'like', '%' . $this->search . '%')
                ->orWhere('pelaporan_deskripsi', 'like', '%' . $this->search . '%')
                ->orWhereHas('fasilitas.barang', function ($q) {
                    $q->where('barang_nama', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

    return view('livewire.laporan-statistik', compact('table'));
}
}
