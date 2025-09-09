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
        Schema::create('training_modules', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->string('difficulty_level')->default('beginner');
            $table->integer('estimated_duration')->default(30); // in minutes
            $table->string('language')->default('en');

            // Content
            $table->json('document_content')->nullable(); // Store extracted document content
            $table->string('document_path')->nullable(); // Path to uploaded document
            $table->string('document_type')->nullable(); // pdf, docx, etc.

            // AI Chat settings
            $table->boolean('enable_ai_chat')->default(true);
            $table->json('ai_chat_config')->nullable(); // Configuration for AI chat

            // Status and access
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedBigInteger('created_by');

            // Metadata
            $table->json('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('completion_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['is_published', 'category']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_modules');
    }
};
