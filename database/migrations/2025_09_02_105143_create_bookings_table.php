<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Client who booked
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Booked service
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');

            // Financials
            $table->decimal('amount', 10, 2)->default(0);        // Total service cost
            $table->decimal('amount_paid', 10, 2)->default(0);   // Amount actually paid
            $table->decimal('discount', 10, 2)->default(0);      // Discount applied
            $table->string('currency', 3)->default('USD');       // Currency code (USD, EUR, etc.)
            $table->string('payment_method')->nullable();        // e.g., card, mpesa, paypal
            $table->string('payment_reference')->nullable();     // Optional payment transaction reference

            // Booking status & metadata
            $table->string('status')->default('pending');        // pending, confirmed, cancelled, refunded
            $table->boolean('is_returning')->default(false);     // Returning client flag
            $table->string('location')->nullable();              // Service location
            $table->timestamp('scheduled_at')->nullable();       // Scheduled service date/time
            $table->string('service_type')->nullable();          // e.g., Bonding, Professional, International
            $table->boolean('is_international')->default(false); // Flag for international bookings


            // Timestamps & soft delete
            $table->timestamps();
            $table->softDeletes();                               // Enable soft deletes for safety
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }



};


