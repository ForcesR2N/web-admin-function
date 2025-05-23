<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiBookingController;

// Public endpoints (for mobile app)
Route::post('/booking', [ApiBookingController::class, 'apiCreate']);
Route::get('/booking/{id}', [ApiBookingController::class, 'apiCheckStatus']);

// IMPORTANT: These match your Flutter app's API calls
Route::get('/bookings/me', [ApiBookingController::class, 'getUserBookings']);

// Protected endpoints (for admin actions)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [ApiBookingController::class, 'getBookings']);
    Route::patch('/booking/host/confirm/{id}', [ApiBookingController::class, 'confirmBooking']);
    Route::patch('/booking/host/cancel/{id}', [ApiBookingController::class, 'cancelBooking']);
});
