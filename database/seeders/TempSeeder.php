<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class TempSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Kosongkan tabel terkait untuk menghindari duplikasi saat seeding ulang
        // Urutan penghapusan penting karena adanya foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('m_skor_alt')->truncate();
        DB::table('t_status_pelaporan')->truncate();
        DB::table('m_pelaporan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =================================================================
        // LOGIKA DARI PelaporanSeeder.php
        // =================================================================
        $dummySource = public_path('storage/dummy');
        $imageFiles = [];
        if (File::exists($dummySource)) {
            $imageFiles = collect(File::files($dummySource))
                ->map(fn($file) => 'storage/dummy/' . $file->getFilename())
                ->toArray();
        }

        $randomImages = fn() => !empty($imageFiles) && fake()->boolean(70)
            ? json_encode(fake()->randomElements($imageFiles, rand(1, 3)), JSON_UNESCAPED_SLASHES)
            : null;

        $dataLaporan = [
            // Data laporan Anda...
            ['user_id' => 6, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0001', 'pelaporan_deskripsi' => 'AC tidak berfungsi dengan baik, suhu tidak turun.'],
            ['user_id' => 4, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0007', 'pelaporan_deskripsi' => 'AC kembali mati total, tidak ada respon dari remote.'],
            ['user_id' => 5, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0008', 'pelaporan_deskripsi' => 'Suara AC sangat berisik dan mengganggu.'],
            ['user_id' => 4, 'fasilitas_id' => 1, 'pelaporan_kode' => 'PLP-0002', 'pelaporan_deskripsi' => 'Meja pecah, tidak bisa digunakan.'],
            ['user_id' => 5, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0003', 'pelaporan_deskripsi' => 'Proyektor tidak bisa digunakan, gambar buram.'],
            ['user_id' => 6, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0009', 'pelaporan_deskripsi' => 'Kabel proyektor putus.'],
            ['user_id' => 6, 'fasilitas_id' => 3, 'pelaporan_kode' => 'PLP-0004', 'pelaporan_deskripsi' => 'Kaki kursi patah.'],
            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0005', 'pelaporan_deskripsi' => 'Papan tulis sulit dihapus, spidol membekas.'],
            ['user_id' => 5, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0006', 'pelaporan_deskripsi' => 'Papan tulis retak di bagian tengah.'],
            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0010', 'pelaporan_deskripsi' => 'Permukaan papan tulis menggelembung.'],
            ['user_id' => 6, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0011', 'pelaporan_deskripsi' => 'Penghapus papan tulis hilang.'],
        ];

        $kriteriaIds = [1, 2, 3, 4]; // C1, C2, C3, C4

        foreach ($dataLaporan as $row) {
            // == Langkah 1: Buat Laporan (m_pelaporan) ==
            $randomDate = fake()->dateTimeBetween('-6 months', 'now');
            $pelaporanId = DB::table('m_pelaporan')->insertGetId([
                ...$row,
                'pelaporan_gambar' => $randomImages(),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            // =================================================================
            // LOGIKA DARI StatusPelaporanSeeder.php
            // =================================================================
            $statusesToInsert = [];
            $reportCreationTime = Carbon::parse($randomDate);
            $lastStatusTime = $reportCreationTime;
            $finalStatus = 'Menunggu'; // Status default

            // Status pertama selalu 'Menunggu'
            $statusesToInsert[] = [
                'pelaporan_id' => $pelaporanId,
                'status_pelaporan' => 'Menunggu',
                'created_at' => $reportCreationTime,
                'updated_at' => $reportCreationTime,
            ];

            // Buat skenario status acak
            $scenario = rand(1, 4);
            if ($scenario <= 2) { // Skenario: Selesai
                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 24))->addMinutes(rand(0, 59));
                $statusesToInsert[] = ['pelaporan_id' => $pelaporanId, 'status_pelaporan' => 'Diproses', 'created_at' => $diprosesTime, 'updated_at' => $diprosesTime];
                $lastStatusTime = $diprosesTime;

                $selesaiTime = $lastStatusTime->copy()->addHours(rand(2, 48))->addMinutes(rand(0, 59));
                $statusesToInsert[] = ['pelaporan_id' => $pelaporanId, 'status_pelaporan' => 'Selesai', 'created_at' => $selesaiTime, 'updated_at' => $selesaiTime];
                $finalStatus = 'Selesai';

            } elseif ($scenario === 3) { // Skenario: Sedang Diproses
                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 12))->addMinutes(rand(0, 59));
                $statusesToInsert[] = ['pelaporan_id' => $pelaporanId, 'status_pelaporan' => 'Diproses', 'created_at' => $diprosesTime, 'updated_at' => $diprosesTime];
                $finalStatus = 'Diproses';
            }
            // Skenario 4: 'Menunggu' (tidak perlu blok kode tambahan)

            DB::table('t_status_pelaporan')->insert($statusesToInsert);

            // =================================================================
            // LOGIKA DARI AltSkorSeeder.php
            // =================================================================
            $skorToInsert = [];
            foreach ($kriteriaIds as $kriteriaId) {
                // Aturan: Lewati C1 & C4 jika status masih 'Menunggu'
                if ($finalStatus === 'Menunggu' && in_array($kriteriaId, [1, 4])) {
                    continue;
                }

                $nilai_skor = 0;
                switch ($kriteriaId) {
                    case 1: $nilai_skor = rand(1, 3); break;
                    case 2: $nilai_skor = rand(1, 3); break;
                    case 3: $nilai_skor = rand(1, 3); break;
                    case 4: $nilai_skor = rand(10, 500) * 1000; break;
                }

                $skorToInsert[] = [
                    'skor_alt_kode' => $pelaporanId . '-C' . $kriteriaId,
                    'pelaporan_id' => $pelaporanId,
                    'kriteria_id' => $kriteriaId,
                    'nilai_skor' => $nilai_skor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($skorToInsert)) {
                DB::table('m_skor_alt')->insert($skorToInsert);
            }
        }
    }
}
