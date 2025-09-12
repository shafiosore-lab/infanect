<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AIController;

/*
|--------------------------------------------------------------------------
| Activity & Training Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // AI Assistant
    Route::get('ai/chat', [AIController::class, 'chat'])->name('ai.chat');
    Route::post('ai/chat', [AIController::class, 'sendMessage'])->name('ai.send-message');

    // Legacy Routes for backward compatibility
    Route::get('training-modules', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('training-modules.index');
    Route::get('training-modules/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('training-modules.show');

    Route::get('parenting-modules', [\App\Http\Controllers\ParentingModuleController::class, 'index'])->name('parenting-modules.index');
    Route::get('parenting-modules/{id}', [\App\Http\Controllers\ParentingModuleController::class, 'show'])->name('parenting-modules.show');

    // Legacy user bookings redirect
    Route::get('user/bookings', function () {
        return redirect()->route('client.bookings.index');
    })->name('user.bookings.index');
});
