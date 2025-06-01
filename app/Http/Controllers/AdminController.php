<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PelaporanModel;
use App\Models\StatusPelaporanModel;
use App\Models\FasilitasModel;

class AdminController extends Controller
{
    public function dasbor()
    {
        return view('pages.admin.dasbor.index');
    }


    public function user()
    {
        $table = UserModel::with('role');
        $roles = RoleModel::all();
        return view('pages.admin.manage-user.index', compact('table', 'roles'));
    }

    public function laporan_statistik (){
        $table = PelaporanModel::with('fasilitas','user', 'statusPelaporan')->paginate(10);
        $fasilitas = FasilitasModel::all();
        $user = UserModel::all();
        $status = StatusPelaporanModel::all();
        return view('pages.admin.laporan-statistik.index', compact('table', 'fasilitas', 'user','status'));
    }

    public function user_add(Request $request)
    {
        try {
            $existingIdentitas = UserModel::where('identitas', $request->identitas)->first();
            if ($existingIdentitas) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', 'Gagal membuat akun, Identitas sudah digunakan!');
            }

            $existingEmail = UserModel::where('email', $request->email)->first();
            if ($existingEmail) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', 'Gagal membuat akun, Email sudah digunakan!');
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|max:50',
                'identitas' => 'required|max:20|unique:m_user,identitas',
                'email' => 'required|email|max:60|unique:m_user,email',
                'password' => 'required|min:5|max:20',
                'role_id' => 'required|exists:m_role,role_id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }

            UserModel::create([
                'nama' => $request->nama,
                'identitas' => $request->identitas,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            return redirect()->route('admin.user')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('admin.user')
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function gedung()
    {
        return view('pages.admin.gedung.index');
    }
    public function fasilitas()
    {
        return view('pages.admin.fasilitas.index');
    }

    // public function laporan_statistik() {
    //     $laporan = [
    //         ['judul' => 'Perbaikan AC', 'status' => 'Selesai', 'tanggal' => '2025-05-01'],
    //         ['judul' => 'Kerusakan Internet', 'status' => 'Diproses', 'tanggal' => '2025-05-20'],
    //     ];

    //     return view('pages.admin.laporan-statistik.index', compact('laporan'));
    // }
}
