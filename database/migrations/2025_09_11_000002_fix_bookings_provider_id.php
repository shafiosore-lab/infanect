<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add provider_id column if missing
        if (! Schema::hasColumn('bookings', 'provider_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('provider_id')->nullable()->after('service_id');
            });
        }

        // Ensure foreign key exists; attempt to add if missing
        try {
            // Check information_schema for constraint
            $dbName = DB::getDatabaseName();
            $constraint = DB::selectOne(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'provider_id' AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$dbName]
            );

            if (! $constraint) {
                Schema::table('bookings', function (Blueprint $table) {
                    // use explicit foreign key name to avoid collisions
                    $table->foreign('provider_id', 'bookings_provider_id_fk')->references('id')->on('users')->onDelete('set null');
                });
            }
        } catch (\Exception $e) {
            // Ignore errors - most likely foreign key already exists or permissions issue
        }
    }

    public function down()
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                try {
                    $table->dropForeign('bookings_provider_id_fk');
                } catch (\Exception $e) { }

                try {
                    $table->dropColumn('provider_id');
                } catch (\Exception $e) { }
            });
        }
    }
};
