<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function perbaikan()
    {
        // $perbaikan = Perbaikan::where('status', '!=', 'selesai')->get();
        // return view('pages.teknisi.perbaikan.index', compact('perbaikan'));
        return view('pages.teknisi.perbaikan.index');
    }

    public function show()
    {
        // $perbaikan = Perbaikan::findOrFail($id);
        // return view('pages.teknisi.perbaikan.detail', compact('perbaikan'));
        return view('pages.teknisi.perbaikan.detail');
    }

    public function update(Request $request, $id)
    {
        // $perbaikan = Perbaikan::findOrFail($id);
        // $perbaikan->update($request->all());
        // return redirect()->route('teknisi')->with('success', 'Perbaikan updated successfully');
        return redirect()->route('teknisi')->with('success', 'Perbaikan updated successfully');
    }

    public function riwayat()
    {
        // $riwayat = Perbaikan::where('status', 'selesai')->get();
        // return view('pages.teknisi.riwayat.index', compact('riwayat'));
        return view('pages.teknisi.riwayat-perbaikan.index');
    }

    public function riwayatShow()
    {
        // $riwayat = Perbaikan::findOrFail($id);
        // return view('pages.teknisi.riwayat.detail', compact('riwayat'));
        return view('pages.teknisi.riwayat-perbaikan.detail');
    }
}
