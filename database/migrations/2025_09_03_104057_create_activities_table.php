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
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();

                $table->foreignId('provider_id')
                    ->nullable()
                    ->constrained('service_providers')
                    ->nullOnDelete();

                $table->unsignedBigInteger('tenant_id')->nullable();

                $table->string('title');
                $table->string('category')->index();
                $table->string('country')->nullable();
                $table->string('region')->nullable();
                $table->string('venue')->nullable();
                $table->dateTime('datetime')->nullable();
                $table->decimal('price', 9, 2)->nullable();
                $table->integer('slots')->default(0);
                $table->string('booking_link')->nullable();
                $table->json('meta')->nullable();

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
