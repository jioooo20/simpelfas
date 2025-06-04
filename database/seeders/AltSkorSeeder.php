<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AltSkorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_skor_alt')->insert([
            [
                'skor_alt_kode' => '1-C1',
                'pelaporan_id' => 1,
                'kriteria_id' => 1,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '1-C2',
                'pelaporan_id' => 1,
                'kriteria_id' => 2,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '1-C3',
                'pelaporan_id' => 1,
                'kriteria_id' => 3,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '1-C4',
                'pelaporan_id' => 1,
                'kriteria_id' => 4,
                'nilai_skor' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '2-C1',
                'pelaporan_id' => 2,
                'kriteria_id' => 1,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '2-C2',
                'pelaporan_id' => 2,
                'kriteria_id' => 2,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '2-C3',
                'pelaporan_id' => 2,
                'kriteria_id' => 3,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '2-C4',
                'pelaporan_id' => 2,
                'kriteria_id' => 4,
                'nilai_skor' => 60200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '3-C1',
                'pelaporan_id' => 3,
                'kriteria_id' => 1,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '3-C2',
                'pelaporan_id' => 3,
                'kriteria_id' => 2,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '3-C3',
                'pelaporan_id' => 3,
                'kriteria_id' => 3,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '3-C4',
                'pelaporan_id' => 3,
                'kriteria_id' => 4,
                'nilai_skor' => 60200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '4-C1',
                'pelaporan_id' => 4,
                'kriteria_id' => 1,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '4-C2',
                'pelaporan_id' => 4,
                'kriteria_id' => 2,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '4-C3',
                'pelaporan_id' => 4,
                'kriteria_id' => 3,
                'nilai_skor' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '4-C4',
                'pelaporan_id' => 4,
                'kriteria_id' => 4,
                'nilai_skor' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '5-C1',
                'pelaporan_id' => 5,
                'kriteria_id' => 1,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '5-C2',
                'pelaporan_id' => 5,
                'kriteria_id' => 2,
                'nilai_skor' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '5-C3',
                'pelaporan_id' => 5,
                'kriteria_id' => 3,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '5-C4',
                'pelaporan_id' => 5,
                'kriteria_id' => 4,
                'nilai_skor' => 11000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '6-C1',
                'pelaporan_id' => 6,
                'kriteria_id' => 1,
                'nilai_skor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '6-C2',
                'pelaporan_id' => 6,
                'kriteria_id' => 2,
                'nilai_skor' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '6-C3',
                'pelaporan_id' => 6,
                'kriteria_id' => 3,
                'nilai_skor' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skor_alt_kode' => '6-C4',
                'pelaporan_id' => 6,
                'kriteria_id' => 4,
                'nilai_skor' => 230000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
