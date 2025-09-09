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
        Schema::create('service_insights', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->integer('bookings_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_insights');
    }
};
