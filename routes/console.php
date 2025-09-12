<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Booking;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestNotification;

/*
|--------------------------------------------------------------------------
| Custom Artisan Commands for Infanect
|--------------------------------------------------------------------------
| These commands give you quick shortcuts for development, maintenance,
| and admin workflows.
|--------------------------------------------------------------------------
*/

// Default Laravel "inspire" command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🔹 1. System cleanup (clear all caches)
Artisan::command('system:cleanup', function () {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->info('✅ All system caches cleared successfully!');
})->purpose('Clean up all Laravel caches');

// 🔹 2. Reset & reseed database
Artisan::command('db:reset', function () {
    $this->call('migrate:fresh', ['--seed' => true]);
    $this->info('✅ Database reset and seeded successfully!');
})->purpose('Reset and reseed the database');

// 🔹 3. Clean up old bookings
Artisan::command('bookings:cleanup', function () {
    $count = Booking::where('status', 'cancelled')
        ->orWhere('status', 'expired')
        ->delete();

    $this->info("🗑️ {$count} old bookings cleaned up successfully!");
})->purpose('Remove cancelled and expired bookings');

// 🔹 4. Refresh learning modules
Artisan::command('infanect:refresh-modules', function () {
    $modules = Module::count();
    $this->info("📚 {$modules} modules found. Refresh process simulated (add logic as needed).");
})->purpose('Refresh Infanect learning modules');

// 🔹 5. Send a test notification to first user
Artisan::command('notify:test', function () {
    $user = User::first();
    if ($user) {
        Notification::send($user, new TestNotification("🚀 This is a test notification from Infanect!"));
        $this->info("📩 Test notification sent to {$user->email}");
    } else {
        $this->error("⚠️ No users found in the database.");
    }
})->purpose('Send a test notification to the first user');

// 🔹 6. Report total users
Artisan::command('users:report', function () {
    $total = User::count();
    $verified = User::whereNotNull('email_verified_at')->count();
    $this->info("👥 Total Users: {$total}");
    $this->info("✅ Verified Users: {$verified}");
    $this->info("❌ Unverified Users: " . ($total - $verified));
})->purpose('Generate a quick user report');
