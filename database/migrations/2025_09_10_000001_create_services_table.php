<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->string('currency',3)->nullable();
                $table->string('delivery_type')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
