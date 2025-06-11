<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerbaikanModel;
use App\Models\StatusPerbaikanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Ditambahkan
use Carbon\Carbon;

class StatusPerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StatusPerbaikanModel::truncate();

        // =================================================================
        // LOGIKA PENGAMBILAN GAMBAR DARI REFERENSI ANDA
        // =================================================================
        $dummySourcePath = public_path('storage/dummy');
        $imageFiles = [];
        if (File::exists($dummySourcePath)) {
            $imageFiles = collect(File::files($dummySourcePath))
                ->map(fn($file) => 'storage/dummy/' . $file->getFilename()) // Mengubah path agar bisa diakses dari web
                ->toArray();
        }

        // Fungsi bantuan untuk mengambil gambar secara acak (1-3 gambar)
        // dan mengembalikannya sebagai string JSON.
        $getRandomImagesJson = function() use ($imageFiles) {
            // Jika tidak ada file gambar, kembalikan null
            if (empty($imageFiles)) {
                return null;
            }

            // Tentukan jumlah gambar yang akan diambil, maksimal 3 atau sebanyak file yang ada
            $maxImages = min(3, count($imageFiles));
            $count = rand(1, $maxImages);

            // Ambil elemen acak dari array file gambar
            $randomImagePaths = fake()->randomElements($imageFiles, $count);

            // Encode menjadi JSON
            return json_encode($randomImagePaths, JSON_UNESCAPED_SLASHES);
        };
        // =================================================================
        // AKHIR LOGIKA PENGAMBILAN GAMBAR
        // =================================================================


        $semuaPerbaikan = PerbaikanModel::all();
        if ($semuaPerbaikan->isEmpty()) {
            $this->command->info('Tidak ada data Perbaikan. Jalankan PerbaikanSeeder terlebih dahulu.');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        foreach ($semuaPerbaikan as $perbaikan) {
            $tanggalMulai = Carbon::parse($perbaikan->created_at);

            // Status 'Menunggu' tidak perlu gambar
            StatusPerbaikanModel::create([
                'perbaikan_id' => $perbaikan->perbaikan_id,
                'perbaikan_gambar' => null,
                'perbaikan_status' => 'Menunggu',
                'created_at' => $tanggalMulai,
                'updated_at' => $tanggalMulai,
            ]);

            // Secara acak, beberapa perbaikan akan lanjut ke status 'Diproses'
            if (rand(1, 10) <= 8) {
                $tanggalProses = $tanggalMulai->addDays(rand(1, 2));
                StatusPerbaikanModel::create([
                    'perbaikan_id' => $perbaikan->perbaikan_id,
                    'perbaikan_gambar' => $getRandomImagesJson(), // Diubah: Ambil gambar acak
                    'perbaikan_status' => 'Diproses',
                    'created_at' => $tanggalProses,
                    'updated_at' => $tanggalProses,
                ]);

                // Dari yang 'Diproses', beberapa akan lanjut ke 'Selesai'
                if (rand(1, 10) <= 6) {
                    $tanggalSelesai = $tanggalProses->addDays(rand(1, 5));
                    StatusPerbaikanModel::create([
                        'perbaikan_id' => $perbaikan->perbaikan_id,
                        'perbaikan_gambar' => $getRandomImagesJson(), // Diubah: Ambil gambar acak
                        'perbaikan_status' => 'Selesai',
                        'created_at' => $tanggalSelesai,
                        'updated_at' => $tanggalSelesai,
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Seeder StatusPerbaikanModel berhasil dijalankan dengan gambar acak!');
    }
}
