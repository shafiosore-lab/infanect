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
        Schema::create('user_module_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('content_id')->nullable(); // current content being viewed
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->integer('time_spent')->default(0); // in minutes
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('progress_data')->nullable(); // detailed progress tracking
            $table->boolean('is_favorited')->default(false);
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('parenting_modules')->onDelete('cascade');
            $table->foreign('content_id')->references('id')->on('module_contents')->onDelete('set null');
            $table->unique(['user_id', 'module_id']);
            $table->index(['user_id', 'status']);
            $table->index(['module_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_module_progress');
    }
};
