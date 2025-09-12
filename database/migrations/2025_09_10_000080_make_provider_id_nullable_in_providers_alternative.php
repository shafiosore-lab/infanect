<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('providers')) return;

        Schema::table('providers', function (Blueprint $table) {
            if (!Schema::hasColumn('providers', 'provider_id_new')) {
                $table->unsignedBigInteger('provider_id_new')->nullable()->after('id');
            }
        });

        // Copy existing provider_id to provider_id_new if column exists
        if (Schema::hasColumn('providers', 'provider_id')) {
            DB::statement('UPDATE providers SET provider_id_new = provider_id WHERE provider_id IS NOT NULL');

            // Drop old column and rename new column
            Schema::table('providers', function (Blueprint $table) {
                $table->dropColumn('provider_id');
            });

            Schema::table('providers', function (Blueprint $table) {
                $table->unsignedBigInteger('provider_id_new')->nullable()->change();
            });

            // Rename column using raw statement as change might require DBAL
            DB::statement('ALTER TABLE providers CHANGE provider_id_new provider_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        // no-op
    }
};
