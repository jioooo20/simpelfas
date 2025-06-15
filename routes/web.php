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

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:1')->prefix('admin')->group(function (): void {
        Route::get('/', [AdminController::class, 'dasbor'])->name('admin');
        Route::get('/laporan', [AdminController::class, 'laporan_statistik'])->name('laporan.index');
        Route::prefix('user')->group(function (): void {
            Route::get('/', [AdminController::class, 'user'])->name('admin.user');
            Route::post('/add', [AdminController::class, 'user_add'])->name('admin.user-add');
            Route::post('/import-user', [AdminController::class, 'import_user'])->name('admin.import-user');
        });

        Route::prefix('gedung')->group(function (): void {
            Route::get('/', [AdminController::class, 'gedung'])->name('admin.gedung');
        });

        Route::prefix('fasilitas')->group(function (): void {
            Route::get('/', [AdminController::class, 'fasilitas'])->name('admin.fasilitas');
        });

        Route::prefix('barang')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\BarangController::class, 'index'])->name('admin.barang');
            Route::get('/create', [\App\Http\Controllers\BarangController::class, 'create'])->name('admin.barang.create');
            Route::get('/{id}', [\App\Http\Controllers\BarangController::class, 'show'])->name('admin.barang.show');
            Route::post('/', [\App\Http\Controllers\BarangController::class, 'store'])->name('admin.barang.store');
            Route::get('/{id}/edit', [\App\Http\Controllers\BarangController::class, 'edit'])->name('admin.barang.edit');
            Route::put('/{id}', [\App\Http\Controllers\BarangController::class, 'update'])->name('admin.barang.update');
            Route::delete('/{id}', [\App\Http\Controllers\BarangController::class, 'destroy'])->name('admin.barang.destroy');
        });

        Route::prefix('laporan-kerusakan')->group(function (): void {
            Route::get('/', [\App\Http\Controllers\LaporanKerusakanController::class, 'index'])->name('admin.laporan-kerusakan.index');
            Route::get('/{id}', [\App\Http\Controllers\LaporanKerusakanController::class, 'show'])->name('admin.laporan-kerusakan.show');
        });

    });
    Route::middleware('role:2')->prefix('sarpra')->group(function (): void {
        Route::get('/', [SarpraController::class, 'dasbor'])->name('sarpra');
        Route::get('/laporan-kerusakan-fasilitas', [SarpraController::class, 'laporan_kerusakan_fasilitas'])->name('sarpra.laporan-kerusakan-fasilitas');
        Route::get('/rekomendasi-prioritas-perbaikan', [SarpraController::class, 'rekomendasi_prioritas_perbaikan'])->name('sarpra.rekomendasi-prioritas-perbaikan');
        Route::get('/statistik-fasilitas', [SarpraController::class, 'statistikFasilitas'])->name('statistik-fasilitas');
        Route::get('/feedback', [SarpraController::class, 'count-total'])->name('feedback.index');
        Route::get('/penugasan-perbaikan', [SarpraController::class, 'penugasan_perbaikan'])->name('penugasan-perbaikan');
    });
    Route::middleware('role:3')->prefix('teknisi')->group(function (): void {
        Route::get('/', [TeknisiController::class, 'perbaikan'])->name('teknisi');
        Route::group(['prefix' => 'perbaikan'], function (): void {
            Route::get('/detail/{id}', [TeknisiController::class, 'perbaikanShow'])->name('detail-perbaikan');
            Route::post('/update', [TeknisiController::class, 'update'])->name('update-perbaikan');
        });
        Route::group(['prefix' => 'riwayat-perbaikan'], function (): void {
            Route::get('/', [TeknisiController::class, 'riwayat'])->name('riwayat-perbaikan');
            Route::get('/detail', [TeknisiController::class, 'riwayatShow'])->name('detail-riwayat-perbaikan');
        });
    });
    Route::middleware('role:4,5,6')->prefix('users')->group(function (): void {
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::post('/pelaporan', [UsersController::class, 'storePelaporan'])->name('store-pelaporan');
        Route::get('/status-laporan', [UsersController::class, 'statusLaporan'])->name('status-laporan');
        Route::get('/lokasi-options', [UsersController::class, 'getLokasiOptions'])->name('lokasi-options');
        Route::get('/feedback',[UsersController::class, 'UmpanBalik'])->name('users.feedback'); //
        Route::get('/feedback-create/{perbaikan_id}',[UsersController::class, 'UmpanBalik_Create'])->name('feedback-create');
        Route::post('/feedback/store', [UsersController::class, 'storeFeedback'])->name('feedback-store');
        Route::get('/laporan-data', [UsersController::class, 'getLaporanData'])->name('laporan-data');
        Route::get('/laporan-detail/{id}', [UsersController::class, 'getLaporanDetail'])->name('laporan-detail');
    });
});



//lucu lucuan
Route::get('/realtime-clock', function () {
    return Carbon::now()->translatedFormat('l, d F Y H:i:s');
});

Route::post('/notifikasi/read-all', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifikasi.readAll');
