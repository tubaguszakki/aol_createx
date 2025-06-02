<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Midtrans notification (outside auth middleware)
Route::post('/midtrans/notification', [BookingController::class, 'notification'])->name('midtrans.notification');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // User dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/bookings', [DashboardController::class, 'bookings'])->name('dashboard.bookings');
    
    // Booking routes
    Route::prefix('booking')->group(function () {
        Route::post('/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
        Route::get('/failed/{booking}', [BookingController::class, 'failed'])->name('booking.failed');
        Route::get('/pending/{booking}', [BookingController::class, 'pending'])->name('booking.pending');
        Route::get('/pay/{booking}', [BookingController::class, 'pay'])->name('booking.pay'); // ← TAMBAHAN BARU
    });
    
    // Admin routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::patch('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.booking.status');
        Route::post('/bookings/{booking}/check-payment', [AdminController::class, 'checkPaymentStatus'])->name('admin.booking.check-payment');
        Route::post('/bookings/{booking}/regenerate-pin', [AdminController::class, 'regeneratePin'])->name('admin.booking.regenerate-pin');
    });
});
