<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'identitas' => '1111111111',
                'nama' => 'Administrator',
                'password' => Hash::make('12345'),
                'email' => '1111111111@polinema.ac.id',
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'identitas' => '2222222222',
                'nama' => 'Sarana Prasarana',
                'password' => Hash::make('12345'),
                'email' => '2222222222@polinema.ac.id',
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'identitas' => '33333333333',
                'nama' => 'Teknisi',
                'password' => Hash::make('12345'),
                'email' => '33333333333@polinema.ac.id',
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'identitas' => '4444444444',
                'nama' => 'Staff/Dosen/Mahasiswa',
                'password' => Hash::make('12345'),
                'email' => '4444444444@polinema.ac.id',
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('m_user')->insert($data);
    }
}
