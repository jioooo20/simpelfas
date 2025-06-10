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
        DB::table('m_feedback')->delete();

        $laporanSelesai = DB::table('t_status_pelaporan')
            ->where('status_pelaporan', 'Selesai')
            ->get(['pelaporan_id', 'created_at as waktu_selesai']);

        if ($laporanSelesai->isEmpty()) {
            $this->command->info('Tidak ada laporan yang berstatus "Selesai", seeder feedback dilewati.');
            return;
        }

        $feedbackToInsert = [];

        foreach ($laporanSelesai as $l) {
            if (!fake()->boolean(80)) {
                continue;
            }

            $rating = fake()->randomElement([2, 3, 3, 4, 4, 4, 5, 5, 5, 5]);
            $feedbackText = null;

            if ($rating >= 4) {
                $feedbackText = fake()->randomElement([
                    'Perbaikan sangat cepat dan hasilnya memuaskan. Terima kasih banyak!',
                    'Sudah berfungsi normal kembali seperti semula. Mantap!',
                    'Respon cepat dan pengerjaan rapi.',
                    'Terima kasih, masalah sudah teratasi dengan baik.',
                ]);
            } elseif ($rating === 3) {
                $feedbackText = fake()->randomElement([
                    'Sudah oke, tapi prosesnya agak lama.',
                    'Berfungsi, tapi sepertinya masih ada sedikit masalah.',
                    'Cukup baik.',
                ]);
            } else { // Rating 1 atau 2
                $feedbackText = fake()->randomElement([
                    'Perbaikannya tidak tuntas, masalah muncul lagi.',
                    'Masih rusak, hanya sementara beres.',
                    'Sangat tidak memuaskan, butuh waktu sangat lama.',
                ]);
            }

            $waktuSelesai = Carbon::parse($l->waktu_selesai);
            $waktuFeedback = $waktuSelesai->copy()->addHours(rand(1, 48))->addMinutes(rand(0, 59));

            $feedbackToInsert[] = [
                'pelaporan_id' => $l->pelaporan_id,
                'feedback_text' => fake()->boolean(90) ? $feedbackText : null, // 10% rating tanpa teks
                'rating' => $rating,
                'created_at' => $waktuFeedback,
                'updated_at' => $waktuFeedback,
            ];
        }

        DB::table('m_feedback')->insert($feedbackToInsert);
    }
}
