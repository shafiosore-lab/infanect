<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasColumn('users', 'provider_status')) {
            Schema::table('users', function (Blueprint $table) {
                // Do not rely on 'role' column existing
                $table->string('provider_status')->nullable()->comment('pending|approved|rejected');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'provider_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('provider_status');
            });
        }
    }
};
