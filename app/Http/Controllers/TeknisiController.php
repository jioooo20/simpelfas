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

    public function riwayatShow()
    {
        return view('pages.teknisi.riwayat-perbaikan.detail');
    }
}
