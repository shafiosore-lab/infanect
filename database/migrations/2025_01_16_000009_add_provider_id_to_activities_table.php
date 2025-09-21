<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'provider_id')) {
                $table->foreignId('provider_id')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
                $table->integer('duration_minutes')->default(60)->after('price');
                $table->string('age_group')->default('all')->after('category');
                $table->string('difficulty_level')->default('beginner')->after('age_group');

                $table->index(['provider_id', 'status']);
                $table->index(['category', 'age_group']);
            }
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'provider_id')) {
                $table->dropForeign(['provider_id']);
                $table->dropColumn(['provider_id', 'duration_minutes', 'age_group', 'difficulty_level']);
            }
        });
    }
};
