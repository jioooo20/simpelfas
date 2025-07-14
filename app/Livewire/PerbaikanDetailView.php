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
    public $pelaporanTerkait = [];
    public $semuaFoto = [];

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
        try {
            // Inisialisasi properti dengan nilai default
            $this->pelaporanTerkait = collect([]);
            $this->semuaFoto = [];
            
            $this->perbaikan = PerbaikanModel::with(['pelaporan.user.role', 'perbaikanPetugas.user'])
                ->findOrFail($this->perbaikanId);
                
            $this->statusTerakhir = $this->perbaikan->statusPerbaikan()->latest()->first();
            
            // Data lain seperti lokasi, fasilitas, teknisi, histori
            $this->lokasi = $this->perbaikan->pelaporan->fasilitas->ruang ?? null;
            $this->fasilitas = $this->perbaikan->pelaporan->fasilitas ?? null;
            $this->teknisi = $this->perbaikan->perbaikanPetugas->first()->user ?? null;
            
            // Ambil riwayat status perbaikan
            $this->histori = $this->perbaikan->statusPerbaikan()->orderBy('created_at', 'asc')->get();
            
            // Hitung total perbaikan dengan kode yang mirip
            $prefix = preg_replace('/\d+$/', '', $this->perbaikan->perbaikan_kode);
            $this->totalPerbaikan = PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->count();
            
            if ($this->perbaikan && $this->perbaikan->perbaikan_kode) {
                // Ambil semua pelaporan terkait berdasarkan kode perbaikan yang mirip
                $prefix = preg_replace('/-\d+[A-Z]*$/i', '', $this->perbaikan->perbaikan_kode);
                $perbaikanTerkait = PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->pluck('pelaporan_id');
                
                if ($perbaikanTerkait->isNotEmpty()) {
                    $this->pelaporanTerkait = \App\Models\PelaporanModel::whereIn('pelaporan_id', $perbaikanTerkait)->get();
                    
                    // Kumpulkan semua foto dari pelaporan terkait
                    foreach ($this->pelaporanTerkait as $pelaporan) {
                        if ($pelaporan && $pelaporan->pelaporan_gambar) {
                            $fotoArr = json_decode($pelaporan->pelaporan_gambar, true);
                            if (is_array($fotoArr) && count($fotoArr) > 0) {
                                foreach ($fotoArr as $foto) {
                                    $fotoPath = str_starts_with($foto, 'storage/') ? asset($foto) : asset('storage/' . $foto);
                                    $this->semuaFoto[] = [
                                        'path' => $fotoPath,
                                        'pelaporan_id' => $pelaporan->pelaporan_id,
                                        'pelaporan_kode' => $pelaporan->pelaporan_kode ?? '',
                                        'created_at' => $pelaporan->created_at
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error atau tampilkan pesan error
            logger()->error('Error in PerbaikanDetailView::loadData: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.perbaikan-detail-view');
    }
}
