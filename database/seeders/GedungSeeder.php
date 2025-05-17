<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_gedung')->insert([
            [
                'gedung_kode' => 'AA',
                'gedung_nama' => 'Gedung Kantor Pusat',
                'gedung_keterangan' => 'Gedung ini untuk apa yaa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_kode' => 'ST',
                'gedung_nama' => 'Gedung TI dan Sipil',
                'gedung_keterangan' => 'Gedung untuk apa yaa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_kode' => 'AW',
                'gedung_nama' => 'Pusat Informasi',
                'gedung_keterangan' => 'Gedung untuk spill informasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
