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
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('provider_type')->default('provider-bonding');
                $table->enum('kyc_status', ['not_registered', 'pending', 'approved', 'rejected'])->default('not_registered');
                $table->json('specializations')->nullable();
                $table->text('bio')->nullable();
                $table->decimal('hourly_rate', 8, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['user_id', 'provider_type']);
                $table->index('kyc_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
