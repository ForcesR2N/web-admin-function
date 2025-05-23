<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApiBookingController;
use App\Http\Controllers\ApiAuthController;

// API Auth Routes
Route::get('/api/login', [ApiAuthController::class, 'showLogin'])->name('api.login.show');
Route::post('/api/login', [ApiAuthController::class, 'login'])->name('api.login');
Route::post('/api/logout', [ApiAuthController::class, 'logout'])->name('api.logout');

// Default redirect to API login
Route::get('/', function () {
    return redirect('/api/login');
});

// Dashboard route (auth check is in controller)
Route::get('/dashboard', function () {
    // Check auth here manually
    if (!ApiAuthController::isAuthenticated()) {
        return redirect('/api/login')->withErrors([
            'email' => 'Please login to access this page',
        ]);
    }
    return view('dashboard');
})->name('dashboard');

// Profile routes
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
});

// Rooms Management (via FastAPI)
Route::controller(ApiBookingController::class)->group(function () {
    Route::get('/rooms', 'indexroom')->name('rooms.index');
    Route::get('/rooms/{room}', 'show')->name('rooms.show');
});

// Mobile App Booking Management (via FastAPI)
Route::prefix('bookings')->name('bookings.')->controller(ApiBookingController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{booking}', 'showBooking')->name('show');
    Route::put('/{booking}/approve', 'approve')->name('approve');
    Route::put('/{booking}/reject', 'reject')->name('reject');
});
