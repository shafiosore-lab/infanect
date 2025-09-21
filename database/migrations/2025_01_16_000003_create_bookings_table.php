<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('activity_id')->nullable()->constrained()->onDelete('set null');
                $table->string('service_type')->default('bonding');
                $table->datetime('booking_date');
                $table->integer('duration')->default(60); // minutes
                $table->decimal('amount', 8, 2)->default(0.00);
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->json('participants')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['user_id', 'status']);
                $table->index(['provider_id', 'status']);
                $table->index(['activity_id', 'booking_date']);
                $table->index('booking_date');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
