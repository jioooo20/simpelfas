<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PelaporanModel;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        $laporanKerusakan = PelaporanModel::with([
            'user',
            'fasilitas',
            'statusPelaporan' => function ($q) {
                $q->orderBy('status_pelaporan_id', 'asc');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('pages.admin.laporan-kerusakan.index', compact('laporanKerusakan'));
    }

    public function show($id)
    {
        $laporan = PelaporanModel::with([
            'user',
            'fasilitas',
            'statusPelaporan' => function ($q) {
                $q->orderByDesc('status_pelaporan_id');
            }
        ])->findOrFail($id);

        return view('pages.admin.laporan-kerusakan.show', compact('laporan'));
    }
}