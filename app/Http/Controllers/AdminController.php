<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dasbor()
    {
        return view('pages.admin.dasbor.index');
    }

    public function role()
    {
        $table = RoleModel::with('user')
            ->select('m_role.*')
            ->selectRaw('COUNT(m_user.user_id) as jumlah_user')
            ->leftJoin('m_user', 'm_role.role_id', '=', 'm_user.role_id')
            ->groupBy('m_role.role_id')
            ->paginate(10);

        return view('pages.admin.manage-role.index', compact('table'));
    }

    public function role_add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_kode' => 'required|max:10',
                'role_nama' => 'required|max:50',
                'role_deskripsi' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.role')
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }

            $existingRole = RoleModel::where('role_kode', $request->role_kode)->first();
            if ($existingRole) {
                return redirect()->route('admin.role')
                    ->withInput()
                    ->with('error', 'Gagal menambahkan role. Kode sudah digunakan!!');
            }

            RoleModel::create([
                'role_kode' => $request->role_kode,
                'role_nama' => $request->role_nama,
                'role_deskripsi' => $request->role_deskripsi,
            ]);

            return redirect()->route('admin.role')
                ->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('admin.role')
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function user()
    {
        $table = UserModel::with('role');
        $roles = RoleModel::all();
        return view('pages.admin.manage-user.index', compact('table', 'roles'));
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
}
