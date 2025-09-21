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
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category')->default('general');
                $table->decimal('base_price', 8, 2)->default(0.00);
                $table->integer('duration')->default(60); // minutes
                $table->boolean('is_active')->default(true);
                $table->json('requirements')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['category', 'is_active']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
