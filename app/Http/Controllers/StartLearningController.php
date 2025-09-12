<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartLearningController extends Controller
{
    public function index()
    {
        // Load available lessons from JSON files
        $lessonsPath = database_path('seeders/data');
        $lessons = [];

        if (is_dir($lessonsPath)) {
            $files = glob($lessonsPath . '/lesson_*.json');
            foreach ($files as $file) {
                $json = json_decode(file_get_contents($file), true);
                if (isset($json['lesson']) && is_array($json['lesson'])) {
                    $lessons[] = [
                        'title' => $json['lesson']['title'] ?? basename($file, '.json'),
                        'external_id' => basename($file, '.json'),
                        'description' => $json['lesson']['description'] ?? '',
                    ];
                }
            }
        }

        // If no lessons found, provide default ones
        if (empty($lessons)) {
            $lessons = [
                [
                    'title' => 'Sample Bedtime Lesson',
                    'external_id' => 'lesson_bedtime_01',
                    'description' => 'Learn effective bedtime routines for children'
                ],
                [
                    'title' => 'Sample Tantrum Management',
                    'external_id' => 'lesson_tantrums_01',
                    'description' => 'Learn how to handle children tantrums effectively'
                ],
                [
                    'title' => 'Screen Time Balance',
                    'external_id' => 'lesson_screentime_01',
                    'description' => 'Managing healthy screen time for children'
                ]
            ];
        }

        return view('start-learning.index', compact('lessons'));
    }

    public function play(Request $request)
    {
        // Default lesson data
        $lesson = [
            'title' => 'Sample Bedtime Lesson',
            'description' => 'Learn effective bedtime routines for children',
            'content' => 'This is a sample lesson about establishing healthy bedtime routines.'
        ];

        $audioUrl = null;
        $videoUrl = null;
        $audioDuration = null;

        // Try to load from JSON file
        $path = database_path('seeders/data/lesson_bedtime_01.json');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $json = json_decode($content, true);

            if ($json && isset($json['lesson']) && is_array($json['lesson'])) {
                $lesson = array_merge($lesson, $json['lesson']);
            }

            if ($json && isset($json['media_assets']) && is_array($json['media_assets'])) {
                $media = $json['media_assets'];
                $audio = collect($media)->firstWhere('type', 'audio');
                $video = collect($media)->firstWhere('type', 'video');

                if (is_array($audio) && isset($audio['url'])) {
                    $audioUrl = $audio['url'];
                    $audioDuration = $audio['duration_seconds'] ?? null;
                }

                if (is_array($video) && isset($video['url'])) {
                    $videoUrl = $video['url'];
                }
            }
        }

        return view('start-learning.play', [
            'lesson' => $lesson,
            'audioUrl' => $audioUrl,
            'videoUrl' => $videoUrl,
            'audioDuration' => $audioDuration,
        ]);
    }

    public function playDirect($externalId = null)
    {
        // Default lesson data
        $lesson = [
            'title' => 'Sample Lesson',
            'description' => 'A sample learning lesson',
            'content' => 'This is sample lesson content.'
        ];

        $audioUrl = null;
        $videoUrl = null;
        $audioDuration = null;

        // Determine which file to load
        $filename = $externalId ?: 'lesson_bedtime_01';
        $path = database_path('seeders/data/' . $filename . '.json');

        // Fallback to default if specific file not found
        if (!file_exists($path)) {
            $path = database_path('seeders/data/lesson_bedtime_01.json');
        }

        // Try to load lesson data
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $json = json_decode($content, true);

            if ($json && isset($json['lesson']) && is_array($json['lesson'])) {
                $lesson = array_merge($lesson, $json['lesson']);
            }

            if ($json && isset($json['media_assets']) && is_array($json['media_assets'])) {
                $media = $json['media_assets'];
                $audio = collect($media)->firstWhere('type', 'audio');
                $video = collect($media)->firstWhere('type', 'video');

                if (is_array($audio) && isset($audio['url'])) {
                    $audioUrl = $audio['url'];
                    $audioDuration = $audio['duration_seconds'] ?? null;
                }

                if (is_array($video) && isset($video['url'])) {
                    $videoUrl = $video['url'];
                }
            }
        }

        return view('start-learning.play', [
            'lesson' => $lesson,
            'audioUrl' => $audioUrl,
            'videoUrl' => $videoUrl,
            'audioDuration' => $audioDuration,
        ]);
    }
}
