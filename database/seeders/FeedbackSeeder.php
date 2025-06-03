<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('m_feedback')->insert([
            [
                'pelaporan_id' => 1, // Merujuk pada pelaporan AC (PLR0001)
                'feedback_text' => 'AC sudah kembali dingin, terima kasih atas respon cepatnya!',
                'rating' => 5, // Rating dari 1-5
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelaporan_id' => 2, // Merujuk pada pelaporan meja pecah (PLR0002)
                'feedback_text' => 'Meja sudah diganti, namun prosesnya memakan waktu cukup lama.',
                'rating' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelaporan_id' => 3, // Merujuk pada pelaporan proyektor (PLR0003)
                'feedback_text' => 'Proyektor berfungsi kembali setelah diperbaiki. Mantap!',
                'rating' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelaporan_id' => 4, // Merujuk pada pelaporan kursi (PLR0004)
                'feedback_text' => null, // Feedback bisa saja tidak ada teksnya
                'rating' => 2, // Mungkin perbaikan kurang memuaskan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelaporan_id' => 5, // Merujuk pada pelaporan papan tulis (PLR0005)
                'feedback_text' => 'Papan tulis sudah bersih dan spidol sudah tersedia. Terima kasih.',
                'rating' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
