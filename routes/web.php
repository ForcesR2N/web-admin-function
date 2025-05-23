<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApiBookingController;

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

    // Regular room booking routes (existing system)
    Route::prefix('booking')->name('booking.')->group(function() {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
    });

    Route::get('/rooms', [BookingController::class, 'indexroom'])->name('rooms.index');
    Route::get('/rooms/{room}', [BookingController::class, 'show'])->name('rooms.show');

    // API Booking management routes (mobile app bookings)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [ApiBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [ApiBookingController::class, 'show'])->name('show');
        Route::put('/{booking}/approve', [ApiBookingController::class, 'approve'])->name('approve');
        Route::put('/{booking}/reject', [ApiBookingController::class, 'reject'])->name('reject');
    });
});

require __DIR__.'/auth.php';
