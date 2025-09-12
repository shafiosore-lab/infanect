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
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'slug')) {
                $table->string('slug')->unique()->after('id'); // unique code, e.g. "parenting-101"
            }
            if (!Schema::hasColumn('modules', 'title')) {
                $table->string('title')->after('slug');
            }
            if (!Schema::hasColumn('modules', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('modules', 'language')) {
                $table->string('language', 10)->default('en')->after('description'); // future: i18n
            }
            if (!Schema::hasColumn('modules', 'region')) {
                $table->string('region')->nullable()->after('language');          // e.g., "EU", "AFRICA"
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['slug', 'title', 'description', 'language', 'region']);
        });
    }
};
