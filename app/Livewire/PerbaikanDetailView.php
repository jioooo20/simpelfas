<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;

class PerbaikanDetailView extends Component
{
    public $perbaikanId;
    public $perbaikan;
    public $statusTerakhir;
    public $lokasi;
    public $fasilitas;
    public $teknisi;
    public $histori;
    public $totalPerbaikan;

    protected $listeners = ['refreshPerbaikanDetail' => 'refreshData'];

    public function mount($perbaikanId)
    {
        $this->perbaikanId = $perbaikanId;
        $this->loadData();
    }

    public function refreshData()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->perbaikan = PerbaikanModel::with(['pelaporan.user.role', 'perbaikanPetugas.user'])
            ->findOrFail($this->perbaikanId);
            
        $this->statusTerakhir = $this->perbaikan->statusPerbaikan()->latest()->first();
        
        // Data lain seperti lokasi, fasilitas, teknisi, histori
        $this->lokasi = $this->perbaikan->pelaporan->fasilitas->ruang->lantai->gedung ?? null;
        $this->fasilitas = $this->perbaikan->pelaporan->fasilitas ?? null;
        $this->teknisi = $this->perbaikan->perbaikanPetugas->first()->user ?? null;
        
        // Ambil riwayat status perbaikan
        $this->histori = $this->perbaikan->statusPerbaikan()->orderBy('created_at', 'asc')->get();
        
        // Hitung total perbaikan dengan kode yang mirip
        $prefix = preg_replace('/\d+$/', '', $this->perbaikan->perbaikan_kode);
        $this->totalPerbaikan = PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->count();
    }

    public function render()
    {
        return view('livewire.perbaikan-detail-view');
    }
}
