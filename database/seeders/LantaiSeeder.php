<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LantaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_lantai')->insert([
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST1B',
                'lantai_nama' => 'Lantai 1 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST1T',
                'lantai_nama' => 'Lantai 1 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST2B',
                'lantai_nama' => 'Lantai 2 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST2T',
                'lantai_nama' => 'Lantai 2 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST3B',
                'lantai_nama' => 'Lantai 3 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST3T',
                'lantai_nama' => 'Lantai 3 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST4B',
                'lantai_nama' => 'Lantai 4 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST4T',
                'lantai_nama' => 'Lantai 4 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST5B',
                'lantai_nama' => 'Lantai 5 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST5T',
                'lantai_nama' => 'Lantai 5 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST6B',
                'lantai_nama' => 'Lantai 6 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST6T',
                'lantai_nama' => 'Lantai 6 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST7B',
                'lantai_nama' => 'Lantai 7 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST7T',
                'lantai_nama' => 'Lantai 7 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST8B',
                'lantai_nama' => 'Lantai 8 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gedung_id' => 2,
                'lantai_kode' => 'ST8T',
                'lantai_nama' => 'Lantai 8 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
