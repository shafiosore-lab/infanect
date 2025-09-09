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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // e.g., Bonding Activities, Professional Service Providers
            $table->text('description')->nullable();
            $table->string('code')->nullable()->unique(); // optional unique code
            $table->string('icon')->nullable(); // optional icon
            $table->string('country')->nullable(); // optional country
            $table->boolean('is_active')->default(true);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
