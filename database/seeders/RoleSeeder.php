<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['role_id' => 1, 'role_kode' => 'ADM', 'role_nama' => 'Administrator', 'role_deskripsi' => 'Administrator sistem dengan akses penuh'],
            ['role_id' => 2, 'role_kode' => 'SRPR', 'role_nama' => 'Sarana Prasarana', 'role_deskripsi' => 'Mengelola sarana dan prasarana kampus'],
            ['role_id' => 3, 'role_kode' => 'TKN', 'role_nama' => 'Teknisi', 'role_deskripsi' => 'Melakukan perbaikan dan pemeliharaan peralatan'],
            ['role_id' => 4, 'role_kode' => 'STF', 'role_nama' => 'Staff', 'role_deskripsi' => 'Pengguna layanan fasilitas kampus'],
            ['role_id' => 5, 'role_kode' => 'DSN', 'role_nama' => 'Dosen', 'role_deskripsi' => 'Pengguna layanan fasilitas kampus'],
            ['role_id' => 6, 'role_kode' => 'MHS', 'role_nama' => 'Mahasiswa', 'role_deskripsi' => 'Pengguna layanan fasilitas kampus'],
        ];

        DB::table('m_role')->insert($data);
    }
}
