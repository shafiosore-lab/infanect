<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('engagements')) {
            Schema::create('engagements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('activity_id')->nullable()->constrained()->onDelete('set null');
                $table->string('engagement_type')->default('activity_participation');
                $table->integer('engagement_score')->default(5);
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('engaged_at')->useCurrent();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['user_id', 'engagement_type']);
                $table->index(['provider_id', 'engaged_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('engagements');
    }
};
