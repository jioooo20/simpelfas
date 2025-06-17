<?php

namespace App\Repositories;

use App\Models\SkorAltModel;

class SkorAltRepository
{
    public function getSkorLabel(SkorAltModel $skor): string
    {
        $kriteriaNama = $skor->kriteria?->kriteria_nama;

        return match ($kriteriaNama) {
            'Skala_Kerusakan' => match ($skor->nilai_skor) {
                1 => 'Ringan',
                2 => 'Sedang',
                3 => 'Berat',
                default => 'Tidak Diketahui'
            },
            'Frekuensi_Penggunaan' => match ($skor->nilai_skor) {
                1 => 'Jarang',
                2 => 'Sedang',
                3 => 'Sering',
                default => 'Tidak Diketahui'
            },
            default => (string)$skor->nilai_skor
        };
    }
}
