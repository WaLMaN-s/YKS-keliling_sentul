<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ScheduleSlotController as AdminScheduleSlotController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - YKS Keliling Sentul (Figma Design Specification)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. FRONT OFFICE / ROLE USER (PORTAL PUBLIK)
// ==========================================

// Home & Static Info Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-yks', [HomeController::class, 'about'])->name('about');
Route::get('/galeri-testimoni', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/informasi', [HomeController::class, 'info'])->name('info');
Route::get('/paket', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{slug}', [PackageController::class, 'show'])->name('packages.show');

// Booking Flow (4 Steps: Jadwal, Data Pemesan, Pembayaran, Konfirmasi)
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/pembayaran/{booking_code}', [BookingController::class, 'payment'])->name('payment.show');
Route::post('/pembayaran/{booking_code}/bukti', [BookingController::class, 'uploadProof'])->name('payment.upload');
Route::get('/lacak-pesanan', [BookingController::class, 'track'])->name('booking.track');

// User Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/riwayat-saya', [AuthController::class, 'history'])->name('user.history');
});

// ==========================================
// 2. BACK OFFICE / ROLE ADMIN (ADMINISTRASI YKS)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Ringkasan / Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Pembayaran (Antrean Bukti Transfer)
    Route::get('/verifikasi-pembayaran', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::post('/verifikasi-pembayaran/{booking_code}/setujui', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifikasi-pembayaran/{booking_code}/tolak', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    // Data Reservasi
    Route::get('/reservasi', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservasi/{booking_code}', [AdminReservationController::class, 'show'])->name('reservations.show');

    // Jadwal & Slot
    Route::get('/jadwal-slot', [AdminScheduleSlotController::class, 'index'])->name('schedules.index');

    // Kelola Paket (CRUD, Upload Foto & Toggle Status Aktif/Nonaktif)
    Route::get('/paket', [AdminPackageController::class, 'index'])->name('packages.index');
    Route::post('/paket', [AdminPackageController::class, 'store'])->name('packages.store');
    Route::put('/paket/{id}', [AdminPackageController::class, 'update'])->name('packages.update');
    Route::patch('/paket/{id}/toggle-status', [AdminPackageController::class, 'toggleStatus'])->name('packages.toggle_status');
    Route::delete('/paket/{id}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');

    // Data Pelanggan
    Route::get('/pelanggan', [AdminCustomerController::class, 'index'])->name('customers.index');

    // Laporan & Ekspor (CSV & PDF)
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.export_csv');

    // Jam Operasional & Pengaturan Pos
    Route::get('/jam-operasional', [AdminSettingController::class, 'index'])->name('settings.index');
});
