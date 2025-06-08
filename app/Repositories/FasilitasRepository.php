<?php

namespace App\Repositories;

use App\Models\FasilitasModel;
use Illuminate\Support\Collection;


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
}
