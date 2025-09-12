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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

Schema::create('modules', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id')->nullable();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('file_path')->nullable();
    $table->longText('ai_text')->nullable();
    $table->string('ai_audio_path')->nullable();
    $table->json('tags')->nullable();
    $table->string('level')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
