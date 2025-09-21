<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class MoodSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Check if mood_submissions table exists
        if (!Schema::hasTable('mood_submissions')) {
            // Create the table if it doesn't exist
            Schema::create('mood_submissions', function ($table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('mood');
                $table->integer('mood_score')->default(5);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        $clients = User::where('role', 'client')->get();

        if ($clients->isEmpty()) return;

        $moods = [
            ['mood' => 'happy', 'score_range' => [7, 10]],
            ['mood' => 'calm', 'score_range' => [6, 8]],
            ['mood' => 'stressed', 'score_range' => [2, 5]],
            ['mood' => 'anxious', 'score_range' => [1, 4]],
            ['mood' => 'excited', 'score_range' => [8, 10]],
            ['mood' => 'sad', 'score_range' => [1, 3]],
            ['mood' => 'content', 'score_range' => [6, 8]],
            ['mood' => 'frustrated', 'score_range' => [2, 4]],
        ];

        // Create mood submissions for the last 30 days
        foreach ($clients->take(5) as $client) {
            for ($day = 0; $day < 30; $day++) {
                if (rand(1, 3) === 1) { // 33% chance of submission per day
                    $moodData = collect($moods)->random();

                    DB::table('mood_submissions')->insert([
                        'user_id' => $client->id,
                        'mood' => $moodData['mood'],
                        'mood_score' => rand($moodData['score_range'][0], $moodData['score_range'][1]),
                        'notes' => $this->getMoodNote($moodData['mood']),
                        'created_at' => now()->subDays($day)->setTime(rand(8, 20), rand(0, 59)),
                        'updated_at' => now()->subDays($day)->setTime(rand(8, 20), rand(0, 59)),
                    ]);
                }
            }
        }
    }

    private function getMoodNote($mood): ?string
    {
        $notes = [
            'happy' => 'Had a great day with family!',
            'calm' => 'Feeling peaceful after meditation.',
            'stressed' => 'Work has been overwhelming lately.',
            'anxious' => 'Worried about upcoming events.',
            'excited' => 'Looking forward to weekend activities!',
            'sad' => 'Missing family members.',
            'content' => 'Feeling satisfied with life.',
            'frustrated' => 'Dealing with daily challenges.',
        ];

        return $notes[$mood] ?? null;
    }
}
