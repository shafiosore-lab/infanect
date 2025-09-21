<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('activities', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('activities', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
