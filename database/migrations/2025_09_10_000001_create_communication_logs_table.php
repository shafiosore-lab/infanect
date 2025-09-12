<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('channel', ['email','sms','push']);
            $table->string('type');
            $table->text('message');
            $table->enum('status', ['queued','sent','failed'])->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('communication_logs');
    }
};
