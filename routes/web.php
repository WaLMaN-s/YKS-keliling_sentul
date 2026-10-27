<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - YKS Keliling Sentul (Role User)
|--------------------------------------------------------------------------
*/

// Home & Catalog
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{slug}', [PackageController::class, 'show'])->name('packages.show');

// Booking & Order Tracking
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
