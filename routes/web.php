<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\TarifSppController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
    Route::get('/lupa-password',   [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/lupa-password',  [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}',  [ForgotPasswordController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
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
     Route::get('/profile',           [ProfileController::class, 'edit'])       ->name('profile.edit');
    Route::post('/profile',          [ProfileController::class, 'update'])     ->name('profile.update');
    Route::delete('/profile/foto',   [ProfileController::class, 'hapusFoto']) ->name('profile.foto.hapus');
    Route::post('/profile/password', [ProfileController::class, 'gantiPassword'])->name('profile.password');
 
    // Admin reset password user lain
    Route::post('/admin/users/{user}/reset-password',
        [ProfileController::class, 'adminResetPassword'])
        ->name('admin.user.reset-password')
        ->middleware('role:admin');

    Route::get('/', fn() => redirect()->route('dashboard'));

    /*
    |----------------------------------------------------------------------
    | DASHBOARD
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin,bendahara,kepala_sekolah,guru');

    Route::get('/dashboard/chart-absensi', [DashboardController::class, 'chartAbsensi'])
    ->name('dashboard.chart-absensi')
    ->middleware('role:admin,kepala_sekolah');

    Route::get('/dashboard/chart-spp', [DashboardController::class, 'chartSpp'])
    ->name('dashboard.chart-spp')
    ->middleware('role:admin,bendahara,kepala_sekolah');

    /*
    |----------------------------------------------------------------------
    | SISWA
    |
    | PENTING: Route spesifik (/siswa/import, /siswa/create, dll) HARUS
    |          didaftarkan SEBELUM wildcard {siswa} agar Laravel tidak
    |          menganggap "import" atau "create" sebagai parameter model.
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // ── Input manual ──
        Route::get('/siswa/create',       [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa',             [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}',      [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}',   [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // ── Kelola status siswa (keluar / aktifkan kembali) ──
        // POST /siswa/{siswa}/keluarkan → nonaktifkan dengan tanggal & keterangan resmi
        // POST /siswa/{siswa}/aktifkan  → aktifkan kembali siswa yang nonaktif
        Route::post('/siswa/{siswa}/keluarkan', [SiswaController::class, 'keluarkan'])->name('siswa.keluarkan');
        Route::post('/siswa/{siswa}/aktifkan',  [SiswaController::class, 'aktifkan']) ->name('siswa.aktifkan');

        // ── Import Excel ──
        // GET  /siswa/import          → form halaman import
        // POST /siswa/import          → proses upload & simpan data
        // GET  /siswa/import/template → download file template .xlsx
        Route::get('/siswa/import',          [SiswaController::class, 'importForm'])      ->name('siswa.import.form');
        Route::post('/siswa/import',         [SiswaController::class, 'import'])          ->name('siswa.import');
        Route::get('/siswa/import/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.import.template');
    });

    Route::middleware('role:admin,bendahara,kepala_sekolah')->group(function () {
        Route::get('/siswa',         [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    });

    /*
    |----------------------------------------------------------------------
    | GURU
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
    |----------------------------------------------------------------------
    */

    Route::middleware('role:guru')->group(function () {
        Route::get('/absensi',           [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
        Route::get('/absensi/izin',      [AbsensiController::class, 'formIzin'])->name('absensi.izin.form');
        Route::post('/absensi/izin',     [AbsensiController::class, 'storeIzin'])->name('absensi.izin.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/absensi/kelola',             [AbsensiController::class, 'kelola'])->name('absensi.kelola');
        Route::post('/absensi/manual',            [AbsensiController::class, 'simpanManual'])->name('absensi.manual');
        Route::post('/absensi/auto-alpha',        [AbsensiController::class, 'autoAlpha'])->name('absensi.auto-alpha');
        Route::delete('/absensi/{absensi}/hapus', [AbsensiController::class, 'hapus'])->name('absensi.hapus');
    });

    /*
    |----------------------------------------------------------------------
    | LAPORAN ABSENSI
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/laporan/absensi',
            [LaporanController::class, 'absensi'])
            ->name('absensi.laporan');

        Route::get('/laporan/absensi/cetak',
            [LaporanController::class, 'cetakAbsensi'])
            ->name('absensi.cetak-laporan');
    });

    /*
    |----------------------------------------------------------------------
    | SPP
    |----------------------------------------------------------------------
    */

    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/spp/konfirmasi',         [SppController::class, 'konfirmasi'])->name('spp.konfirmasi');
        Route::post('/spp/konfirmasi/satu',   [SppController::class, 'konfirmasiSatu'])->name('spp.konfirmasi.satu');
        Route::post('/spp/konfirmasi/massal', [SppController::class, 'konfirmasiMassal'])->name('spp.konfirmasi.massal');

        Route::get('/spp/input', [SppController::class, 'create'])->name('spp.create');
        Route::post('/spp',      [SppController::class, 'store'])->name('spp.store');

        Route::delete('/spp/{spp}', [SppController::class, 'destroy'])->name('spp.destroy');
    });

    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/spp/tarif',            [TarifSppController::class, 'index'])  ->name('spp.tarif.index');
        Route::post('/spp/tarif',           [TarifSppController::class, 'store'])  ->name('spp.tarif.store');
        Route::put('/spp/tarif/{tarif}',    [TarifSppController::class, 'update']) ->name('spp.tarif.update');
        Route::delete('/spp/tarif/{tarif}', [TarifSppController::class, 'destroy'])->name('spp.tarif.destroy');
    });

    Route::middleware('role:admin,bendahara,kepala_sekolah')->group(function () {
        Route::get('/spp',                [SppController::class, 'index'])->name('spp.index');
        Route::get('/spp/tunggakan',      [SppController::class, 'tunggakan'])->name('spp.tunggakan');
        Route::get('/spp/laporan',        [SppController::class, 'laporan'])->name('spp.laporan');
        Route::get('/spp/{spp}/kwitansi', [SppController::class, 'kwitansi'])->name('spp.kwitansi');
        Route::get('/spp/{spp}/cetak',    [SppController::class, 'cetakKwitansi'])->name('spp.cetak');
    });

    /*
    |----------------------------------------------------------------------
    | LAPORAN GABUNGAN
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,kepala_sekolah,bendahara')->group(function () {
        Route::get('/laporan/spp',       [LaporanController::class, 'spp'])      ->name('laporan.spp');
        Route::get('/laporan/spp/cetak', [LaporanController::class, 'cetakSpp']) ->name('laporan.spp.cetak');
    });

});