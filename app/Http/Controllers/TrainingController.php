<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodSubmission;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's recent mood data for personalized recommendations
        $recentMood = null;

        // If mood_id is provided from sidebar submission, use that specific mood
        if ($request->has('mood_id')) {
            $recentMood = MoodSubmission::where('id', $request->mood_id)
                ->where('user_id', $user->id)
                ->first();
        } else {
            // Otherwise get the most recent mood submission
            $recentMood = MoodSubmission::where('user_id', $user->id)
                ->latest()
                ->first();
        }

        // Get focus area from URL parameter for direct navigation
        $focusArea = $request->get('focus', null);

        // Get personalized training categories based on mood and focus
        $trainingCategories = $this->getTrainingCategories($recentMood, $focusArea);

        $data = [
            'userLevel' => $this->getUserLevel($user),
            'totalPoints' => $this->getUserPoints($user),
            'overallProgress' => $this->getOverallProgress($user),
            'completedModules' => $this->getCompletedModulesCount($user),
            'totalModules' => 12,
            'currentStreak' => $this->getCurrentStreak($user),
            'trainingCategories' => $trainingCategories,
            'recentAchievements' => $this->getRecentAchievements($user),
            'recentMood' => $recentMood,
            'focusArea' => $focusArea,
            'moodBasedMessage' => $this->getMoodBasedMessage($recentMood)
        ];

        return view('training.index', $data);
    }

    public function module($moduleId)
    {
        return view('training.module', compact('moduleId'));
    }

    private function getTrainingCategories($recentMood = null, $focusArea = null)
    {
        $categories = [
            [
                'id' => 'emotional-intelligence',
                'name' => 'Emotional Intelligence',
                'icon' => '🧠',
                'description' => 'Learn to understand and manage emotions effectively',
                'progress' => 75,
                'priority' => $recentMood && $recentMood->mood_score < 5 ? 'high' : 'normal',
                'modules' => [
                    [
                        'id' => 'ei-1',
                        'title' => 'Understanding Your Child\'s Emotions',
                        'duration' => '15 min',
                        'points' => 100,
                        'completed' => true,
                        'locked' => false,
                        'recommended' => $recentMood && $recentMood->mood_score < 5
                    ],
                    [
                        'id' => 'ei-2',
                        'title' => 'Managing Tantrums with Empathy',
                        'duration' => '20 min',
                        'points' => 150,
                        'completed' => false,
                        'locked' => false,
                        'recommended' => $recentMood && $recentMood->mood_score < 7
                    ]
                ]
            ],
            [
                'id' => 'stress-management',
                'name' => 'Stress Management',
                'icon' => '🧘',
                'description' => 'Tools for managing parental stress and self-care',
                'progress' => 10,
                'priority' => ($recentMood && $recentMood->mood_score < 5) || $focusArea === 'stress' ? 'urgent' : 'normal',
                'modules' => [
                    [
                        'id' => 'stress-1',
                        'title' => 'Mindful Parenting Basics',
                        'duration' => '14 min',
                        'points' => 90,
                        'completed' => false,
                        'locked' => false,
                        'recommended' => $recentMood && $recentMood->mood_score < 5
                    ],
                    [
                        'id' => 'stress-2',
                        'title' => '5-Minute Stress Relief Techniques',
                        'duration' => '10 min',
                        'points' => 80,
                        'completed' => false,
                        'locked' => false,
                        'recommended' => true
                    ]
                ]
            ],
            [
                'id' => 'positive-discipline',
                'name' => 'Positive Discipline',
                'icon' => '⚖️',
                'description' => 'Evidence-based discipline strategies that work',
                'progress' => 30,
                'priority' => 'normal',
                'modules' => [
                    [
                        'id' => 'pd-1',
                        'title' => 'Setting Clear Boundaries',
                        'duration' => '12 min',
                        'points' => 80,
                        'completed' => false,
                        'locked' => false
                    ]
                ]
            ],
            [
                'id' => 'communication',
                'name' => 'Communication Skills',
                'icon' => '💬',
                'description' => 'Build stronger connections through better communication',
                'progress' => 60,
                'priority' => 'normal',
                'modules' => [
                    [
                        'id' => 'comm-1',
                        'title' => 'Active Listening Techniques',
                        'duration' => '16 min',
                        'points' => 110,
                        'completed' => true,
                        'locked' => false
                    ]
                ]
            ]
        ];

        // Sort categories based on priority (urgent first, then high, then normal)
        usort($categories, function($a, $b) {
            $priorityOrder = ['urgent' => 0, 'high' => 1, 'normal' => 2];
            return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
        });

        return $categories;
    }

    private function getUserLevel($user)
    {
        // Calculate based on total points earned
        $points = $this->getUserPoints($user);
        return intval($points / 1000) + 1;
    }

    private function getUserPoints($user)
    {
        // Mock calculation - replace with actual user progress tracking
        return 2450;
    }

    private function getOverallProgress($user)
    {
        // Mock calculation
        return 65;
    }

    private function getCompletedModulesCount($user)
    {
        // Mock data
        return 8;
    }

    private function getCurrentStreak($user)
    {
        // Mock data
        return 5;
    }

    private function getRecentAchievements($user)
    {
        return [
            [
                'icon' => '🎯',
                'name' => 'First Steps',
                'description' => 'Completed first module',
                'points' => 100
            ],
            [
                'icon' => '🔥',
                'name' => 'On Fire',
                'description' => '5 day learning streak',
                'points' => 250
            ],
            [
                'icon' => '📚',
                'name' => 'Knowledge Seeker',
                'description' => 'Completed 5 modules',
                'points' => 500
            ]
        ];
    }

    private function getMoodBasedMessage($recentMood)
    {
        if (!$recentMood) {
            return "Welcome to your learning journey! Take a mood check-in to get personalized recommendations.";
        }

        $score = $recentMood->mood_score;

        if ($score <= 3) {
            return "We understand parenting can be challenging. Here are some resources specifically chosen to help you feel more supported and confident.";
        } elseif ($score <= 5) {
            return "Let's work together to boost your parenting confidence with these targeted learning modules.";
        } elseif ($score <= 7) {
            return "You're doing great! Here are some modules to help you continue growing as a parent.";
        } else {
            return "Fantastic mood! You're in a great headspace for learning. Check out these advanced techniques to enhance your parenting skills.";
        }
    }
}
