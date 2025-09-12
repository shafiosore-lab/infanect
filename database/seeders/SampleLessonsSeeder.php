<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SampleLessonsSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/lesson_bedtime_01.json');
        if (! file_exists($path)) {
            $this->command->info('Sample lesson JSON not found: '.$path);
            return;
        }

        $json = json_decode(file_get_contents($path), true);
        if (! $json) {
            $this->command->error('Invalid JSON in '.$path);
            return;
        }

        $lesson = $json['lesson'] ?? null;
        $media = $json['media_assets'] ?? [];

        if (! $lesson) {
            $this->command->error('No lesson object in JSON');
            return;
        }

        if (! Schema::hasTable('lessons')) {
            $this->command->warn('lessons table does not exist, skipping SampleLessonsSeeder');
            return;
        }

        // Insert or update lesson by external_id if present
        $externalId = $lesson['external_id'] ?? null;
        if (! $externalId) {
            $this->command->error('Lesson missing external_id');
            return;
        }

        try {
            $exists = DB::table('lessons')->where('external_id', $externalId)->first();

            $payload = [
                'external_id' => $externalId,
                'title' => $lesson['title'] ?? 'Untitled',
                'summary' => $lesson['summary'] ?? null,
                'target_age_min' => $lesson['target_age_min'] ?? null,
                'target_age_max' => $lesson['target_age_max'] ?? null,
                'tags' => isset($lesson['tags']) ? json_encode($lesson['tags']) : null,
                'source_pdf_id' => $lesson['source_pdf_id'] ?? null,
                'created_at' => $lesson['created_at'] ?? now(),
                'updated_at' => $lesson['updated_at'] ?? now(),
            ];

            if ($exists) {
                DB::table('lessons')->where('id', $exists->id)->update($payload);
                $lessonId = $exists->id;
            } else {
                $lessonId = DB::table('lessons')->insertGetId($payload);
            }

            // Media assets
            if (Schema::hasTable('media_assets') && is_array($media)) {
                foreach ($media as $m) {
                    try {
                        $match = DB::table('media_assets')
                            ->where('lesson_id', $lessonId)
                            ->where('type', $m['type'])
                            ->first();

                        $mp = [
                            'lesson_id' => $lessonId,
                            'type' => $m['type'] ?? 'audio',
                            'url' => $m['url'] ?? null,
                            'duration_seconds' => $m['duration_seconds'] ?? 0,
                            'language' => $m['language'] ?? null,
                            'generated_at' => $m['generated_at'] ?? now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if ($match) {
                            DB::table('media_assets')->where('id', $match->id)->update($mp);
                        } else {
                            DB::table('media_assets')->insert($mp);
                        }
                    } catch (\Throwable $e) {
                        // ignore per-media errors
                    }
                }
            }

            $this->command->info('Seeded lesson: '.$externalId);
        } catch (\Throwable $e) {
            $this->command->error('Failed to seed lesson: '.$e->getMessage());
        }
    }
}
