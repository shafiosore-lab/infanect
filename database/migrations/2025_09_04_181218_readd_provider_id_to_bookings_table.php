<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add provider_id column if it does not exist
        if (! Schema::hasColumn('bookings', 'provider_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('provider_id')->nullable()->after('service_id');
            });
        }

        // Add foreign key if it does not exist
        try {
            $dbName = DB::getDatabaseName();
            $constraint = DB::selectOne(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'provider_id' AND REFERENCED_TABLE_NAME = 'users'",
                [$dbName]
            );

            if (! $constraint) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->foreign('provider_id', 'bookings_provider_id_fk')->references('id')->on('users')->onDelete('set null');
                });
            }
        } catch (\Exception $e) {
            // ignore - likely constraint already exists or insufficient privileges
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                try { $table->dropForeign('bookings_provider_id_fk'); } catch (\Exception $e) { }
                try { $table->dropColumn('provider_id'); } catch (\Exception $e) { }
            });
        }
    }
};
