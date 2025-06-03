<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SarpraController extends Controller
{
    public function dasbor()
    {
        return view('pages.sarpra.dasbor.index');
    }

    public function statistikFasilitas() {
        return view('pages.sarpra.analisis-laporan.index');
    }
    public function laporan_kerusakan_fasilitas()
    {
        return view('pages.sarpra.laporan-kerusakan-fasilitas.index');
    }
    public function rekomendasi_prioritas_perbaikan()
    {
        return view('pages.sarpra.rekomendasi-prioritas-perbaikan.index');
    }

}
