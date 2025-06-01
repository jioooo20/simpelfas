<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_kriteria')->insert([
            [
                'kriteria_kode' => 'C1',
                'kriteria_nama' => 'Banyak_Laporan',
                'kriteria_jenis' => 'Benefit',
                'w1_mhs' => 0.40,
                'w2_dsn' => 0.20,
                'w3_stf' => 0.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kriteria_kode' => 'C2',
                'kriteria_nama' => 'Skala_Kerusakan',
                'kriteria_jenis' => 'Benefit',
                'w1_mhs' => 0.35,
                'w2_dsn' => 0.25,
                'w3_stf' => 0.35,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kriteria_kode' => 'C3',
                'kriteria_nama' => 'Frekuensi_Penggunaan',
                'kriteria_jenis' => 'Benefit',
                'w1_mhs' => 0.20,
                'w2_dsn' => 0.35,
                'w3_stf' => 0.30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kriteria_kode' => 'C4',
                'kriteria_nama' => 'Biaya_Perbaikan',
                'kriteria_jenis' => 'Cost',
                'w1_mhs' => 0.05,
                'w2_dsn' => 0.20,
                'w3_stf' => 0.15,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
