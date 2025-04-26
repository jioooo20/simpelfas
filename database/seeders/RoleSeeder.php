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
            ['role_id' => 1, 'role_kode' => 'ADM', 'role_nama' => 'Administrator'],
            ['role_id' => 2, 'role_kode' => 'SRPR', 'role_nama' => 'Sarana Prasarana'],
            ['role_id' => 3, 'role_kode' => 'TKN', 'role_nama' => 'Teknisi'],
            ['role_id' => 4, 'role_kode' => 'USERS', 'role_nama' => 'Staff/Dosen/Mahasiswa'],
        ];

        DB::table('m_role')->insert($data);
    }
}
