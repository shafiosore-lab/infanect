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
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('module_id');
            $table->string('recommendation_type'); // 'personalized', 'trending', 'similar_users', 'completion_based'
            $table->decimal('confidence_score', 3, 2)->default(0.00); // AI confidence 0.00-1.00
            $table->text('reasoning')->nullable(); // Why this recommendation was made
            $table->json('user_profile_data')->nullable(); // User's learning profile snapshot
            $table->json('recommendation_metadata')->nullable(); // Additional AI data
            $table->boolean('is_viewed')->default(false);
            $table->boolean('is_clicked')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('parenting_modules')->onDelete('cascade');
            $table->index(['user_id', 'recommendation_type']);
            $table->index(['user_id', 'is_viewed']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};
