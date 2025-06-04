<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkorAltSeeder extends Seeder
{
    public function run(): void
    {
        $kriteriaIds = [2, 3];
        $skorData = [];

        foreach (range(1, 6) as $pelaporanId) {
            foreach ($kriteriaIds as $kriteriaId) {
                $nilai = number_format(rand(1, 3), 2, '.', '');
                $skorData[] = [
                    'skor_alt_kode' => "{$pelaporanId}-C{$kriteriaId}",
                    'pelaporan_id' => $pelaporanId,
                    'kriteria_id' => $kriteriaId,
                    'nilai_skor' => $nilai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('m_skor_alt')->insert($skorData);
    }
}
