<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        // Ensure duration_minutes exists (nullable) so availability logic relying on it is safe
        if (!Schema::hasColumn('services', 'duration_minutes')) {
            Schema::table('services', function (Blueprint $table) {
                $table->integer('duration_minutes')->nullable();
            });
        }

        // Add availability JSON column if it doesn't exist
        if (!Schema::hasColumn('services', 'availability')) {
            Schema::table('services', function (Blueprint $table) {
                $table->json('availability')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'availability')) {
                $table->dropColumn('availability');
            }

            // Note: don't drop duration_minutes in down to avoid data loss unless you created it here intentionally
        });
    }
};
