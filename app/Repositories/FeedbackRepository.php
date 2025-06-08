<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class FeedbackRepository
{
    public function getAverageRating(): float
    {
        $avg = DB::table('m_feedback')->avg('rating') ?? 0;
        return round($avg, 2);
    }

    public function getYearlyAverageRatings(): array
    {
        $allMonthlyRatings = DB::table('m_feedback')
            ->selectRaw("
                YEAR(created_at) as year,
                LPAD(MONTH(created_at), 2, '0') as month,
                ROUND(AVG(rating), 2) as average_rating
            ")
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $yearlyData = [];
        foreach ($allMonthlyRatings as $rating) {
            if (!isset($yearlyData[$rating->year])) {
                $yearlyData[$rating->year] = [];
            }
            $yearlyData[$rating->year][] = [
                'month' => $rating->month,
                'rating' => $rating->average_rating,
            ];
        }

        return $yearlyData;
    }

    public function getFacilityRatings(): Collection
    {
        return $this->buildFacilityRatingsQuery()
            ->havingRaw('COUNT(fb.rating) > 0')
            ->get();
    }

    private function buildFacilityRatingsQuery(): Builder
    {
        return DB::table('t_fasilitas as f')
            ->join('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->join('m_ruang as r',   'f.ruang_id',  '=', 'r.ruang_id')
            ->join('m_lantai as l',  'r.lantai_id', '=', 'l.lantai_id')
            ->join('m_gedung as g',  'l.gedung_id', '=', 'g.gedung_id')
            ->leftJoin('m_pelaporan as p', 'f.fasilitas_id', '=', 'p.fasilitas_id')
            ->leftJoin('m_feedback as fb',   'p.pelaporan_id',  '=', 'fb.pelaporan_id')
            ->selectRaw("
                f.fasilitas_id,
                b.barang_nama    AS item_name,
                f.fasilitas_kode AS original_fasilitas_kode,
                r.ruang_nama     AS room,
                l.lantai_nama    AS floor,
                g.gedung_nama    AS building,
                AVG(fb.rating)   AS rata_rata_rating,
                COUNT(fb.rating) AS total_ratings
            ")
            ->groupBy(
                'f.fasilitas_id', 'b.barang_nama', 'f.fasilitas_kode',
                'r.ruang_nama', 'l.lantai_nama', 'g.gedung_nama'
            );
    }
}
