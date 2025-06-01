<?php

namespace App\Repositories;

use App\Models\FasilitasModel;

class FasilitasRepository
{
    public function getLokasiOptions(): \Illuminate\Support\Collection
    {
        return FasilitasModel::with(['ruang.lantai.gedung', 'barang'])->get()->map(function ($item) {
            $label = $item->ruang->lantai->gedung->gedung_nama . ' - ' .
                $item->ruang->lantai->lantai_nama . ' - ' .
                $item->ruang->ruang_nama . ' - ' .
                $item->barang->barang_nama . ' - ' .
                $item->barang->barang_kode;

            $search = strtolower(
                str_replace(['-', '  '], [' ', ' '],
                    preg_replace('/[^a-zA-Z0-9 ]/', '', $label)
                )
            );

            return [
                'id'    => $item->fasilitas_id,
                'label' => $label,
                'search' => $search,
            ];
        });
    }
}
