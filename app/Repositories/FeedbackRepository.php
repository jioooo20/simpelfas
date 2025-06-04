<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class FeedbackRepository
{
    public function getAverageRating(): float
    {
        $avg = DB::table('m_feedback')->avg('rating') ?? 0;
        return round($avg, 2);
    }

    public function getAverageRatingByMonth(): Collection
    {
        return DB::table('m_feedback')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS bulan, ROUND(AVG(rating), 2) AS rata_rata_rating")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();
    }

    public function getFacilityRatings(): Collection
    {
        return DB::table('t_fasilitas as f')
            ->join('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->join('m_ruang as r',   'f.ruang_id',  '=', 'r.ruang_id')
            ->join('m_lantai as l',  'r.lantai_id', '=', 'l.lantai_id')
            ->join('m_gedung as g',  'l.gedung_id', '=', 'g.gedung_id')
            ->leftJoin('m_pelaporan as p', 'f.fasilitas_id', '=', 'p.fasilitas_id')
            ->leftJoin('m_feedback as fb',   'p.pelaporan_id',  '=', 'fb.pelaporan_id')
            ->selectRaw("
                b.barang_nama    AS item_name,
                f.fasilitas_kode AS original_fasilitas_kode,
                r.ruang_nama     AS room,
                l.lantai_nama    AS floor,
                g.gedung_nama    AS building,
                ROUND(AVG(fb.rating), 2) AS rating,
                COUNT(fb.rating)       AS total_ratings
            ")
            ->groupBy(
                'f.fasilitas_id',
                'b.barang_nama',
                'f.fasilitas_kode',
                'r.ruang_nama',
                'l.lantai_nama',
                'g.gedung_nama'
            )
            ->havingRaw('COUNT(fb.rating) > 0')
            ->get();
    }
}
