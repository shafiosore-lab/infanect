<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('filename');
                $table->string('original_name')->nullable();
                $table->string('mime')->nullable();
                $table->bigInteger('size')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('document_chunks')) {
            Schema::create('document_chunks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
                $table->integer('chunk_index')->default(0);
                $table->text('text');
                $table->json('embedding')->nullable();
                $table->boolean('indexed')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document_chunks');
        Schema::dropIfExists('documents');
    }
};
