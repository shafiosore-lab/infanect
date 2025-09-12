<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('activity_preferences')) {
            Schema::create('activity_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->json('preferences');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activity_preferences');
    }
};
