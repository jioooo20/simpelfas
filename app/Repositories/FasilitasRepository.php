<?php

namespace App\Repositories;

use App\Models\FasilitasModel;
use App\Models\PelaporanModel;
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
        $laporanSelesai = PelaporanModel::query()
            ->join('t_status_pelaporan as s', 'm_pelaporan.pelaporan_id', '=', 's.pelaporan_id')
            ->where('s.status_pelaporan', 'Selesai')
            ->select(
                'm_pelaporan.fasilitas_id',
                'm_pelaporan.created_at as waktu_dibuat',
                's.created_at as waktu_selesai'
            )
            ->orderBy('fasilitas_id')
            ->orderBy('waktu_dibuat');

        $intervalPerLaporan = DB::query()
            ->fromSub($laporanSelesai, 'lapsel')
            ->select(
                'fasilitas_id',
                'waktu_dibuat',
                DB::raw('LAG(waktu_selesai, 1) OVER (PARTITION BY fasilitas_id ORDER BY waktu_dibuat) as waktu_selesai_sebelumnya')
            );

        $intervalSubquery = DB::query()
            ->fromSub($intervalPerLaporan, 'interval_data')
            ->select(
                'fasilitas_id',
                DB::raw('ROUND(AVG(DATEDIFF(waktu_dibuat, waktu_selesai_sebelumnya))) as average_interval_days')
            )
            ->whereNotNull('waktu_selesai_sebelumnya')
            ->whereColumn('waktu_dibuat', '>', 'waktu_selesai_sebelumnya')
            ->groupBy('fasilitas_id');


        return DB::table('m_pelaporan as p')
            ->join('t_fasilitas as f', 'p.fasilitas_id', '=', 'f.fasilitas_id')
            ->join('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->join('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
            ->join('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
            ->join('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
            ->joinSub($intervalSubquery, 'intervals', function ($join) {
                $join->on('f.fasilitas_id', '=', 'intervals.fasilitas_id');
            })
            ->select(
                'b.barang_nama as item_name',
                'f.fasilitas_kode as original_fasilitas_kode',
                'f.fasilitas_kode as item_code',
                'r.ruang_nama as room',
                'l.lantai_nama as floor',
                'g.gedung_nama as building',
                DB::raw('COUNT(p.pelaporan_id) as jumlah_laporan'),
                'intervals.average_interval_days as interval_rata_rata_hari'
            )
            ->groupBy(
                'f.fasilitas_id', 'b.barang_nama', 'f.fasilitas_kode',
                'r.ruang_nama', 'l.lantai_nama', 'g.gedung_nama',
                'intervals.average_interval_days'
            )
            ->having('jumlah_laporan', '>', 1)
            ->having('interval_rata_rata_hari', '<', 30)
            ->orderBy('interval_rata_rata_hari', 'asc')
            ->orderBy('jumlah_laporan', 'desc')
            ->get()
            ->map(fn($item) => (array)$item);
    }
}
