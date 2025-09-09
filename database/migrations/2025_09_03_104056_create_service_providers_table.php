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
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('specialization')->nullable();
            $table->json('availability')->nullable(); // JSON schedule/slots
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedBigInteger('tenant_id')->nullable(); // For multi-tenant SaaS
            $table->unsignedBigInteger('user_id')->nullable(); // Optional link to User (account owner)
            $table->json('metadata')->nullable(); // Flexible JSON storage
            $table->string('country')->nullable(); // International support
            $table->string('language')->default('en'); // Default language for communication
            $table->string('currency', 3)->default('USD'); // Currency for service pricing
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};
