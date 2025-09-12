<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('provider_profiles')) {
            Schema::table('provider_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('provider_profiles', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('provider_profiles')) {
            Schema::table('provider_profiles', function (Blueprint $table) {
                if (Schema::hasColumn('provider_profiles', 'deleted_at')) {
                    $table->dropColumn('deleted_at');
                }
            });
        }
    }
};
