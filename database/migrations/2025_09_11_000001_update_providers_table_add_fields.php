<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('providers')) {
            Schema::table('providers', function (Blueprint $table) {
                if (!Schema::hasColumn('providers', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('providers', 'state')) {
                    $table->string('state')->nullable();
                }
                if (!Schema::hasColumn('providers', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('providers', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('providers', 'address')) {
                    $table->string('address')->nullable();
                }
                if (!Schema::hasColumn('providers', 'postal_code')) {
                    $table->string('postal_code')->nullable();
                }
                if (!Schema::hasColumn('providers', 'latitude')) {
                    $table->double('latitude')->nullable();
                }
                if (!Schema::hasColumn('providers', 'longitude')) {
                    $table->double('longitude')->nullable();
                }
                if (!Schema::hasColumn('providers', 'logo')) {
                    $table->string('logo')->nullable();
                }
                if (!Schema::hasColumn('providers', 'is_available')) {
                    $table->boolean('is_available')->default(true);
                }
                if (!Schema::hasColumn('providers', 'avg_rating')) {
                    $table->decimal('avg_rating', 3, 2)->default(0)->nullable();
                }
                if (!Schema::hasColumn('providers', 'total_reviews')) {
                    $table->integer('total_reviews')->default(0);
                }
                if (!Schema::hasColumn('providers', 'total_revenue')) {
                    $table->decimal('total_revenue', 12, 2)->default(0);
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('providers')) {
            Schema::table('providers', function (Blueprint $table) {
                // Do not drop user_id or important columns on rollback to avoid data loss in production
                $columns = ['state','email','phone','address','postal_code','latitude','longitude','logo','is_available','avg_rating','total_reviews','total_revenue'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('providers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
