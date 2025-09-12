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
                if (!Schema::hasColumn('providers', 'business_name')) {
                    $table->string('business_name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('providers', 'category')) {
                    $table->string('category')->nullable()->after('business_name');
                }
                if (!Schema::hasColumn('providers', 'country')) {
                    $table->string('country')->nullable()->after('category');
                }
                if (!Schema::hasColumn('providers', 'city')) {
                    $table->string('city')->nullable()->after('country');
                }
                if (!Schema::hasColumn('providers', 'timezone')) {
                    $table->string('timezone')->nullable()->after('city');
                }
                if (!Schema::hasColumn('providers', 'language')) {
                    $table->string('language')->nullable()->after('timezone');
                }
                if (!Schema::hasColumn('providers', 'status')) {
                    $table->string('status')->default('pending')->after('language');
                }
                if (!Schema::hasColumn('providers', 'business_license_path')) {
                    $table->string('business_license_path')->nullable()->after('status');
                }
                if (!Schema::hasColumn('providers', 'id_document_path')) {
                    $table->string('id_document_path')->nullable()->after('business_license_path');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('providers')) {
            Schema::table('providers', function (Blueprint $table) {
                $cols = [
                    'business_name','category','country','city','timezone','language','status','business_license_path','id_document_path'
                ];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('providers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
