<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            BarangSeeder::class,
            KriteriaSeeder::class,
            FasilitasSeederClean::class, //ini fasilitas default semua, komen kalo mau seed fasilitas rusak
            // FasilitasSeeder::class, // ini fasilitas rusak, unkomen kalo pelaporan, status, alt skor diunkomen
            // PelaporanSeeder::class,
            // StatusPelaporanSeeder::class,
            // AltSkorSeeder::class,
            // TempSeeder::class,
            // PerbaikanSeeder::class,
            //PerbaikanPetugasSeeder::class,
            // StatusPerbaikanSeeder::class,
            // FeedbackSeeder::class,
        ]);
    }
}
