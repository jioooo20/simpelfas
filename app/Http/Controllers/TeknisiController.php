<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function perbaikan()
    {
        return view('pages.teknisi.perbaikan.index');
    }

    public function perbaikanShow()
    {
        return view('pages.teknisi.perbaikan.detail');
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
