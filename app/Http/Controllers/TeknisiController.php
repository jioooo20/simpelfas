<?php

namespace App\Http\Controllers;

use App\Models\PerbaikanModel;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function perbaikan()
    {
        return view('pages.teknisi.perbaikan.index');
    }

    public function perbaikanShow($id)
    {
        $perbaikan = PerbaikanModel::findOrFail($id);
        return view('pages.teknisi.perbaikan.detail', compact('perbaikan'));
    }

    public function riwayat()
    {
        return view('pages.teknisi.riwayat-perbaikan.index');
    }

    public function riwayatShow(Request $request)
    {
        $perbaikanId = $request->query('id');
        
        // Jika ID tidak diberikan, gunakan dummy data
        if (!$perbaikanId) {
            return view('pages.teknisi.riwayat-perbaikan.detail', ['perbaikan' => null]);
        }
        
        // Temukan perbaikan atau kembalikan halaman dengan data kosong jika tidak ditemukan
        $perbaikan = PerbaikanModel::find($perbaikanId);
        return view('pages.teknisi.riwayat-perbaikan.detail', ['perbaikan' => $perbaikan]);
    }
}
