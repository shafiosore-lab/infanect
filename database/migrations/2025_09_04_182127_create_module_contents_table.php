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
        Schema::create('module_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('content_type', ['pdf', 'video', 'audio', 'text']); // PDF, Video, Audio, Text
            $table->string('file_path')->nullable(); // For uploaded files
            $table->string('file_url')->nullable(); // For external URLs
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->integer('duration')->nullable(); // for video/audio in seconds
            $table->integer('order')->default(0); // for sequencing content
            $table->boolean('is_preview')->default(false); // free preview content
            $table->json('metadata')->nullable(); // additional data like transcription, subtitles
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('parenting_modules')->onDelete('cascade');
            $table->index(['module_id', 'order']);
            $table->index(['content_type', 'is_preview']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_contents');
    }
};
