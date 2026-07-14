<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\JadwalAuditController;
use App\Http\Controllers\KkaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\TindakLanjutController;
use Illuminate\Support\Facades\Route;

// Halaman awal redirect ke dashboard jika sudah login
Route::get('/', fn() => redirect()->route('dashboard'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Semua route di bawah ini butuh login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Cabang
    Route::resource('cabang', CabangController::class);

    // Jadwal Audit
    Route::resource('jadwal-audit', JadwalAuditController::class);

    // KKA
    Route::resource('kka', KkaController::class);
    Route::post('kka/{kka}/ajukan', [KkaController::class, 'ajukan'])->name('kka.ajukan');
    Route::post('kka/{kka}/review', [KkaController::class, 'review'])->name('kka.review');

    // Scoring
    Route::get('kka/{kka}/scoring', [ScoringController::class, 'show'])->name('scoring.show');
    Route::post('kka/{kka}/scoring/hitung', [ScoringController::class, 'hitung'])->name('scoring.hitung');

    // Temuan
    Route::resource('temuan', TemuanController::class);

    // Evaluasi Temuan (khusus SKAI Pusat)
    Route::resource('evaluasi', EvaluasiController::class)->except(['create', 'store']);

    // Tindak Lanjut
    Route::resource('tindak-lanjut', TindakLanjutController::class);

    // Laporan
    Route::resource('laporan', LaporanController::class);
});
