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
            [
            'barang_kode' => 'BRG004',
            'barang_nama' => 'Papan Tulis',
            'deskripsi' => 'Papan tulis magnetik',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG005',
            'barang_nama' => 'AC',
            'deskripsi' => 'Pendingin ruangan 1PK',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG006',
            'barang_nama' => 'Komputer',
            'deskripsi' => 'Komputer untuk lab',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG007',
            'barang_nama' => 'Speaker',
            'deskripsi' => 'Speaker aktif ruang kelas',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG008',
            'barang_nama' => 'Lemari Arsip',
            'deskripsi' => 'Lemari penyimpanan dokumen',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG009',
            'barang_nama' => 'Router WiFi',
            'deskripsi' => 'Router untuk koneksi internet',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'barang_kode' => 'BRG010',
            'barang_nama' => 'UPS',
            'deskripsi' => 'UPS untuk backup listrik',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
