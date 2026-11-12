<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ScheduleSlotController as AdminScheduleSlotController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
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
// 1. FRONT OFFICE / ROLE USER (PORTAL PUBLIK TANPA LOGIN)
// ==========================================

// Home & Static Info Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-yks', [HomeController::class, 'about'])->name('about');
Route::get('/galeri-testimoni', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/informasi', [HomeController::class, 'info'])->name('info');
Route::get('/paket', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{slug}', [PackageController::class, 'show'])->name('packages.show');

// Booking Flow Tanpa Login User (4 Steps: Jadwal, Data Pemesan, Pembayaran, Konfirmasi & Lacak Status)
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/pembayaran/{booking_code}', [BookingController::class, 'payment'])->name('payment.show');
Route::post('/pembayaran/{booking_code}/bukti', [BookingController::class, 'uploadProof'])->name('payment.upload');
Route::get('/lacak-pesanan', [BookingController::class, 'track'])->name('booking.track');

// ==========================================
// 2. BACK OFFICE / ROLE ADMIN (ADMINISTRASI YKS)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Autentikasi Admin (/admin/login & /admin/logout)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Seluruh modul /admin/* wajib login Admin terlebih dahulu
    Route::middleware('admin')->group(function () {
        // 1. Ringkasan / Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // 2. Kelola Paket (CRUD, Upload Foto & Toggle Status Aktif/Nonaktif)
        Route::get('/paket', [AdminPackageController::class, 'index'])->name('packages.index');
        Route::post('/paket', [AdminPackageController::class, 'store'])->name('packages.store');
        Route::put('/paket/{id}', [AdminPackageController::class, 'update'])->name('packages.update');
        Route::patch('/paket/{id}/toggle-status', [AdminPackageController::class, 'toggleStatus'])->name('packages.toggle_status');
        Route::delete('/paket/{id}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');

        // 3. Jadwal & Slot
        Route::get('/jadwal-slot', [AdminScheduleSlotController::class, 'index'])->name('schedules.index');
        Route::post('/jadwal-slot/sesi', [AdminScheduleSlotController::class, 'updateSessions'])->name('schedules.update_sessions');
        Route::post('/jadwal-slot/blokir', [AdminScheduleSlotController::class, 'blockDate'])->name('schedules.block_date');
        Route::delete('/jadwal-slot/blokir/{date}', [AdminScheduleSlotController::class, 'unblockDate'])->name('schedules.unblock_date');

        // 4. Data Reservasi
        Route::get('/reservasi', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::patch('/reservasi/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update_status');

        // 5. Verifikasi Pembayaran
        Route::get('/verifikasi', [AdminVerificationController::class, 'index'])->name('verifications.index');
        Route::patch('/verifikasi/{id}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
        Route::patch('/verifikasi/{id}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

        // 6. Data Pelanggan
        Route::get('/pelanggan', [AdminCustomerController::class, 'index'])->name('customers.index');

        // 7. Laporan
        Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');

        // 8. Jam Operasional
        Route::get('/jam-operasional', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/jam-operasional', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/jam-operasional/pengecualian', [AdminSettingController::class, 'storeException'])->name('settings.store_exception');
        Route::delete('/jam-operasional/pengecualian/{index}', [AdminSettingController::class, 'destroyException'])->name('settings.destroy_exception');
    });
});
