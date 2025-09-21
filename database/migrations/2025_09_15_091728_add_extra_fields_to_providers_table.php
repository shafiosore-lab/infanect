<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            if (!Schema::hasColumn('providers', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('providers', 'location')) $table->string('location')->nullable();
            if (!Schema::hasColumn('providers', 'rating')) $table->decimal('rating', 3, 2)->default(0);
            if (!Schema::hasColumn('providers', 'services')) $table->json('services')->nullable();
            if (!Schema::hasColumn('providers', 'bio')) $table->text('bio')->nullable();
            if (!Schema::hasColumn('providers', 'price')) $table->decimal('price', 8, 2)->nullable();
            if (!Schema::hasColumn('providers', 'image_url')) $table->string('image_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            if (Schema::hasColumn('providers', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('providers', 'location')) $table->dropColumn('location');
            if (Schema::hasColumn('providers', 'rating')) $table->dropColumn('rating');
            if (Schema::hasColumn('providers', 'services')) $table->dropColumn('services');
            if (Schema::hasColumn('providers', 'bio')) $table->dropColumn('bio');
            if (Schema::hasColumn('providers', 'price')) $table->dropColumn('price');
            if (Schema::hasColumn('providers', 'image_url')) $table->dropColumn('image_url');
        });
    }
};
