<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfessionalProviderDashboardController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\Provider\BookingController;
use App\Http\Controllers\Provider\ReviewController;
use App\Http\Controllers\Provider\ClientController;
use App\Http\Controllers\Provider\ProviderController;

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Routes specific to service providers (both professional and bonding)
|
*/

// Provider-specific routes (require authentication and provider role)
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    // Provider Dashboard
    Route::get('/dashboard', [ProfessionalProviderDashboardController::class, 'index'])->name('dashboard');

    // Service Management
    Route::resource('services', ServiceController::class);

    // Booking Management
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Client Reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

    // Client Management
    Route::resource('clients', ClientController::class);

    // Activity Management
    Route::get('activities', [ProviderController::class, 'activities'])->name('activities.index');
    Route::get('activities/create', [ProviderController::class, 'createActivity'])->name('activities.create');
    Route::post('activities/create', [ProviderController::class, 'createActivity'])->name('activities.store');
    Route::get('activities/{activity}/edit', [ProviderController::class, 'editActivity'])->name('activities.edit');
    Route::put('activities/{activity}', [ProviderController::class, 'editActivity'])->name('activities.update');
    Route::delete('activities/{activity}', [ProviderController::class, 'deleteActivity'])->name('activities.destroy');

    // Metrics
    Route::get('metrics', [ProfessionalProviderDashboardController::class, 'metrics'])->name('metrics');

    // Documents
    Route::get('documents', [ProviderController::class, 'documents'])->name('documents');
    Route::post('documents', [ProviderController::class, 'uploadDocuments'])->name('documents.upload');
});

// Public Provider Directory (visible without login)
Route::get('providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');

use App\Http\Controllers\ProviderMessageController;

Route::prefix('provider')->name('provider.')->middleware(['auth'])->group(function () {
    Route::get('/messages', [ProviderMessageController::class, 'index'])->name('messages.index');
});

use App\Http\Controllers\ProviderNotificationController;

Route::prefix('provider')->name('provider.')->middleware(['auth'])->group(function () {
    Route::get('/notifications', [ProviderNotificationController::class, 'index'])->name('notifications');
});
use App\Http\Controllers\ProviderTransactionController;

Route::prefix('provider')->name('provider.')->middleware(['auth'])->group(function () {
    Route::get('/transactions', [ProviderTransactionController::class, 'index'])->name('transactions.index');
});
use App\Http\Controllers\ProviderPayoutController;
Route::prefix('provider')->name('provider.')->middleware(['auth'])->group(function () {
    Route::get('/payouts', [ProviderPayoutController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/create', [ProviderPayoutController::class, 'create'])->name('payouts.create');
    Route::post('/payouts', [ProviderPayoutController::class, 'store'])->name('payouts.store');
});



use App\Http\Controllers\ProviderFinancialsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/provider/financials', [ProviderFinancialController::class, 'index'])
        ->name('provider.financials');
});

