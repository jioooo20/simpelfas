<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_barang')->insert([
            [
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Meja kotak mahal',
                'deskripsi' => 'Meja untuk dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Proyektor',
                'deskripsi' => 'Proyektor untuk presentasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Kursi',
                'deskripsi' => 'Kursi untuk ruang kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
