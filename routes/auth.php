<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\ServiceProvider\ServiceProviderDashboardController;

// ==============================
// Guest routes (unauthenticated users)
// ==============================
Route::middleware('guest')->group(function () {

    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ==============================
// Authenticated routes (users that are logged in)
// ==============================
Route::middleware('auth')->group(function () {

    // Email verification
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password confirmation & update
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // ==============================
    // Dashboard Redirect by Role
    // ==============================
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('parent')) {
            return redirect()->route('parent.dashboard');
        } elseif ($user->hasRole('service_provider')) {
            return redirect()->route('service.dashboard');
        }

        return abort(403, 'Unauthorized access.');
    })->name('dashboard');

    // ==============================
    // Role Based Dashboards
    // ==============================

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

    // Parent
    Route::middleware('role:parent')->prefix('parent')->group(function () {
        Route::get('dashboard', [ParentDashboardController::class, 'index'])
            ->name('parent.dashboard');
    });

    // Service Provider
    Route::middleware('role:service_provider')->prefix('service')->group(function () {
        Route::get('dashboard', [ServiceProviderDashboardController::class, 'index'])
            ->name('service.dashboard');
    });
});
