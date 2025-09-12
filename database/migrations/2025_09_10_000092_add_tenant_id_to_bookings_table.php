<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings') && ! Schema::hasColumn('bookings', 'tenant_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('activity_id');
                if (Schema::hasTable('tenants')) {
                    try {
                        $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
                    } catch (\Throwable $e) {
                        // ignore FK creation failures
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'tenant_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                try { $table->dropForeign(['tenant_id']); } catch (\Throwable $e) {}
                $table->dropColumn('tenant_id');
            });
        }
    }
};
