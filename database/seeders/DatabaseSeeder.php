<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            BarangSeeder::class,
            FasilitasSeeder::class,
            KriteriaSeeder::class,
            PelaporanSeeder::class,
            StatusPelaporanSeeder::class,
            AltSkorSeeder::class,
            // TempSeeder::class,
            // PerbaikanSeeder::class,
            //PerbaikanPetugasSeeder::class,
            // StatusPerbaikanSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}
