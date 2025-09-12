<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes specific to administrators and system managers
|
*/

// Admin Dashboard
Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

/**
 * User Management
 */
Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
Route::post('users/{id}/update-role', [UserManagementController::class, 'updateRole'])->name('users.update-role');

/**
 * Content Management
 */
Route::resource('activities', ActivityController::class);
Route::resource('services', \App\Http\Controllers\ServiceController::class);
Route::resource('modules', \App\Http\Controllers\ModuleController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::resource('roles', \App\Http\Controllers\RoleController::class);
Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class);

/**
 * Analytics & Reports
 */
Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('analytics/retention', [AnalyticsController::class, 'retention'])->name('analytics.retention');
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
        Route::resource('approvals', ApprovalController::class);
        Route::post('approvals/bulk-action', [ApprovalController::class, 'bulkAction'])->name('approvals.bulk-action');

        /**
         * Document Management
         */
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        /**
         * Analytics & Reports
         */
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/retention', [AnalyticsController::class, 'retention'])->name('analytics.retention');
        Route::get('analytics/engagement-heatmaps', [AnalyticsController::class, 'engagementHeatmaps'])->name('analytics.engagement-heatmaps');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });
