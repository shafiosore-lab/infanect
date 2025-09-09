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
        Schema::create('ai_chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('session_id')->unique(); // For grouping conversation messages
            $table->string('message_type'); // 'user' or 'assistant'
            $table->longText('message'); // The actual message content
            $table->json('metadata')->nullable(); // Additional data like sources, confidence, etc.
            $table->boolean('is_audio_generated')->default(false); // Track if audio was generated
            $table->string('audio_url')->nullable(); // URL to generated audio file
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'session_id']);
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_conversations');
    }
};
