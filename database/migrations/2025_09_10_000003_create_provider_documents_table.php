<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('provider_documents')) {
            Schema::create('provider_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
                $table->string('type');
                $table->string('path');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('provider_documents');
    }
};
