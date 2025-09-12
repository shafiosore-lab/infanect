<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('providers') && Schema::hasColumn('providers', 'provider_id')) {
            try {
                DB::statement("ALTER TABLE `providers` MODIFY `provider_id` BIGINT UNSIGNED NULL");
            } catch (\Throwable $e) {
                // If ALTER fails (missing DBAL), attempt a no-op fallback
                // The user may need to install doctrine/dbal to support column modifications.
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('providers') && Schema::hasColumn('providers', 'provider_id')) {
            try {
                DB::statement("ALTER TABLE `providers` MODIFY `provider_id` BIGINT UNSIGNED NOT NULL");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
