<?php

namespace App\Repositories;

use App\Models\PelaporanModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PelaporanRepository
{
    public function StorePelaporan(array $data): PelaporanModel
    {
        $kode = 'PLP-' . strtoupper(Str::random(6));

        $pelaporan = PelaporanModel::create([
            'user_id' => Auth::id() ?? 1,
            'fasilitas_id' => $data['fasilitas_id'],
            'pelaporan_kode' => $kode,
            'pelaporan_deskripsi' => $data['deskripsi'],
            'pelaporan_gambar' => isset($data['gambar']) ? json_encode($data['gambar'], JSON_UNESCAPED_SLASHES) : null,
        ]);

        StatusPelaporanModel::create([
            'pelaporan_id' => $pelaporan->pelaporan_id,
            'status_pelaporan' => 'Menunggu'
        ]);

        return $pelaporan;
    }


    public function getFormattedLaporanData()
    {
        $laporan = PelaporanModel::with(['fasilitas', 'statusPelaporan' => function ($query) {
            $query->latest('created_at');
        }])
            ->orderBy('created_at', 'desc')
            ->get();

        return $laporan->map(function ($item) {
            $latestStatus = $item->statusPelaporan->first();
            return [
                'id' => $item->pelaporan_id,
                'judul' => $item->pelaporan_deskripsi ?? '-',
                'tanggal' => $item->created_at->format('d M Y'),
                'status' => $latestStatus ? $latestStatus->status_pelaporan : 'Belum Ada Status',
            ];
        });
    }

}
