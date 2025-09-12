<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) return;

        if (!Schema::hasColumn('bookings', 'service_id')) return;

        try {
            // Try using change() (requires doctrine/dbal)
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('service_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // Fallback to raw SQL for MySQL
            try {
                $driver = DB::getDriverName();
                if ($driver === 'mysql' || $driver === 'mysqli') {
                    DB::statement('ALTER TABLE `bookings` MODIFY `service_id` BIGINT UNSIGNED NULL;');
                } else {
                    // For other drivers we attempt a generic alter - may fail
                    DB::statement('ALTER TABLE bookings ALTER COLUMN service_id DROP NOT NULL');
                }
            } catch (\Throwable $ex) {
                // ignore failures
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('bookings')) return;
        if (!Schema::hasColumn('bookings', 'service_id')) return;

        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('service_id')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            try {
                $driver = DB::getDriverName();
                if ($driver === 'mysql' || $driver === 'mysqli') {
                    DB::statement('ALTER TABLE `bookings` MODIFY `service_id` BIGINT UNSIGNED NOT NULL;');
                } else {
                    // best-effort
                }
            } catch (\Throwable $ex) {
                // ignore
            }
        }
    }
};
