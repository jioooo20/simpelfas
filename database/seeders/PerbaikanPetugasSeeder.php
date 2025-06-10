<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerbaikanPetugasModel;
use App\Models\PerbaikanModel;
use App\Models\UserModel;

// Pastikan model ini mengarah ke tabel 'm_user'
use Illuminate\Support\Facades\DB;

class PerbaikanPetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PerbaikanPetugasModel::truncate();

        $perbaikanIds = PerbaikanModel::pluck('perbaikan_id')->toArray();

        // --- PERUBAHAN DI SINI ---
        // Kode Lama: $userIds = UserModel::pluck('user_id')->toArray();
        // Ambil HANYA user yang memiliki role_id 3 (Teknisi)
        $userIds = UserModel::where('role_id', 3)->pluck('user_id')->toArray();
        // --- AKHIR PERUBAHAN ---

        if (empty($perbaikanIds) || empty($userIds)) {
            // Pesan error diubah menjadi lebih spesifik
            $this->command->info('Tidak ada data Perbaikan atau User Teknisi (role_id=3). Seeder tidak dijalankan.');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        $assignments = [];
        foreach ($perbaikanIds as $perbaikanId) {
            $assignedUserCount = rand(1, min(count($userIds), 2)); // Pastikan tidak mengambil lebih dari jumlah teknisi yang ada
            $randomUserIndexes = (array)array_rand($userIds, $assignedUserCount);

            foreach ($randomUserIndexes as $index) {
                $userId = $userIds[$index];

                $pair = $perbaikanId . '-' . $userId;
                if (!isset($assignments[$pair])) {
                    PerbaikanPetugasModel::create([
                        'perbaikan_id' => $perbaikanId,
                        'user_id' => $userId,
                    ]);
                    $assignments[$pair] = true;
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Seeder PerbaikanPetugasModel berhasil dijalankan dengan menugaskan teknisi!');
    }
}
