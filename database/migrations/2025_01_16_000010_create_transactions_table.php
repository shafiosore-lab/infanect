<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('booking_id')->constrained()->onDelete('cascade');
                $table->string('reference')->unique();
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
                $table->string('payment_method')->default('mock');
                $table->json('gateway_response')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['user_id', 'status']);
                $table->index(['booking_id', 'status']);
                $table->index('reference');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
