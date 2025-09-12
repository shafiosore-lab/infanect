<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfessionalProviderDashboardController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\Provider\BookingController;
use App\Http\Controllers\Provider\ReviewController;
use App\Http\Controllers\Provider\CommunicationController;

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Routes specific to service providers (both professional and bonding)
|
*/

// Provider Dashboard
Route::get('/', [ProfessionalProviderDashboardController::class, 'index'])->name('dashboard');

// Service Management
Route::resource('services', ServiceController::class);

// Booking Management
Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

// Client Reviews
Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

// Communications
Route::post('sms/bulk', [CommunicationController::class, 'sendBulkSMS'])->name('sms.bulk');
Route::post('email/campaign', [CommunicationController::class, 'sendEmailCampaign'])->name('email.campaign');
        ->name('provider.store');

    /**
     * Provider Management
     */
    Route::prefix('provider')->name('provider.')->group(function () {
        // Service Management
        Route::resource('services', ServiceController::class);

        // Booking Management
        Route::get('bookings', [BookingController::class, 'index'])
            ->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])
            ->name('bookings.show');
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])
            ->name('bookings.update-status');

        // Reviews & Feedback
        Route::get('reviews', [ReviewController::class, 'index'])
            ->name('reviews.index');
    });
});

/**
 * Public Provider Directory (visible without login)
 */
Route::get('providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');
