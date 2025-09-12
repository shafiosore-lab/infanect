<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_profile_id')->nullable()->constrained('provider_profiles')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->json('slots')->nullable();
                $table->json('images')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->decimal('price', 10, 2)->default(0);
                $table->string('currency', 10)->default('KES');
                $table->integer('duration_minutes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('activities')) {
            Schema::dropIfExists('activities');
        }
    }
};
