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
            'ruang_id' => 1,
            'barang_id' => 1,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS002',
            'ruang_id' => 1,
            'barang_id' => 2,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS003',
            'ruang_id' => 2,
            'barang_id' => 3,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS004',
            'ruang_id' => 1,
            'barang_id' => 5,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS005',
            'ruang_id' => 2,
            'barang_id' => 5,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS006',
            'ruang_id' => 3,
            'barang_id' => 5,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS007',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS008',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS009',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS010',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS011',
            'ruang_id' => 1,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            // Additional 5 with ruang_id 2
            [
            'fasilitas_kode' => 'FAS012',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS013',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS014',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS015',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS016',
            'ruang_id' => 2,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            // Additional 5 with ruang_id 3
            [
            'fasilitas_kode' => 'FAS017',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS018',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS019',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS020',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'fasilitas_kode' => 'FAS021',
            'ruang_id' => 3,
            'barang_id' => 6,
            'fasilitas_status' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
