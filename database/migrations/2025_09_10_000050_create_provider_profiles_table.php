<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('provider_profiles')) {
            Schema::create('provider_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('company_name')->nullable();
                $table->string('slug')->nullable()->unique();
                $table->text('bio')->nullable();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->string('address')->nullable();
                $table->string('avatar')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('provider_profiles')) {
            Schema::dropIfExists('provider_profiles');
        }
    }
};
