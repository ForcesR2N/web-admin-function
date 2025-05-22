<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApiBookingController; // New controller for API bookings

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Regular room booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('booking.destroy');

    Route::get('/rooms', [BookingController::class, 'indexroom'])->name('rooms.index');
    Route::get('/rooms/{room}', [BookingController::class, 'show'])->name('rooms.show');

    // New routes for mobile app booking requests
    Route::get('/mobile-bookings', [ApiBookingController::class, 'index'])->name('mobile-bookings.index');
    Route::put('/mobile-bookings/{booking}/approve', [ApiBookingController::class, 'approve'])->name('mobile-bookings.approve');
    Route::put('/mobile-bookings/{booking}/reject', [ApiBookingController::class, 'reject'])->name('mobile-bookings.reject');
});

require __DIR__.'/auth.php';
