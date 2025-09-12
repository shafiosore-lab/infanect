<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('providers') && Schema::hasColumn('providers', 'name')) {
            try {
                DB::statement("ALTER TABLE `providers` MODIFY `name` VARCHAR(191) NULL");
            } catch (\Exception $e) {
                // Fallback: ignore if DB driver doesn't support MODIFY via statement
                logger()->warning('Could not alter providers.name to nullable: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('providers') && Schema::hasColumn('providers', 'name')) {
            try {
                DB::statement("ALTER TABLE `providers` MODIFY `name` VARCHAR(191) NOT NULL");
            } catch (\Exception $e) {
                logger()->warning('Could not revert providers.name nullable change: ' . $e->getMessage());
            }
        }
    }
};
