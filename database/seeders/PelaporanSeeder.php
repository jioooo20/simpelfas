<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_pelaporan')->insert([
            [
                'user_id' => 5,
                'fasilitas_id' => 5, //ac
                'pelaporan_kode' => 'PLP-0001',
                'pelaporan_deskripsi' => 'AC tidak berfungsi dengan baik, suhu tidak turun meskipun sudah diatur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'fasilitas_id' => 1, //meja
                'pelaporan_kode' => 'PLP-0002',
                'pelaporan_deskripsi' => 'meja pecah, tidak bisa digunakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'fasilitas_id' => 1, //meja
                'pelaporan_kode' => 'PLR0003',
                'pelaporan_deskripsi' => 'meja pecah, tidak bisa digunakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'fasilitas_id' => 2, //proyektor
                'pelaporan_kode' => 'PLR0004',
                'pelaporan_deskripsi' => 'tidak bisa digunakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'fasilitas_id' => 3, //kursi
                'pelaporan_kode' => 'PLR0005',
                'pelaporan_deskripsi' => 'tidak bisa digunakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'fasilitas_id' => 4, //papan tulis
                'pelaporan_kode' => 'PLR0006',
                'pelaporan_deskripsi' => 'tidak bisa digunakan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
