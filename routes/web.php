<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\SearchController;

// Halaman Utama (Home)
Route::get('/', [HomeController::class, 'index']);

// Rute Login & Autentikasi
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rute Default Laravel Auth
Auth::routes();

// Rute setelah login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rute untuk Absensi RFID
Route::post('/attendance/record', [AttendanceController::class, 'recordAttendance']);

// Grup Admin dengan Middleware `auth`
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Menampilkan & Mengedit Data Santri
    Route::get('/santri/{id}', [AdminController::class, 'showSantri'])->name('admin.santri.detail');
    Route::get('/santri/{id}/edit', [AdminController::class, 'editSantri'])->name('admin.santri.edit');
    Route::match(['put', 'patch'], '/santri/{id}', [AdminController::class, 'updateSantri'])->name('admin.santri.update');
    Route::put('/admin/santri/{id}', [SantriController::class, 'update'])->name('admin.santri.update');

    // Input Absensi Manual
    Route::get('/attendance/manual/{santri_id}', [AdminController::class, 'showInputAbsensiManual'])->name('admin.attendance.manual');
    Route::post('/attendance/manual/{santri_id}', [AttendanceController::class, 'inputAbsensiManual']);

    // Fitur Pencarian di Dashboard Admin
    Route::get('/search', [SearchController::class, 'search'])->name('admin.search');
    Route::get('/santri/export/{format}', [SantriController::class, 'export'])->name('admin.santri.export');

    // Rute untuk mengedit dan memperbarui waktu sholat
    Route::get('/prayer/edit', [PrayerTimeController::class, 'edit'])->name('admin.prayer.edit');
    Route::put('/prayer/update', [PrayerTimeController::class, 'update'])->name('admin.prayer.update');

    // RFID Scanning
    Route::get('/scan-rfid', [AdminController::class, 'getScannedRFID'])->name('admin.scan.rfid');

    Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
    Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');
    Route::delete('/santri/{id}', [SantriController::class, 'destroy'])->name('santri.destroy');
    Route::put('/santri/{id}', [SantriController::class, 'update'])->name('santri.update');

    Route::put('/attendance/{id}/update', [AttendanceController::class, 'update'])->name('admin.attendance.update');
    Route::delete('/attendance/{id}/destroy', [AttendanceController::class, 'destroy'])->name('admin.attendance.destroy');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('admin.attendance.store');

    Route::get('/attendance', [AttendanceController::class, 'manualAttendanceForm'])->name('admin.attendance.form');
    Route::post('/attendance', [AttendanceController::class, 'storeManualAttendance'])->name('admin.attendance.store');

    Route::get('/santri/{santri_id}/attendance/export/{format}', [AttendanceController::class, 'export'])->name('admin.santri.attendance.export');

    Route::get('/attendance/manual', [AdminController::class, 'showInputAbsensiManualAll'])->name('admin.attendance.manual');

    Route::get('/export/excel', [AdminController::class, 'exportExcel'])->name('admin.export.excel');
    Route::get('/export/csv', [AdminController::class, 'exportCsv'])->name('admin.export.csv');
});

Route::get('/home', [AttendanceController::class, 'publicAttendance'])->name('public.absensi');
Route::get('/api/attendance/latest', [AttendanceController::class, 'getLatestAttendance']);
