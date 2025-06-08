<?php

namespace App\Repositories;

use App\Models\FasilitasModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class FasilitasRepository
{
    public function getLokasiOptions(): Collection
    {
        return FasilitasModel::with(['ruang.lantai.gedung', 'barang'])->get()->map(function ($item) {
            $label = $item->ruang->lantai->gedung->gedung_nama . ' - ' .
                $item->ruang->lantai->lantai_nama . ' - ' .
                $item->ruang->ruang_nama . ' - ' .
                $item->barang->barang_nama . ' - ' .
                substr($item->fasilitas_kode, -2);

            $search = strtolower(
                str_replace(['-', '  '], [' ', ' '],
                    preg_replace('/[^a-zA-Z0-9 ]/', '', $label)
                )
            );

            $rawStatus = $item->fasilitas_status;
            $statusCode = '';
            $statusText = '';

            if (!empty($rawStatus) && is_string($rawStatus)) {
                $statusCode = strtoupper($rawStatus);

                $statusMap = [
                    'BAIK' => 'Baik',
                    'RUSAK' => 'Rusak',
                    'DALAM PERBAIKAN' => 'Dalam Perbaikan',
                ];

                if (isset($statusMap[$statusCode])) {
                    $statusText = $statusMap[$statusCode];
                } else {
                    $statusText = ucfirst(strtolower(str_replace('_', ' ', $rawStatus)));
                }
            }

            return [
                'id' => $item->fasilitas_id,
                'label' => $label,
                'search' => $search,
                'statusText' => $statusText,
                'statusCode' => $statusCode,
            ];
        });
    }

    public function getFasilitasBerisikoTinggi(): Collection
    {
        // Menyimpan perhitungan RAW SQL agar lebih rapi
        $intervalCalculation = 'ROUND(
            CASE
                WHEN COUNT(p.pelaporan_id) > 1
                THEN DATEDIFF(MAX(p.created_at), MIN(p.created_at)) / (COUNT(p.pelaporan_id) - 1)
                ELSE 0
            END
        )';

        return DB::table('m_pelaporan as p')
            ->join('t_fasilitas as f', 'p.fasilitas_id', '=', 'f.fasilitas_id')
            // [FIX] Tambahkan join ke tabel m_barang untuk mendapatkan nama
            ->join('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->join('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
            ->join('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
            ->join('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
            ->select(
                'b.barang_nama as item_name',
                // Sediakan kode asli untuk helper dan kode yang akan ditimpa
                'f.fasilitas_kode as original_fasilitas_kode',
                'f.fasilitas_kode as item_code',
                'r.ruang_nama as room',
                'l.lantai_nama as floor',
                'g.gedung_nama as building',
                DB::raw('COUNT(p.pelaporan_id) as jumlah_laporan'),
                DB::raw($intervalCalculation . ' as interval_rata_rata_hari')
            )
            ->groupBy(
                'f.fasilitas_id',
                // [FIX] Tambahkan nama barang ke group by
                'b.barang_nama',
                'f.fasilitas_kode',
                'r.ruang_nama',
                'l.lantai_nama',
                'g.gedung_nama'
            )
            // Menerapkan filter yang sama dengan klausa HAVING di SQL
            ->having('jumlah_laporan', '>', 1)
            ->having('interval_rata_rata_hari', '<', 30)
            // Mengurutkan hasilnya
            ->orderBy('interval_rata_rata_hari', 'asc')
            ->orderBy('jumlah_laporan', 'desc')
            ->get();
    }
}
