 <?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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
    // Rooms Management (via FastAPI)
    Route::get('/rooms', [ApiBookingController::class, 'indexroom'])->name('rooms.index');
    Route::get('/rooms/{room}', [ApiBookingController::class, 'show'])->name('rooms.show');
    // Mobile App Booking Management (via FastAPI)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [ApiBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [ApiBookingController::class, 'showBooking'])->name('show');
        Route::put('/{booking}/approve', [ApiBookingController::class, 'approve'])->name('approve');
        Route::put('/{booking}/reject', [ApiBookingController::class, 'reject'])->name('reject');
    });
});

require __DIR__.'/auth.php';
