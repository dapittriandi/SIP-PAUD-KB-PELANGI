<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| AUTH — Tamu (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/login/guru',  [AuthController::class, 'showPinLogin'])->name('login.guru');
    Route::post('/login/guru', [AuthController::class, 'pinLogin'])->name('login.guru.post');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| AREA TERPROTEKSI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));

    /*
    |----------------------------------------------------------------------
    | DASHBOARD
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin,bendahara,kepala_sekolah,guru');

    /*
    |----------------------------------------------------------------------
    | SISWA
    | Aturan: spesifik (/create) WAJIB di atas wildcard {siswa}
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/siswa/create',       [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa',             [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}',      [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}',   [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    Route::middleware('role:admin,bendahara,kepala_sekolah')->group(function () {
        Route::get('/siswa',         [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    });

    /*
    |----------------------------------------------------------------------
    | GURU
    | Aturan: spesifik (/create, /reset-pin, /toggle) di atas {guru}
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/guru/create',           [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru',                 [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{guru}/edit',      [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{guru}',           [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{guru}',        [GuruController::class, 'destroy'])->name('guru.destroy');
        Route::put('/guru/{guru}/reset-pin', [GuruController::class, 'resetPin'])->name('guru.reset-pin');
        Route::put('/guru/{guru}/toggle',    [GuruController::class, 'toggle'])->name('guru.toggle');
    });

    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/guru',        [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
    });

    /*
    |----------------------------------------------------------------------
    | ABSENSI
    | Aturan: semua route spesifik (/kelola, /laporan, /manual, dll)
    |         WAJIB di atas route dengan parameter {absensi}
    |
    | Method controller → nama route
    | checkIn       → absensi.checkin          POST
    | formIzin      → absensi.izin.form        GET   (guru input izin/sakit/tugas mandiri)
    | storeIzin     → absensi.izin.store       POST
    | kelola        → absensi.kelola           GET
    | simpanManual  → absensi.manual           POST
    | autoAlpha     → absensi.auto-alpha       POST
    | hapus         → absensi.hapus            DELETE
    | laporan       → absensi.laporan          GET   (view HTML)
    | cetakLaporan  → absensi.cetak-laporan    GET   (stream PDF)
    |----------------------------------------------------------------------
    */

    // Guru: self check-in + izin mandiri
    Route::middleware('role:guru')->group(function () {
        Route::get('/absensi',           [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
        Route::get('/absensi/izin',      [AbsensiController::class, 'formIzin'])->name('absensi.izin.form');
        Route::post('/absensi/izin',     [AbsensiController::class, 'storeIzin'])->name('absensi.izin.store');
    });

    // Admin: kelola, input manual, auto-alpha, hapus
    Route::middleware('role:admin')->group(function () {
        Route::get('/absensi/kelola',             [AbsensiController::class, 'kelola'])->name('absensi.kelola');
        Route::post('/absensi/manual',            [AbsensiController::class, 'simpanManual'])->name('absensi.manual');
        Route::post('/absensi/auto-alpha',        [AbsensiController::class, 'autoAlpha'])->name('absensi.auto-alpha');
        Route::delete('/absensi/{absensi}/hapus', [AbsensiController::class, 'hapus'])->name('absensi.hapus');
    });

    // Admin & Kepsek: laporan HTML + cetak PDF
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/absensi/laporan',       [AbsensiController::class, 'laporan'])->name('absensi.laporan');
        Route::get('/absensi/cetak-laporan', [AbsensiController::class, 'cetakLaporan'])->name('absensi.cetak-laporan');
    });

    /*
    |----------------------------------------------------------------------
    | SPP
    | Aturan: spesifik (/input, /tunggakan, /laporan) di atas {spp}
    |
    | Method controller → nama route
    | create       → spp.create    GET
    | store        → spp.store     POST
    | destroy      → spp.destroy   DELETE
    | index        → spp.index     GET
    | tunggakan    → spp.tunggakan GET
    | laporan      → spp.laporan   GET
    | kwitansi     → spp.kwitansi  GET  (view HTML kwitansi)
    | cetakKwitansi → spp.cetak    GET  (stream PDF kwitansi)
    |----------------------------------------------------------------------
    */

    // Admin & Bendahara: input + hapus
    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/spp/input',    [SppController::class, 'create'])->name('spp.create');
        Route::post('/spp',         [SppController::class, 'store'])->name('spp.store');
        Route::delete('/spp/{spp}', [SppController::class, 'destroy'])->name('spp.destroy');
    });

    // Admin, Bendahara, Kepsek: lihat semua data SPP
    // Aturan: /tunggakan dan /laporan di atas {spp}
    Route::middleware('role:admin,bendahara,kepala_sekolah')->group(function () {
        Route::get('/spp',                 [SppController::class, 'index'])->name('spp.index');
        Route::get('/spp/tunggakan',       [SppController::class, 'tunggakan'])->name('spp.tunggakan');
        Route::get('/spp/laporan',         [SppController::class, 'laporan'])->name('spp.laporan');
        Route::get('/spp/{spp}/kwitansi',  [SppController::class, 'kwitansi'])->name('spp.kwitansi');
        Route::get('/spp/{spp}/cetak',     [SppController::class, 'cetakKwitansi'])->name('spp.cetak');
    });

    /*
    |----------------------------------------------------------------------
    | LAPORAN GABUNGAN (LaporanController)
    | Terpisah dari AbsensiController — untuk rekap lintas modul
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('/laporan/spp',     [LaporanController::class, 'spp'])->name('laporan.spp');
    });

});