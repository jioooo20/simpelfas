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
            'barang_kode' => 'MD',
            'barang_nama' => 'Meja Dosen',
            'deskripsi' => 'Meja untuk dosen',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'PRYKTR',
            'barang_nama' => 'Proyektor',
            'deskripsi' => 'Proyektor untuk presentasi',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'KRS',
            'barang_nama' => 'Kursi',
            'deskripsi' => 'Kursi ',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'PPN',
            'barang_nama' => 'Papan Tulis',
            'deskripsi' => 'Untuk menulis materi kuliah',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'AC1PK',
            'barang_nama' => 'AC 1PK',
            'deskripsi' => 'Pendingin ruangan 1PK',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'PC',
            'barang_nama' => 'Komputer',
            'deskripsi' => 'Komputer untuk kegiatan',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'SPK',
            'barang_nama' => 'Speaker',
            'deskripsi' => 'Pengeras suara',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'LMARSP',
            'barang_nama' => 'Lemari Arsip',
            'deskripsi' => 'Lemari penyimpanan dokumen',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'RTR',
            'barang_nama' => 'Router WiFi',
            'deskripsi' => 'Router untuk koneksi internet',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'UPS',
            'barang_nama' => 'Unit Power Supply',
            'deskripsi' => 'Unit Power Supply untuk backup listrik',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
