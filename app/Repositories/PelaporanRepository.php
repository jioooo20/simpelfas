<?php

namespace App\Repositories;

use App\Models\PelaporanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PelaporanRepository
{
    public function StorePelaporan(array $data): PelaporanModel
    {
        $kode = 'PLP-' . strtoupper(Str::random(6));

        return PelaporanModel::create([
            'user_id' => Auth::id() ?? 1,
            'fasilitas_id' => $data['fasilitas_id'],
            'pelaporan_kode' => $kode,
            'pelaporan_deskripsi' => $data['deskripsi'],
            'pelaporan_gambar' => $data['gambar'] ?? null,
        ]);
    }
}
