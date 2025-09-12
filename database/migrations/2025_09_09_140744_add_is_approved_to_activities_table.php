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
        // Add columns if missing
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                if (! Schema::hasColumn('activities', 'is_approved')) {
                    // Do not rely on other columns - add at the end
                    $table->boolean('is_approved')->default(false);
                }
                if (! Schema::hasColumn('activities', 'provider_profile_id')) {
                    $table->unsignedBigInteger('provider_profile_id')->nullable();
                }
            });

            // Add foreign key only if provider_profiles table exists
            try {
                if (Schema::hasTable('provider_profiles')) {
                    $dbName = DB::getDatabaseName();
                    $constraint = DB::selectOne(
                        "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'activities' AND COLUMN_NAME = 'provider_profile_id' AND REFERENCED_TABLE_NAME = 'provider_profiles'",
                        [$dbName]
                    );

                    if (! $constraint) {
                        Schema::table('activities', function (Blueprint $table) {
                            $table->foreign('provider_profile_id', 'activities_provider_profile_id_fk')
                                ->references('id')->on('provider_profiles')->onDelete('set null');
                        });
                    }
                }
            } catch (\Exception $e) {
                // Ignore FK creation errors - may be due to missing table/privileges or schema mismatch
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                try { $table->dropForeign('activities_provider_profile_id_fk'); } catch (\Exception $e) { }
                try { $table->dropColumn('provider_profile_id'); } catch (\Exception $e) { }
                try { $table->dropColumn('is_approved'); } catch (\Exception $e) { }
            });
        }
    }
};
