<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SarpraController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\UsersController;
use App\Livewire\ManageRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin'])->name('postlogin');
Route::get('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('keluar');
Route::post('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('keluar');



Route::middleware('auth')->group(function () {
    Route::get('/', fn(): RedirectResponse => match (Auth::user()->role_id) {
        1 => redirect()->route('admin'),
        2 => redirect()->route('sarpra'),
        3 => redirect()->route('teknisi'),
        4 => redirect()->route('users'),
        default => route('login'),
    });

    Route::middleware('role:1')->prefix('admin')->group(function (): void {
        Route::get('/', [AdminController::class, 'dasbor'])->name('admin');
        Route::prefix('role')->group(function (): void {
            Route::get('/', [AdminController::class, 'role'])->name('admin.role');
            Route::post('/add', [AdminController::class, 'role_add'])->name('admin.role-add');
        });
        Route::prefix('user')->group(function (): void {
            Route::get('/', [AdminController::class, 'user'])->name('admin.user');
            Route::post('/add', [AdminController::class, 'user_add'])->name('admin.user-add');
        });
    });
    Route::middleware('role:2')->prefix('sarpra')->group(function (): void {
        Route::get('/', [SarpraController::class, 'dasbor'])->name('sarpra');
    });
    Route::middleware('role:3')->prefix('teknisi')->group(function (): void {
        Route::get('/', [TeknisiController::class, 'perbaikan'])->name('teknisi');
    });
    Route::middleware('role:4')->prefix('users')->group(function (): void {
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::get('/status-laporan', [UsersController::class, 'statusLaporan'])->name('status-laporan');
    });
});



//lucu lucuan
Route::get('/realtime-clock', function () {
    return Carbon::now()->translatedFormat('l, d F Y H:i:s');
});
