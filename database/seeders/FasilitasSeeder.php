<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_fasilitas')->insert([
            [
            'fasilitas_kode' => 'FAS001',
            'fasilitas_nama' => 'AC',
            'ruang_id' => 1,
            'barang_id' => 1,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS002',
            'fasilitas_nama' => 'Proyektor',
            'ruang_id' => 1,
            'barang_id' => 2,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS003',
            'fasilitas_nama' => 'Whiteboard',
            'ruang_id' => 2,
            'barang_id' => 3,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
