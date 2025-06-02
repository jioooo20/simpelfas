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
        return view('pages.sarpra.analisis-laporan.statistik-fasilitas.index');
    }

    public function frekuensiPerbaikan() {
        return view('pages.sarpra.analisis-laporan.frekuensi-perbaikan.index');
    }

    public function kepuasanPengguna() {
        return view('pages.sarpra.analisis-laporan.kepuasan-pengguna.index');
    }

    public function perencanaanPemeliharaan() {
        return view('pages.sarpra.analisis-laporan.perencanaan-pemeliharaan.index');
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
