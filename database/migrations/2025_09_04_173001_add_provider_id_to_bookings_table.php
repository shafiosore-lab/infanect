<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'provider_id')) {
                $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely remove provider_id foreign key and column if they exist
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'provider_id')) {
                    try {
                        // Try dropping by column-based foreign key
                        $table->dropForeign(['provider_id']);
                    } catch (\Exception $e) {
                        // If named constraint differs or doesn't exist, ignore
                    }

                    try {
                        $table->dropColumn('provider_id');
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            });
        }
    }
};
