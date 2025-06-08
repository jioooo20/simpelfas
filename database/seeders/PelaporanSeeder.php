<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PelaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        $data = [
            // Laporan untuk AC 1PK (fasilitas_id: 5)
            ['user_id' => 6, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0001', 'pelaporan_deskripsi' => 'AC tidak berfungsi dengan baik, suhu tidak turun.'],
            ['user_id' => 4, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0007', 'pelaporan_deskripsi' => 'AC kembali mati total, tidak ada respon dari remote.'],
            ['user_id' => 5, 'fasilitas_id' => 5, 'pelaporan_kode' => 'PLP-0008', 'pelaporan_deskripsi' => 'Suara AC sangat berisik dan mengganggu.'],

            // Laporan untuk Meja (fasilitas_id: 1)
            ['user_id' => 4, 'fasilitas_id' => 1, 'pelaporan_kode' => 'PLP-0002', 'pelaporan_deskripsi' => 'Meja pecah, tidak bisa digunakan.'],

            // Laporan untuk Proyektor (fasilitas_id: 2)
            ['user_id' => 5, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0003', 'pelaporan_deskripsi' => 'Proyektor tidak bisa digunakan, gambar buram.'],
            ['user_id' => 6, 'fasilitas_id' => 2, 'pelaporan_kode' => 'PLP-0009', 'pelaporan_deskripsi' => 'Kabel proyektor putus.'],

            // Laporan untuk Kursi (fasilitas_id: 3)
            ['user_id' => 6, 'fasilitas_id' => 3, 'pelaporan_kode' => 'PLP-0004', 'pelaporan_deskripsi' => 'Kaki kursi patah.'],

            // Laporan untuk Papan Tulis (fasilitas_id: 4)
            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0005', 'pelaporan_deskripsi' => 'Papan tulis sulit dihapus, spidol membekas.'],
            ['user_id' => 5, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0006', 'pelaporan_deskripsi' => 'Papan tulis retak di bagian tengah.'],
            ['user_id' => 4, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0010', 'pelaporan_deskripsi' => 'Permukaan papan tulis menggelembung.'],
            ['user_id' => 6, 'fasilitas_id' => 4, 'pelaporan_kode' => 'PLP-0011', 'pelaporan_deskripsi' => 'Penghapus papan tulis hilang.'],
        ];

        foreach ($data as $row) {
            $randomDate = fake()->dateTimeBetween('-6 months', 'now');

            DB::table('m_pelaporan')->insert([
                ...$row,
                'pelaporan_gambar' => $randomImages(),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
    }
}
