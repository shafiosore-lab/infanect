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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->comment('Client first name');
            $table->string('last_name')->nullable()->comment('Client last name');
            $table->string('email')->unique()->comment('Unique client email');
            $table->string('phone')->nullable()->comment('Phone number with international format');
            $table->string('country')->nullable()->comment('ISO country code or name');
            $table->string('city')->nullable()->comment('City or locality');
            $table->json('metadata')->nullable()->comment('Flexible storage for preferences, settings, etc.');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
