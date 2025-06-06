<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatusPelaporanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        for ($i = 1; $i <= 6; $i++) {
            // Jika pelaporan_id ganjil → hanya 'Menunggu'
            if ($i % 2 !== 0) {
                DB::table('t_status_pelaporan')->insert([
                    'pelaporan_id' => $i,
                    'status_pelaporan' => 'Menunggu',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                // pelaporan_id genap → status berurutan (Menunggu → Diproses → Diterima)
                DB::table('t_status_pelaporan')->insert([
                    [
                        'pelaporan_id' => $i,
                        'status_pelaporan' => 'Menunggu',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'pelaporan_id' => $i,
                        'status_pelaporan' => 'Diproses',
                        'created_at' => $now->copy()->addHours(6),
                        'updated_at' => $now->copy()->addHours(6),
                    ],
                    [
                        'pelaporan_id' => $i,
                        'status_pelaporan' => 'Diterima',
                        'created_at' => $now->copy()->addHours(12),
                        'updated_at' => $now->copy()->addHours(12),
                    ],
                ]);
            }
        }
    }
}
