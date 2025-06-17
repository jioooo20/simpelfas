<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;
use Illuminate\Support\Facades\DB;

class PerbaikanUpdateForm extends Component
{
    use WithFileUploads;
    public $perbaikanId;
    public $status;
    public $gambar;
    public $showModal = false;

    protected $listeners = ['openUpdateModal' => 'openModal'];

    public function mount($perbaikanId)
    {
        $this->perbaikanId = $perbaikanId;
    }

    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['status', 'gambar']);
    }

    public function updatePerbaikan()
    {
        try {
            $this->validate([
                'status' => 'required',
                'gambar' => 'required|image|max:5120', // wajib upload gambar, maksimal 5MB
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showErrorToast', 'Gambar harus diupload dan status harus dipilih.');
            return;
        }

        $perbaikan = PerbaikanModel::find($this->perbaikanId);
        if (!$perbaikan) {
            $this->dispatch('showErrorToast', 'Data perbaikan tidak ditemukan.');
            return;
        }

        // Ambil status terakhir dari tabel status_perbaikan
        $statusTerakhir = StatusPerbaikanModel::where('perbaikan_id', $perbaikan->perbaikan_id)
            ->orderByDesc('created_at')->first();
        $statusTerakhirValue = $statusTerakhir ? $statusTerakhir->perbaikan_status : $perbaikan->status;

        // Validasi: Tidak bisa update ke Selesai jika status terakhir Menunggu
        if ($statusTerakhirValue === 'Menunggu' && $this->status === 'Selesai') {
            $this->dispatch('showErrorToast', 'Status tidak bisa langsung diubah menjadi Selesai dari Menunggu.');
            return;
        }

        // Validasi: Hanya bisa update sekali per status
        $sudahUpdateStatus = StatusPerbaikanModel::where('perbaikan_id', $perbaikan->perbaikan_id)
            ->where('perbaikan_status', $this->status)->exists();
        if ($sudahUpdateStatus) {
            $this->dispatch('showErrorToast', 'Status ini sudah pernah diupdate sebelumnya.');
            return;
        }        // Simpan gambar jika ada
        $gambarPath = null;
        $gambar = $this->gambar;
        $filename = uniqid() . '.' . $gambar->getClientOriginalExtension();
        if ($this->status === 'Diproses') {
            $gambarPath = $gambar->storeAs('perbaikan/menunggu', $filename, 'public');
        } elseif ($this->status === 'Selesai') {
            $gambarPath = $gambar->storeAs('perbaikan/selesai', $filename, 'public');
        }

        // Update hanya di tabel t_status_perbaikan
        // Cari semua perbaikan dengan kode yang mirip (prefix)
        $prefix = preg_replace('/\d+$/', '', $perbaikan->perbaikan_kode);
        $perbaikanList = PerbaikanModel::where('perbaikan_kode', 'like', $prefix . '%')->get();

        foreach ($perbaikanList as $item) {
            // Simpan status perbaikan baru di tabel status_perbaikan
            $idpelaporan = $item->pelaporan_id;
            $url = '/users/laporan-detail/' . $idpelaporan;
            $pelaporID = DB::table('m_pelaporan')
                ->where('pelaporan_id', $idpelaporan)
                ->pluck('user_id')
                ->toArray();

            //khusus notif user
            if ($this->status === 'Diproses') {
                sendRoleNotification(
                    [],
                    'Fasilitas Sedang Diperbaiki',
                    'Perbaikan fasilitas sedang dalam proses. Tim teknisi telah mulai mengerjakan perbaikan yang diminta.',
                    $url,
                    $pelaporID
                );
            }
            StatusPerbaikanModel::create([
                'perbaikan_id' => $item->perbaikan_id,
                'perbaikan_gambar' => $gambarPath,
                'perbaikan_status' => $this->status,
            ]);
        }

        //notif admin sarpra
        if ($this->status === 'Selesai') {
            sendRoleNotification(
                ['2'], //sarpra
                'Fasilitas Selesai Diperbaiki',
                'Perbaikan fasilitas ' . $perbaikan->pelaporan->fasilitas->barang->barang_nama . ' telah selesai dikerjakan. Silakan pantau dan evaluasi hasil perbaikan.',
                route('penugasan-perbaikan')
            );
            sendRoleNotification(
                ['1'], //admin
                'Fasilitas Selesai Diperbaiki',
                'Perbaikan fasilitas ' . $perbaikan->pelaporan->fasilitas->barang->barang_nama . ' telah selesai dikerjakan. Silakan pantau dan evaluasi hasil perbaikan.',
                route('laporan.index')
            );
        }

        $this->dispatch('showSuccessToast', 'Status perbaikan berhasil diupdate!');
        $this->closeModal();

        // Refresh komponen pada halaman detail
        $this->dispatch('refreshPerbaikanDetail');

        // Refresh tabel perbaikan jika ada di halaman lain
        $this->dispatch('refreshPerbaikanTable');
    }

    public function render()
    {
        return view('livewire.perbaikan-update-form');
    }
}
