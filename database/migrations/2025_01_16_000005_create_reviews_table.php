<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
                $table->integer('rating')->default(5);
                $table->text('comment')->nullable();
                $table->boolean('is_anonymous')->default(false);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['provider_id', 'rating']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
