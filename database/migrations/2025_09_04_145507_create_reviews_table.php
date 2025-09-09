<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');

            // 🌍 Reviewer Info
            $table->string('reviewer_name');
            $table->string('country_code', 5)->nullable();

            // Feedback
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment')->nullable();

            // AI moderation or translations (future use)
            $table->boolean('is_approved')->default(true);
            $table->json('translations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
