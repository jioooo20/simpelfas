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
            [
                'user_id' => 6,
                'fasilitas_id' => 5,
                'pelaporan_kode' => 'PLP-0001',
                'pelaporan_deskripsi' => 'AC tidak berfungsi dengan baik, suhu tidak turun meskipun sudah diatur.',
            ],
            [
                'user_id' => 4,
                'fasilitas_id' => 1,
                'pelaporan_kode' => 'PLP-0002',
                'pelaporan_deskripsi' => 'Meja pecah, tidak bisa digunakan.',
            ],
            [
                'user_id' => 5,
                'fasilitas_id' => 2,
                'pelaporan_kode' => 'PLP-0003',
                'pelaporan_deskripsi' => 'Proyektor tidak bisa digunakan.',
            ],
            [
                'user_id' => 6,
                'fasilitas_id' => 3,
                'pelaporan_kode' => 'PLP-0004',
                'pelaporan_deskripsi' => 'Kursi tidak bisa digunakan.',
            ],
            [
                'user_id' => 4,
                'fasilitas_id' => 4,
                'pelaporan_kode' => 'PLP-0005',
                'pelaporan_deskripsi' => 'Papan tulis tidak bisa digunakan.',
            ],
            [
                'user_id' => 5,
                'fasilitas_id' => 4,
                'pelaporan_kode' => 'PLP-0006',
                'pelaporan_deskripsi' => 'Papan tulis rusak total.',
            ],
        ];

        foreach ($data as $row) {
            DB::table('m_pelaporan')->insert([
                ...$row,
                'pelaporan_gambar' => $randomImages(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
