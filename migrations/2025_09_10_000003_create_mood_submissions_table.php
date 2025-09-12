<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mood_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('mood');
            $table->integer('mood_score')->nullable();
            $table->json('availability')->nullable();
            $table->json('location')->nullable();
            $table->string('timezone')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mood_submissions');
    }
};
