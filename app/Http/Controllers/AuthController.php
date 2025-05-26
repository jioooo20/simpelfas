<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //login
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        $response = response()->view('auth.login');
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        Log::info('CSRF token saat login:', [csrf_token()]);

        return $response;
    }
    public function postlogin(Request $request)
    {
        $request->validate([
            'identitas' => 'required',
            'password' => 'required|min:5',
        ]);
        $credentials = $request->only('identitas', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('login_error', 'Identitas atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Log::info('Session sebelum logout:', session()->all());
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
