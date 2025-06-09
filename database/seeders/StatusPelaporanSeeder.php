<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPelaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_status_pelaporan')->insert([
            [
                'pelaporan_id' => 1,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pelaporan_id' => 2,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pelaporan_id' => 3,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pelaporan_id' => 4,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pelaporan_id' => 5,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pelaporan_id' => 6,
                'status_pelaporan' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
