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
            'fasilitas_kode' => 'RT01MD001',
            'ruang_id' => 1,
            'barang_id' => 1,
            'fasilitas_status' => 'Rusak',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PRYKTR001',
            'ruang_id' => 1,
            'barang_id' => 2,
            'fasilitas_status' => 'Rusak',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02KRS001',
            'ruang_id' => 2,
            'barang_id' => 3,
            'fasilitas_status' => 'Rusak',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01AC1PK001',
            'ruang_id' => 1,
            'barang_id' => 5,
            'fasilitas_status' => 'Rusak',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02AC1PK001',
            'ruang_id' => 2,
            'barang_id' => 5,
            'fasilitas_status' => 'Rusak',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03AC1PK001',
            'ruang_id' => 3,
            'barang_id' => 5,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PC001',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PC002',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PC003',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PC004',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT01PC005',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02PC001',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02PC002',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02PC003',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02PC004',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT02PC005',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03PC001',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03PC002',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03PC003',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03PC004',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'RT03PC005',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
