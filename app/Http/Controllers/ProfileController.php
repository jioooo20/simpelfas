<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil user dari UserModel berdasarkan user_id yang sedang login
        $user = UserModel::with('role')->find(Auth::id());
        return view('pages.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = UserModel::with('role')->find(Auth::id());
        return view('pages.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = UserModel::find(Auth::id());

        $validatedData = $request->validate([
            'nama' => 'required|string|max:50',
            'password' => 'nullable|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_password' => 'required_with:password|string', // tambahkan validasi ini
    ]);

        // Update nama
        $user->nama = $validatedData['nama'];

        // Jika ada password baru
        if ($request->filled('password')) {
    if (!$request->filled('current_password')) {
        return back()->withErrors(['current_password' => 'Password lama wajib diisi jika ingin mengubah password.'])->withInput();
    }
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
    }
    $user->password = bcrypt($validatedData['password']);
}

        // Jika ada gambar profil baru
        if ($request->hasFile('profile_image')) {
            // Hapus gambar lama jika ada
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}