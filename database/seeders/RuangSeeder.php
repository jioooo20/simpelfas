<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_ruang')->insert([
            [
                'lantai_id' => 9,
                'ruang_kode' => 'RT01',//1
                'ruang_nama' => 'Ruang Teori 01',
                'ruang_keterangan' => 'Ruang ini untuk apa yaa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lantai_id' => 9,
                'ruang_kode' => 'RT02',//2
                'ruang_nama' => 'Ruang Teori 02',
                'ruang_keterangan' => 'Ruang ini untuk apa yaa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lantai_id' => 9,
                'ruang_kode' => 'RT03',//3
                'ruang_nama' => 'Ruang Teori 03',
                'ruang_keterangan' => 'Ruang ini untuk apa yaa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
