<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Routes specific to client/family users of the application
|
*/

// Client Dashboard
Route::get('/', [ClientDashboardController::class, 'index'])->name('dashboard');

// Bookings
Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/', [UserBookingController::class, 'index'])->name('index');
    Route::get('/{booking}', [UserBookingController::class, 'show'])->name('show');
    Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('cancel');
});

// Activities
Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
Route::post('activities/{activity}/book', [ActivityController::class, 'book'])->name('activities.book');

// Services
Route::get('services', [ServiceController::class, 'index'])->name('services.index');
Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::post('services/{service}/book', [ServiceController::class, 'book'])->name('services.book');
