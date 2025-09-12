<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings') && ! Schema::hasColumn('bookings', 'provider_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('provider_id')->nullable()->after('user_id');
                if (Schema::hasTable('providers')) {
                    $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'provider_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'provider_id')) {
                    // drop foreign key if exists
                    try { $table->dropForeign(['provider_id']); } catch (\Throwable $e) {}
                    $table->dropColumn('provider_id');
                }
            });
        }
    }
};
