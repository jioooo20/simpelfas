<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatusPelaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat saat seeding ulang
        DB::table('t_status_pelaporan')->delete();

        // 1. Ambil semua laporan yang sudah ada di database
        $laporan = DB::table('m_pelaporan')->get(['pelaporan_id', 'created_at']);

        foreach ($laporan as $lp) {
            $statusesToInsert = [];

            // Jadikan waktu pembuatan laporan sebagai Carbon instance
            $reportCreationTime = Carbon::parse($lp->created_at);

            // 2. Status pertama untuk setiap laporan PASTI 'Menunggu'
            $statusMenunggu = [
                'pelaporan_id' => $lp->pelaporan_id,
                'status_pelaporan' => 'Menunggu',
                'created_at' => $reportCreationTime,
                'updated_at' => $reportCreationTime,
            ];
            $statusesToInsert[] = $statusMenunggu;

            // Waktu terakhir sebuah status dibuat, dimulai dari waktu laporan dibuat
            $lastStatusTime = $reportCreationTime;

            // 3. Buat skenario status yang bervariasi secara acak
            $scenario = rand(1, 4);

            // Skenario 1 & 2: Laporan selesai diproses (paling umum)
            if ($scenario <= 2) {
                // Tambah status 'Diproses'
                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 24))->addMinutes(rand(0, 59));
                $statusesToInsert[] = [
                    'pelaporan_id' => $lp->pelaporan_id,
                    'status_pelaporan' => 'Diproses',
                    'created_at' => $diprosesTime,
                    'updated_at' => $diprosesTime,
                ];
                $lastStatusTime = $diprosesTime;

                // Tambah status 'Selesai' (Gunakan 'Selesai' agar konsisten dengan query chart)
                $selesaiTime = $lastStatusTime->copy()->addHours(rand(2, 48))->addMinutes(rand(0, 59));
                $statusesToInsert[] = [
                    'pelaporan_id' => $lp->pelaporan_id,
                    'status_pelaporan' => 'Selesai',
                    'created_at' => $selesaiTime,
                    'updated_at' => $selesaiTime,
                ];
            }
            // Skenario 3: Laporan sedang diproses
            elseif ($scenario === 3) {
                // Tambah status 'Diproses'
                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 12))->addMinutes(rand(0, 59));
                $statusesToInsert[] = [
                    'pelaporan_id' => $lp->pelaporan_id,
                    'status_pelaporan' => 'Diproses',
                    'created_at' => $diprosesTime,
                    'updated_at' => $diprosesTime,
                ];
            }
            // Skenario 4: Laporan masih baru (hanya status 'Menunggu')
            // Tidak perlu melakukan apa-apa, karena status 'Menunggu' sudah ditambahkan di awal.

            // Masukkan semua status untuk laporan ini ke database
            DB::table('t_status_pelaporan')->insert($statusesToInsert);
        }
    }
}
