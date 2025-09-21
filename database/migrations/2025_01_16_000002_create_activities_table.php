<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('type')->default('bonding');
                $table->string('category')->nullable();
                $table->integer('max_participants')->default(10);
                $table->decimal('price', 8, 2)->default(0.00);
                $table->datetime('start_date');
                $table->datetime('end_date')->nullable();
                $table->string('location')->nullable();
                $table->string('status')->default('draft');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->json('requirements')->nullable();
                $table->json('tags')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['created_by', 'status']);
                $table->index(['type', 'category']);
                $table->index('start_date');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
