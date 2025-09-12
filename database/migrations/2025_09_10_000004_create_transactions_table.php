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
                $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->nullable();
                $table->string('type')->nullable();
                $table->string('status')->default('pending');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
