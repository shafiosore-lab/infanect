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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();

            // Request details
            $table->string('type'); // 'activity', 'service', 'provider_registration', 'employee_registration'
            $table->string('action'); // 'create', 'update', 'delete'
            $table->unsignedBigInteger('requestor_id'); // User who made the request
            $table->unsignedBigInteger('approver_id')->nullable(); // Admin who approved/rejected

            // Related entity
            $table->string('entity_type'); // 'App\Models\Activity', 'App\Models\Service', etc.
            $table->unsignedBigInteger('entity_id');

            // Approval details
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('request_data')->nullable(); // JSON data of the request
            $table->text('approved_data')->nullable(); // JSON data after approval
            $table->text('comments')->nullable(); // Admin comments

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['type', 'status']);
            $table->index(['requestor_id']);
            $table->index(['approver_id']);

            // Foreign keys
            $table->foreign('requestor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
