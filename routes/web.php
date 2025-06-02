<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SarpraController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\UsersController;
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
        4, 5, 6 => redirect()->route('users'),
        default => route('login'),
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:1')->prefix('admin')->group(function (): void {
        Route::get('/', [AdminController::class, 'dasbor'])->name('admin');
        Route::prefix('user')->group(function (): void {
            Route::get('/', [AdminController::class, 'user'])->name('admin.user');
            Route::post('/add', [AdminController::class, 'user_add'])->name('admin.user-add');
        });

        Route::prefix('gedung')->group(function (): void {
            Route::get('/', [AdminController::class, 'gedung'])->name('admin.gedung');
        });

        Route::prefix('fasilitas')->group(function (): void {
            Route::get('/', [AdminController::class, 'fasilitas'])->name('admin.fasilitas');
        });
    });
    Route::middleware('role:2')->prefix('sarpra')->group(function (): void {
        Route::get('/', [SarpraController::class, 'dasbor'])->name('sarpra');
        Route::get('/laporan-kerusakan-fasilitas', [SarpraController::class, 'laporan_kerusakan_fasilitas'])->name('sarpra.laporan-kerusakan-fasilitas');
        Route::get('/rekomendasi-prioritas-perbaikan', [SarpraController::class, 'rekomendasi_prioritas_perbaikan'])->name('sarpra.rekomendasi-prioritas-perbaikan');
        Route::get('/statistik-fasilitas', [SarpraController::class, 'statistikFasilitas'])->name('statistik-fasilitas');
        Route::get('/frekuensi-perbaikan', [SarpraController::class, 'frekuensiPerbaikan'])->name('frekuensi-perbaikan');
        Route::get('/kepuasan-pengguna', [SarpraController::class, 'kepuasanPengguna'])->name('kepuasan-pengguna');
        Route::get('/perencanaan-pemeliharaan', [SarpraController::class, 'perencanaanPemeliharaan'])->name('perencanaan-pemeliharaan');
    });
    Route::middleware('role:3')->prefix('teknisi')->group(function (): void {
        Route::get('/', [TeknisiController::class, 'perbaikan'])->name('teknisi');
    });
    Route::middleware('role:4,5,6')->prefix('users')->group(function (): void {
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::post('/pelaporan', [UsersController::class, 'storePelaporan'])->name('store-pelaporan');
        Route::get('/status-laporan', [UsersController::class, 'statusLaporan'])->name('status-laporan');
        Route::get('/lokasi-options', [UsersController::class, 'getLokasiOptions'])->name('lokasi-options');
        Route::get('/laporan-data', [UsersController::class, 'getLaporanData'])->name('laporan-data');
        Route::get('/laporan-detail/{id}', [UsersController::class, 'getLaporanDetail'])->name('laporan-detail');
    });
});



//lucu lucuan
Route::get('/realtime-clock', function () {
    return Carbon::now()->translatedFormat('l, d F Y H:i:s');
});
