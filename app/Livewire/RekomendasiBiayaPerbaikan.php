<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FasilitasModel;
use App\Models\SkorAltModel;
use App\Models\PelaporanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekomendasiBiayaPerbaikan extends Component
{
    public $biayaRekomendasi = [];
    public $userSubmissions = []; // Track which facilities the user has submitted for

    public function mount()
    {
        // Initialize biayaRekomendasi array for each facility
        $facilities = $this->getFacilities();
        foreach ($facilities as $facility) {
            $this->biayaRekomendasi[$facility->fasilitas_id] = '';
            // Check if current user has already submitted for this facility
            $this->userSubmissions[$facility->fasilitas_id] = $this->hasUserSubmitted($facility->fasilitas_id);
        }
    }

    public function hasUserSubmitted($fasilitasId)
    {
        // Get all pelaporan_id for this facility
        $pelaporanIds = PelaporanModel::where('fasilitas_id', $fasilitasId)
            ->whereHas('statusPelaporan', function ($query) {
                $query->whereIn('status_pelaporan', ['Menunggu', 'Diterima']);
            })
            ->pluck('pelaporan_id');

        if ($pelaporanIds->isEmpty()) {
            return false;
        }

        $userId = Auth::id();

        // Check if current user ID exists in any skor_alt_kode for this facility's reports
        return SkorAltModel::whereIn('pelaporan_id', $pelaporanIds)
            ->where('kriteria_id', 4)
            ->where(function ($query) use ($userId) {
                $query->where('skor_alt_kode', 'like', '%-' . $userId . '-%')
                      ->orWhere('skor_alt_kode', 'like', '%-' . $userId);
            })
            ->exists();
    }

    public function submitRekomendasi($fasilitasId)
    {
        // Double check if user already submitted for this facility (real-time check)
        if ($this->hasUserSubmitted($fasilitasId)) {
            $this->dispatch('showErrorToast', 'Anda sudah memberikan rekomendasi biaya untuk fasilitas ini!');
            // Update the component state to reflect the current database state
            $this->userSubmissions[$fasilitasId] = true;
            return;
        }

        $this->validate([
            "biayaRekomendasi.{$fasilitasId}" => 'required|numeric|min:1'
        ], [
            "biayaRekomendasi.{$fasilitasId}.required" => 'Biaya rekomendasi harus diisi',
            "biayaRekomendasi.{$fasilitasId}.numeric" => 'Biaya rekomendasi harus berupa angka',
            "biayaRekomendasi.{$fasilitasId}.min" => 'Biaya rekomendasi minimal 1'
        ]);

        try {
            DB::transaction(function () use ($fasilitasId) {
                // Get all pelaporan_id for this facility with status 'Menunggu' or 'Diterima'
                $pelaporanIds = PelaporanModel::where('fasilitas_id', $fasilitasId)
                    ->whereHas('statusPelaporan', function ($query) {
                        $query->whereIn('status_pelaporan', ['Menunggu', 'Diterima']);
                    })
                    ->pluck('pelaporan_id');

                $nilaiInput = (int) $this->biayaRekomendasi[$fasilitasId];
                $userId = Auth::id();

                foreach ($pelaporanIds as $pelaporanId) {
                    // Check if criteria_id '4' already exists for this pelaporan_id
                    $existingSkor = SkorAltModel::where('pelaporan_id', $pelaporanId)
                        ->where('kriteria_id', 4)
                        ->first();

                    if ($existingSkor) {
                        // Calculate average and update
                        $newAverage = round(($existingSkor->nilai_skor + $nilaiInput) / 2);

                        // Append current user ID to the existing code to track all users
                        $currentCode = $existingSkor->skor_alt_kode;
                        $newCode = $currentCode . '-' . $userId;

                        $existingSkor->update([
                            'nilai_skor' => $newAverage,
                            'skor_alt_kode' => $newCode
                        ]);
                    } else {
                        // Create new record with user ID in the code to track submissions
                        SkorAltModel::create([
                            'skor_alt_kode' => $pelaporanId . '-C4-' . $userId,
                            'pelaporan_id' => $pelaporanId,
                            'kriteria_id' => 4,
                            'nilai_skor' => $nilaiInput
                        ]);
                    }
                }

                // Mark this facility as submitted by current user
                $this->userSubmissions[$fasilitasId] = true;
            });

            // Reset form
            $this->biayaRekomendasi[$fasilitasId] = '';

            $this->dispatch('showSuccessToast', 'Rekomendasi biaya berhasil disimpan!');
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Error: ' . $e->getMessage());
        }
    }

    public function getFacilities()
    {
        return FasilitasModel::with([
            'barang',
            'ruang.lantai.gedung',
            'pelaporan' => function ($query) {
                $query->whereHas('statusPelaporan', function ($statusQuery) {
                    $statusQuery->whereIn('status_pelaporan', ['Menunggu', 'Diterima']);
                });
            },
            'pelaporan.user',
            'pelaporan.statusPelaporan'
        ])
        ->whereHas('pelaporan.statusPelaporan', function ($query) {
            $query->whereIn('status_pelaporan', ['Menunggu', 'Diterima']);
        })
        ->get();
    }

    public function render()
    {
        $facilities = $this->getFacilities();

        return view('livewire.rekomendasi-biaya-perbaikan', [
            'facilities' => $facilities
        ]);
    }
}
