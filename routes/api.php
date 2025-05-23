<?php
// routes/api.php - PERBAIKAN: sesuai dengan backend API endpoints

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiBookingController;

/*
|--------------------------------------------------------------------------
| API Routes - SESUAI BACKEND
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Booking API endpoints - sesuai dengan backend API structure
Route::group(['prefix' => 'api'], function () {

    // Public endpoints (untuk mobile app)
    Route::post('/booking', [ApiBookingController::class, 'apiCreate']);
    Route::get('/booking/{id}', [ApiBookingController::class, 'apiCheckStatus']);
    Route::get('/booking', [ApiBookingController::class, 'apiIndex']);

    // Protected endpoints (untuk admin actions)
    Route::middleware('auth:sanctum')->group(function () {
        Route::patch('/booking/host/confirm/{id}', [ApiBookingController::class, 'apiConfirm']);
        Route::patch('/booking/host/cancel/{id}', [ApiBookingController::class, 'apiCancel']);
        Route::patch('/booking/user/cancel/{id}', [ApiBookingController::class, 'apiUserCancel']);
        Route::delete('/booking/{id}', [ApiBookingController::class, 'apiDelete']);
    });
});

// Legacy endpoints untuk backward compatibility dengan Flutter
Route::post('/bookings', [ApiBookingController::class, 'apiCreate']);
Route::get('/bookings/{id}/status', [ApiBookingController::class, 'apiCheckStatus']);
