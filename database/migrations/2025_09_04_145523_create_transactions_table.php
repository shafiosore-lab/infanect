<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');

            // Transaction details
            $table->string('transaction_type'); // booking_payment, refund, payout, commission
            $table->decimal('amount', 15, 2);
            $table->string('currency_code', 3)->default('USD');
            $table->string('payment_method')->nullable(); // card, mpesa, paypal, etc.
            $table->string('transaction_reference')->nullable();

            // Status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
