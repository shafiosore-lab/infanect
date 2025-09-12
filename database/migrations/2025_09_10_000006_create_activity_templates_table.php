<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('age_groups')->nullable();
            $table->string('indoor_outdoor')->nullable();
            $table->string('energy_level')->nullable();
            $table->integer('duration')->nullable();
            $table->string('cost_tier')->nullable();
            $table->json('accessibility_tags')->nullable();
            $table->json('tags')->nullable();
            $table->string('locale', 10)->default('en');
            $table->string('created_by')->default('system');
            $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_templates');
    }
};
