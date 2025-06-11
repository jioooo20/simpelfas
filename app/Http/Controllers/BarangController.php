<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $data = BarangModel::paginate(10);
        return view('pages.admin.barang.index', compact('data'));
    }

    public function show(Request $request, $id)
    {
        $barang = BarangModel::findOrFail($id);
        return response()->json($barang); // Untuk kebutuhan detail via AJAX/modal
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_kode' => 'required|unique:m_barang,barang_kode|max:20',
            'barang_nama' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);
        $barang = BarangModel::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $barang]);
        }
        return redirect()->route('admin.barang')->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $barang = BarangModel::findOrFail($id);
        $validated = $request->validate([
            'barang_kode' => 'required|max:20|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);
        $barang->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $barang]);
        }
        return redirect()->route('admin.barang')->with('success', 'Data barang berhasil diupdate.');
    }

    public function destroy(Request $request, $id)
    {
        $barang = BarangModel::findOrFail($id);
        $barang->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.barang')->with('success', 'Data barang berhasil dihapus.');
    }

    // Optional: disable create/edit view jika tidak diperlukan
    public function create() { abort(404); }
    public function edit($id) { abort(404); }
}