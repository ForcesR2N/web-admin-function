<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile booking API endpoints
Route::post('/bookings', [ApiBookingController::class, 'apiCreate']);
Route::get('/bookings/{id}/status', [ApiBookingController::class, 'apiCheckStatus']);

// These would normally have authentication, but for demonstration purposes we're leaving them open
// In a real application, you should protect these routes with proper authentication
