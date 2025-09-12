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
        Schema::create('parenting_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // newborn, toddler, preschool, school_age, teenager
            $table->string('difficulty_level'); // beginner, intermediate, advanced
            $table->integer('estimated_duration')->default(30); // minutes
            $table->string('language')->default('en');
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedBigInteger('created_by'); // professional/expert who uploaded
            $table->json('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('completion_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['category', 'is_published']);
            $table->index(['difficulty_level', 'is_premium']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parenting_modules');
    }
};
