<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('providers')) {
            Schema::create('providers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('business_name')->nullable();
                $table->string('category')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('timezone')->nullable();
                $table->string('language')->nullable();
                $table->string('status')->default('pending');
                $table->string('business_license_path')->nullable();
                $table->string('id_document_path')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
