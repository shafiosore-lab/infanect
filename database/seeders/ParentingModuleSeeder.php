<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentingModule;
use App\Models\ModuleContent;
use App\Models\User;

class ParentingModuleSeeder extends Seeder
{
    public function run()
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'admin@infanect.com')->first();

        if (!$adminUser) {
            $adminUser = User::first(); // Fallback to first user
        }

        $modules = [
            [
                'title' => 'Understanding Your Baby\'s Development',
                'description' => 'Learn about the key developmental milestones in your baby\'s first year and how to support their growth.',
                'category' => 'Development',
                'difficulty_level' => 'Beginner',
                'estimated_duration' => 45,
                'language' => 'English',
                'is_premium' => false,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['baby', 'development', 'milestones', 'growth'],
                'view_count' => 1250,
                'completion_count' => 890,
                'rating' => 4.8,
                'contents' => [
                    [
                        'title' => 'Introduction to Baby Development',
                        'description' => 'Overview of developmental stages and what to expect',
                        'content_type' => 'text',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 5,
                    ],
                    [
                        'title' => 'Physical Development Milestones',
                        'description' => 'Motor skills and physical growth patterns',
                        'content_type' => 'video',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 15,
                    ],
                    [
                        'title' => 'Cognitive Development',
                        'description' => 'How babies learn and process information',
                        'content_type' => 'text',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                    [
                        'title' => 'Social and Emotional Development',
                        'description' => 'Building relationships and emotional intelligence',
                        'content_type' => 'video',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 12,
                    ],
                    [
                        'title' => 'Supporting Your Baby\'s Development',
                        'description' => 'Practical tips and activities to encourage growth',
                        'content_type' => 'text',
                        'order' => 5,
                        'is_preview' => false,
                        'duration' => 8,
                    ],
                ]
            ],
            [
                'title' => 'Creating a Safe Sleep Environment',
                'description' => 'Essential information about safe sleep practices to protect your baby from SIDS and other risks.',
                'category' => 'Safety',
                'difficulty_level' => 'Beginner',
                'estimated_duration' => 30,
                'language' => 'English',
                'is_premium' => false,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['sleep', 'safety', 'sids', 'infant'],
                'view_count' => 2100,
                'completion_count' => 1650,
                'rating' => 4.9,
                'contents' => [
                    [
                        'title' => 'The Importance of Safe Sleep',
                        'description' => 'Understanding SIDS and sleep-related risks',
                        'content_type' => 'text',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 5,
                    ],
                    [
                        'title' => 'Safe Sleep Guidelines',
                        'description' => 'Back to sleep, separate surfaces, and room sharing',
                        'content_type' => 'video',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 12,
                    ],
                    [
                        'title' => 'Setting Up the Nursery',
                        'description' => 'Choosing safe bedding and sleep environment',
                        'content_type' => 'text',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 8,
                    ],
                    [
                        'title' => 'Common Sleep Myths',
                        'description' => 'Separating fact from fiction about baby sleep',
                        'content_type' => 'video',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                ]
            ],
            [
                'title' => 'Nutrition for Infants and Toddlers',
                'description' => 'Complete guide to feeding your child from birth through toddlerhood, including breastfeeding, formula, and solids.',
                'category' => 'Nutrition',
                'difficulty_level' => 'Intermediate',
                'estimated_duration' => 60,
                'language' => 'English',
                'is_premium' => true,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['nutrition', 'breastfeeding', 'solids', 'feeding'],
                'view_count' => 1850,
                'completion_count' => 1200,
                'rating' => 4.7,
                'contents' => [
                    [
                        'title' => 'Breastfeeding Basics',
                        'description' => 'Getting started with breastfeeding and common challenges',
                        'content_type' => 'video',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 15,
                    ],
                    [
                        'title' => 'Formula Feeding Guide',
                        'description' => 'Choosing and preparing infant formula safely',
                        'content_type' => 'text',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                    [
                        'title' => 'Introduction to Solids',
                        'description' => 'When and how to start solid foods',
                        'content_type' => 'video',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 18,
                    ],
                    [
                        'title' => 'Nutritional Needs by Age',
                        'description' => 'Understanding dietary requirements at different stages',
                        'content_type' => 'text',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 12,
                    ],
                    [
                        'title' => 'Common Feeding Challenges',
                        'description' => 'Dealing with picky eating and allergies',
                        'content_type' => 'video',
                        'order' => 5,
                        'is_preview' => false,
                        'duration' => 14,
                    ],
                ]
            ],
            [
                'title' => 'Positive Discipline Techniques',
                'description' => 'Learn effective, respectful discipline methods that build character and strengthen your relationship with your child.',
                'category' => 'Discipline',
                'difficulty_level' => 'Intermediate',
                'estimated_duration' => 50,
                'language' => 'English',
                'is_premium' => true,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['discipline', 'behavior', 'parenting', 'character'],
                'view_count' => 1680,
                'completion_count' => 1100,
                'rating' => 4.6,
                'contents' => [
                    [
                        'title' => 'Understanding Child Behavior',
                        'description' => 'Why children behave the way they do',
                        'content_type' => 'text',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 8,
                    ],
                    [
                        'title' => 'Setting Clear Expectations',
                        'description' => 'Establishing rules and boundaries effectively',
                        'content_type' => 'video',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 15,
                    ],
                    [
                        'title' => 'Positive Reinforcement',
                        'description' => 'Using praise and rewards to encourage good behavior',
                        'content_type' => 'text',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                    [
                        'title' => 'Natural Consequences',
                        'description' => 'Teaching through life lessons',
                        'content_type' => 'video',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 12,
                    ],
                    [
                        'title' => 'Handling Tantrums',
                        'description' => 'Strategies for managing emotional outbursts',
                        'content_type' => 'text',
                        'order' => 5,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                ]
            ],
            [
                'title' => 'Building Emotional Intelligence in Children',
                'description' => 'Help your child develop emotional awareness, empathy, and self-regulation skills for lifelong success.',
                'category' => 'Emotional Development',
                'difficulty_level' => 'Advanced',
                'estimated_duration' => 75,
                'language' => 'English',
                'is_premium' => true,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['emotions', 'empathy', 'self-regulation', 'intelligence'],
                'view_count' => 1420,
                'completion_count' => 780,
                'rating' => 4.8,
                'contents' => [
                    [
                        'title' => 'What is Emotional Intelligence?',
                        'description' => 'Understanding EQ and its importance',
                        'content_type' => 'video',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 12,
                    ],
                    [
                        'title' => 'Teaching Emotional Awareness',
                        'description' => 'Helping children identify and name their feelings',
                        'content_type' => 'text',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 15,
                    ],
                    [
                        'title' => 'Developing Empathy',
                        'description' => 'Building compassion and understanding of others',
                        'content_type' => 'video',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 18,
                    ],
                    [
                        'title' => 'Self-Regulation Techniques',
                        'description' => 'Tools for managing emotions and impulses',
                        'content_type' => 'text',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 14,
                    ],
                    [
                        'title' => 'Social Skills Development',
                        'description' => 'Building healthy relationships and communication',
                        'content_type' => 'video',
                        'order' => 5,
                        'is_preview' => false,
                        'duration' => 16,
                    ],
                    [
                        'title' => 'Long-term Benefits',
                        'description' => 'How EQ impacts success in life',
                        'content_type' => 'text',
                        'order' => 6,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                ]
            ],
            [
                'title' => 'First Aid and Emergency Preparedness',
                'description' => 'Essential first aid skills and emergency preparedness for parents and caregivers.',
                'category' => 'Safety',
                'difficulty_level' => 'Intermediate',
                'estimated_duration' => 40,
                'language' => 'English',
                'is_premium' => false,
                'is_published' => true,
                'created_by' => $adminUser->id,
                'tags' => ['first aid', 'emergency', 'safety', 'preparedness'],
                'view_count' => 2350,
                'completion_count' => 1890,
                'rating' => 4.9,
                'contents' => [
                    [
                        'title' => 'Basic First Aid Kit',
                        'description' => 'What to include and how to maintain your kit',
                        'content_type' => 'text',
                        'order' => 1,
                        'is_preview' => true,
                        'duration' => 6,
                    ],
                    [
                        'title' => 'CPR for Infants and Children',
                        'description' => 'Life-saving CPR techniques for young children',
                        'content_type' => 'video',
                        'order' => 2,
                        'is_preview' => false,
                        'duration' => 20,
                    ],
                    [
                        'title' => 'Common Childhood Injuries',
                        'description' => 'Treating cuts, burns, and fractures',
                        'content_type' => 'text',
                        'order' => 3,
                        'is_preview' => false,
                        'duration' => 10,
                    ],
                    [
                        'title' => 'Emergency Preparedness',
                        'description' => 'Creating a family emergency plan',
                        'content_type' => 'video',
                        'order' => 4,
                        'is_preview' => false,
                        'duration' => 12,
                    ],
                ]
            ],
        ];

        foreach ($modules as $moduleData) {
            $contents = $moduleData['contents'];
            unset($moduleData['contents']);

            $module = ParentingModule::create($moduleData);

            foreach ($contents as $contentData) {
                $contentData['module_id'] = $module->id;
                ModuleContent::create($contentData);
            }
        }
    }
}
