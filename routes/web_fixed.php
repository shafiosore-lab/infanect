<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainingModulesController;
use App\Http\Controllers\Admin\AdminBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth','role:super-admin'])->group(function(){
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
});

// Training Modules Routes
Route::middleware('auth')->group(function () {
    Route::get('/training-modules', [TrainingModulesController::class, 'index'])->name('training-modules.index');
    Route::post('/training-modules/complete', [TrainingModulesController::class, 'completeModule'])->name('training-modules.complete');
});

// Add your other existing routes below this line
