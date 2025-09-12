<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityTemplateSeeder extends Seeder
{
    public function run()
    {
        if (! Schema::hasTable('activity_templates')) {
            return;
        }

        $now = now();

        $samples = [
            ['title' => 'Park Picnic', 'description' => 'Family picnic in the local park', 'tags' => ['outdoor','picnic'], 'age_groups' => ['0-2','3-5']],
            ['title' => 'Story Time', 'description' => 'Interactive storytelling for toddlers', 'tags' => ['indoor','storytelling'], 'age_groups' => ['0-2','3-5']],
            ['title' => 'Art & Crafts', 'description' => 'Simple crafts for preschoolers', 'tags' => ['arts','craft'], 'age_groups' => ['3-5','6-8']],
        ];

        foreach ($samples as $s) {
            $row = ['title' => $s['title'], 'created_at' => $now, 'updated_at' => $now];

            if (Schema::hasColumn('activity_templates', 'description')) {
                $row['description'] = $s['description'];
            }

            // tags column may have constraints; set to json string if column accepts text, or to null
            if (Schema::hasColumn('activity_templates', 'tags')) {
                try {
                    $row['tags'] = is_array($s['tags']) ? implode(',', $s['tags']) : $s['tags'];
                } catch (\Exception $e) {
                    $row['tags'] = null;
                }
            }

            if (Schema::hasColumn('activity_templates', 'age_groups')) {
                try {
                    $row['age_groups'] = is_array($s['age_groups']) ? json_encode($s['age_groups']) : $s['age_groups'];
                } catch (\Exception $e) {
                    $row['age_groups'] = null;
                }
            }

            try {
                DB::table('activity_templates')->updateOrInsert(
                    ['title' => $s['title']],
                    $row
                );
            } catch (\Exception $e) {
                // ignore insert errors to keep seeding resilient
            }
        }
    }
}
