<?php


/*
 * PelaporanSeeder.php
 */

//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\File;
//
//class PelaporanSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     */
//    public function run(): void
//    {
//        $dummySource = public_path('storage/dummy');
//
//        $imageFiles = [];
//        if (File::exists($dummySource)) {
//            $imageFiles = collect(File::files($dummySource))
//                ->map(fn($file) => 'storage/dummy/' . $file->getFilename())
//                ->toArray();
//        }
//
//        $randomImages = fn() => !empty($imageFiles) && fake()->boolean(70)
//            ? json_encode(fake()->randomElements($imageFiles, rand(1, 3)), JSON_UNESCAPED_SLASHES)
//            : null;
//
//        $data = [
//            // Laporan untuk AC 1PK (fasilitas_id: 5)
//            ['user_id' => 6, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0001', 'pelaporan_deskripsi' => 'AC tidak berfungsi dengan baik, suhu tidak turun.'],
//            ['user_id' => 4, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0007', 'pelaporan_deskripsi' => 'AC kembali mati total, tidak ada respon dari remote.'],
//            ['user_id' => 5, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0008', 'pelaporan_deskripsi' => 'Suara AC sangat berisik dan mengganggu.'],
//
//            // Laporan untuk Meja (fasilitas_id: 1)
//            ['user_id' => 4, 'fasilitas_id' => 1, 'pelaporan_kode' => 'PLP-0002', 'pelaporan_deskripsi' => 'Meja pecah, tidak bisa digunakan.'],
//
//            // Laporan untuk Proyektor (fasilitas_id: 2)
//            ['user_id' => 5, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0003', 'pelaporan_deskripsi' => 'Proyektor tidak bisa digunakan, gambar buram.'],
//            ['user_id' => 6, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0009', 'pelaporan_deskripsi' => 'Kabel proyektor putus.'],
//
//            // Laporan untuk Kursi (fasilitas_id: 3)
//            ['user_id' => 6, 'fasilitas_id' => 3, 'pelaporan_kode' => 'PLP-0004', 'pelaporan_deskripsi' => 'Kaki kursi patah.'],
//
//            // Laporan untuk Papan Tulis (fasilitas_id: 4)
//            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0005', 'pelaporan_deskripsi' => 'Papan tulis sulit dihapus, spidol membekas.'],
//            ['user_id' => 5, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0006', 'pelaporan_deskripsi' => 'Papan tulis retak di bagian tengah.'],
//            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0010', 'pelaporan_deskripsi' => 'Permukaan papan tulis menggelembung.'],
//            ['user_id' => 6, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0011', 'pelaporan_deskripsi' => 'Penghapus papan tulis hilang.'],
//        ];
//
//        foreach ($data as $row) {
//            $randomDate = fake()->dateTimeBetween('-6 months', 'now');
//
//            DB::table('m_pelaporan')->insert([
//                ...$row,
//                'pelaporan_gambar' => $randomImages(),
//                'created_at' => $randomDate,
//                'updated_at' => $randomDate,
//            ]);
//        }
//    }
//}

/*
 * StatusPelaporanSeeder.php
 */

//namespace Database\Seeders;
//use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//use Carbon\Carbon;
//
//class StatusPelaporanSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     */
//    public function run(): void
//    {
//        // Hapus data lama agar tidak duplikat saat seeding ulang
//        DB::table('t_status_pelaporan')->delete();
//
//        // 1. Ambil semua laporan yang sudah ada di database
//        $laporan = DB::table('m_pelaporan')->get(['pelaporan_id', 'created_at']);
//
//        foreach ($laporan as $lp) {
//            $statusesToInsert = [];
//
//            // Jadikan waktu pembuatan laporan sebagai Carbon instance
//            $reportCreationTime = Carbon::parse($lp->created_at);
//
//            // 2. Status pertama untuk setiap laporan PASTI 'Menunggu'
//            $statusMenunggu = [
//                'pelaporan_id' => $lp->pelaporan_id,
//                'status_pelaporan' => 'Menunggu',
//                'created_at' => $reportCreationTime,
//                'updated_at' => $reportCreationTime,
//            ];
//            $statusesToInsert[] = $statusMenunggu;
//
//            // Waktu terakhir sebuah status dibuat, dimulai dari waktu laporan dibuat
//            $lastStatusTime = $reportCreationTime;
//
//            // 3. Buat skenario status yang bervariasi secara acak
//            $scenario = rand(1, 4);
//
//            // Skenario 1 & 2: Laporan selesai diproses (paling umum)
//            if ($scenario <= 2) {
//                // Tambah status 'Diproses'
//                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 24))->addMinutes(rand(0, 59));
//                $statusesToInsert[] = [
//                    'pelaporan_id' => $lp->pelaporan_id,
//                    'status_pelaporan' => 'Diproses',
//                    'created_at' => $diprosesTime,
//                    'updated_at' => $diprosesTime,
//                ];
//                $lastStatusTime = $diprosesTime;
//
//                // Tambah status 'Selesai' (Gunakan 'Selesai' agar konsisten dengan query chart)
//                $selesaiTime = $lastStatusTime->copy()->addHours(rand(2, 48))->addMinutes(rand(0, 59));
//                $statusesToInsert[] = [
//                    'pelaporan_id' => $lp->pelaporan_id,
//                    'status_pelaporan' => 'Selesai',
//                    'created_at' => $selesaiTime,
//                    'updated_at' => $selesaiTime,
//                ];
//            } // Skenario 3: Laporan sedang diproses
//            elseif ($scenario === 3) {
//                // Tambah status 'Diproses'
//                $diprosesTime = $lastStatusTime->copy()->addHours(rand(1, 12))->addMinutes(rand(0, 59));
//                $statusesToInsert[] = [
//                    'pelaporan_id' => $lp->pelaporan_id,
//                    'status_pelaporan' => 'Diproses',
//                    'created_at' => $diprosesTime,
//                    'updated_at' => $diprosesTime,
//                ];
//            }
//            // Skenario 4: Laporan masih baru (hanya status 'Menunggu')
//            // Tidak perlu melakukan apa-apa, karena status 'Menunggu' sudah ditambahkan di awal.
//
//            // Masukkan semua status untuk laporan ini ke database
//            DB::table('t_status_pelaporan')->insert($statusesToInsert);
//        }
//    }
//}

/*
 * AltSkorSeeder.php
 */

//namespace Database\Seeders;
//
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
//use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//
//class AltSkorSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     */
//    public function run(): void
//    {
//        // 1. Kosongkan tabel skor terlebih dahulu
//        DB::table('m_skor_alt')->delete();
//
//        // 2. Subquery untuk menemukan status terakhir dari setiap pelaporan
//        $latestStatusTimestamps = DB::table('t_status_pelaporan')
//            ->select('pelaporan_id', DB::raw('MAX(created_at) as last_created_at'))
//            ->groupBy('pelaporan_id');
//
//        // 3. Query utama untuk mengambil semua laporan beserta status terakhirnya
//        $laporanWithStatus = DB::table('m_pelaporan as p')
//            ->join('t_status_pelaporan as sp', 'p.pelaporan_id', '=', 'sp.pelaporan_id')
//            ->joinSub($latestStatusTimestamps, 'latest_status', function ($join) {
//                $join->on('sp.pelaporan_id', '=', 'latest_status.pelaporan_id')
//                    ->on('sp.created_at', '=', 'latest_status.last_created_at');
//            })
//            ->select('p.pelaporan_id', 'sp.status_pelaporan as final_status')
//            ->get();
//
//        $skorToInsert = [];
//        $kriteriaIds = [1, 2, 3, 4]; // C1, C2, C3, C4
//
//        // 4. Lakukan loop pada setiap laporan yang ada
//        foreach ($laporanWithStatus as $laporan) {
//            foreach ($kriteriaIds as $kriteriaId) {
//
//                // Aturan: Jika status 'Menunggu', lewati C1 (id:1) dan C4 (id:4)
//                if ($laporan->final_status === 'Menunggu' && in_array($kriteriaId, [1, 4])) {
//                    continue; // Ini memastikan hanya C2 dan C3 yang dibuat untuk status 'Menunggu'
//                }
//
//                $nilai_skor = 0;
//                // 5. Buat nilai skor acak
//                switch ($kriteriaId) {
//                    case 1:
//                        $nilai_skor = rand(1, 3);
//                        break;
//                    case 2:
//                        $nilai_skor = rand(1, 3);
//                        break;
//                    case 3:
//                        $nilai_skor = rand(1, 3);
//                        break;
//                    case 4:
//                        $nilai_skor = rand(10, 500) * 1000;
//                        break;
//                }
//
//                $skorToInsert[] = [
//                    'skor_alt_kode' => $laporan->pelaporan_id . '-C' . $kriteriaId,
//                    'pelaporan_id' => $laporan->pelaporan_id,
//                    'kriteria_id' => $kriteriaId,
//                    'nilai_skor' => $nilai_skor,
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ];
//            }
//        }
//
//        // 6. Masukkan semua data skor ke database
//        if (!empty($skorToInsert)) {
//            DB::table('m_skor_alt')->insert($skorToInsert);
//        }
//    }
//}
