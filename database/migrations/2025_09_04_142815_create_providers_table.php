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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name'); // Provider/business name
            $table->string('service_type')->index(); // e.g. bonding activity, therapy, sports
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();

            // 🌍 Internationalization
            $table->string('country_code', 5)->nullable();    // ISO 3166-1 alpha-2 (e.g. KE, US, IN)
            $table->string('currency_code', 3)->default('USD'); // ISO 4217
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();

            // Location-based services
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Branding
            $table->string('logo')->nullable();

            // Status & Insights
            $table->boolean('is_available')->default(true);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->unsignedBigInteger('total_reviews')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0.00);

            $table->timestamps();
            $table->softDeletes(); // safer deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
