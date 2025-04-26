<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin'])->name('postlogin');
Route::post('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('keluar');
// Route::get('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('keluar');



Route::middleware('auth')->group(function () {
    Route::get('/', fn(): RedirectResponse => match (Auth::user()->role_id) {
        1 => redirect()->route('admin'),
        2 => redirect()->route('sarpra'),
        3 => redirect()->route('teknisi'),
        4 => redirect()->route('users'),
        default => abort(403, 'Unauthorized action.'),
    });

    Route::middleware('role:1')->prefix('admin')->group(function (): void {
        Route::get('/', [AdminController::class, 'dasbor'])->name('admin');
    });
});
