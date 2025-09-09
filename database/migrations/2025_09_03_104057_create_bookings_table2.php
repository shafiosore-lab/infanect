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
        if (!Schema::hasTable('activity_bookings')) {
            Schema::create('activity_bookings', function (Blueprint $table) {
                $table->id();

                // 🔗 Relationships
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('activity_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('provider_id')->nullable()->constrained('service_providers')->nullOnDelete();

                // For multi-tenant support (no foreign key constraint)
                $table->unsignedBigInteger('tenant_id')->nullable();

                // 🌍 Customer Info
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('country_code', 5)->nullable();

                // Booking details
                $table->dateTime('booking_date')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'refunded'])->default('pending');

                // 💰 International currency support
                $table->decimal('price', 12, 2)->nullable();
                $table->string('currency_code', 3)->default('USD');
                $table->string('payment_ref')->nullable();

                // Extra info
                $table->json('metadata')->nullable();

                $table->timestamps();
                $table->softDeletes(); // safer deletes




            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_bookings');
    }
};
