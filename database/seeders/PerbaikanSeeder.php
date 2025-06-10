<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerbaikanModel;
use App\Models\PelaporanModel; // Kita butuh model ini untuk membaca data laporan
use Illuminate\Support\Facades\DB;

class PerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PerbaikanModel::truncate();

        // Ambil data laporan yang sudah ada, yang akan kita buatkan data perbaikannya
        // Kita ambil semua data laporan agar bisa menggunakan kode laporannya
        $laporanUntukDiperbaiki = PelaporanModel::all();

        // Cek jika tidak ada data laporan sama sekali
        if ($laporanUntukDiperbaiki->isEmpty()) {
            $this->command->info('Tidak ada data di tabel pelaporan. Jalankan PelaporanSeeder terlebih dahulu.');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        $perbaikanData = [];
        $counter = 1;

        // Kita akan buat data perbaikan untuk setiap laporan yang ada
        foreach ($laporanUntukDiperbaiki as $pelaporan) {
            $perbaikanData[] = [
                'pelaporan_id'      => $pelaporan->pelaporan_id,
                'perbaikan_kode'    => 'PRB-' . now()->format('Ymd') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                'perbaikan_deskripsi' => 'Tugas perbaikan dibuat berdasarkan laporan ' . $pelaporan->pelaporan_kode . '. Menunggu penugasan teknisi.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            $counter++;
        }

        // Masukkan semua data perbaikan ke dalam database
        PerbaikanModel::insert($perbaikanData);

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Seeder PerbaikanModel berhasil dijalankan!');
    }
}
