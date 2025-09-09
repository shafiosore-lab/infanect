<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingModule;
use App\Models\User;

class TrainingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::first(); // Fallback to first user
        }

        if (!$admin) {
            return; // No users found
        }

        // Create WHO Mental Health training module
        $module = TrainingModule::create([
            'title' => 'Investing in Mental Health',
            'description' => 'Comprehensive guide on mental health investment, prevention, and management strategies from the World Health Organization.',
            'category' => 'Mental Health',
            'difficulty_level' => 'intermediate',
            'estimated_duration' => 45,
            'language' => 'en',
            'is_premium' => false,
            'is_published' => true,
            'created_by' => $admin->id,
            'tags' => ['mental health', 'WHO', 'prevention', 'investment', 'global health'],
            'enable_ai_chat' => true,
            'document_content' => $this->getDocumentContent(),
        ]);

        // Create additional sample modules
        TrainingModule::create([
            'title' => 'Understanding Depression',
            'description' => 'Learn about depression symptoms, causes, and evidence-based treatments.',
            'category' => 'Mental Health',
            'difficulty_level' => 'beginner',
            'estimated_duration' => 30,
            'language' => 'en',
            'is_premium' => false,
            'is_published' => true,
            'created_by' => $admin->id,
            'tags' => ['depression', 'mental health', 'treatment'],
            'enable_ai_chat' => true,
        ]);

        TrainingModule::create([
            'title' => 'Workplace Mental Health',
            'description' => 'Strategies for maintaining mental wellness in professional environments.',
            'category' => 'Professional Development',
            'difficulty_level' => 'intermediate',
            'estimated_duration' => 25,
            'language' => 'en',
            'is_premium' => false,
            'is_published' => true,
            'created_by' => $admin->id,
            'tags' => ['workplace', 'stress management', 'professional development'],
            'enable_ai_chat' => true,
        ]);
    }

    private function getDocumentContent()
    {
        return [
            'full_text' => 'This WHO publication provides comprehensive information about mental health investment, prevention strategies, and global mental health challenges.',
            'sections' => [
                [
                    'title' => 'Introduction',
                    'content' => 'Mental health has been hidden behind a curtain of stigma and discrimination for too long. This publication aims to guide understanding of mental health burdens and the potential for mental health gains through proper investment.'
                ],
                [
                    'title' => 'Executive Summary',
                    'content' => 'Mental health is crucial for overall well-being. As many as 450 million people suffer from mental disorders. Four of the six leading causes of disability are due to neuropsychiatric disorders. The economic burden is substantial, affecting personal income, productivity, and national economies.'
                ],
                [
                    'title' => 'What is Mental Health?',
                    'content' => 'Mental health is more than the absence of mental disorders. It includes subjective well-being, self-efficacy, autonomy, competence, and the ability to realize intellectual and emotional potential. Mental health problems affect society as a whole and are a major challenge to global development.'
                ],
                [
                    'title' => 'The Magnitude and Burdens of Mental Disorders',
                    'content' => 'About 450 million people suffer from mental or behavioral disorders. Neuropsychiatric conditions account for 13% of the global burden of disease. Mental disorders affect individuals, families, and communities with enormous human suffering, disability, and economic loss.'
                ],
                [
                    'title' => 'The Economic Burden of Mental Disorders',
                    'content' => 'Mental health problems impose significant economic costs on individuals, families, employers, and society. The cost of mental disorders in developed countries is estimated to be between 3% and 4% of GNP. These costs include treatment expenses, lost productivity, and reduced quality of life.'
                ],
                [
                    'title' => 'Promoting Mental Health and Preventing Mental Ill Health',
                    'content' => 'Prevention and promotion strategies can reduce disability and deaths. Effective interventions include early childhood programs, school-based initiatives, workplace stress management, and community-based prevention programs. Many interventions are cost-effective and evidence-based.'
                ],
                [
                    'title' => 'The Gap Between Burden and Resources',
                    'content' => 'There is a significant gap between the need for mental health treatment and available resources. In developed countries, 35-50% of people with mental disorders do not receive treatment. In developing countries, the treatment gap can be as high as 90%.'
                ],
                [
                    'title' => 'WHO Global Action Programme (mhGAP)',
                    'content' => 'WHO has launched the Mental Health Global Action Programme to close the treatment gap. The program focuses on increasing country capacity, raising awareness, improving policies, and building local research capacity for mental health interventions.'
                ],
                [
                    'title' => 'Much Can Be Done - Everyone Can Contribute',
                    'content' => 'Effective mental health interventions are available and can be implemented immediately. Priorities include prevention of childhood mental problems, suicide prevention, depression management, and human rights protection. Everyone from individuals to governments can contribute to better mental health.'
                ]
            ],
            'key_topics' => [
                'Mental health investment strategies',
                'Prevention and promotion programs',
                'Economic burden analysis',
                'Global mental health challenges',
                'Treatment gap reduction',
                'Policy development',
                'Community interventions',
                'Workplace mental health',
                'Child and adolescent mental health',
                'Suicide prevention',
                'Depression management',
                'Human rights in mental health'
            ],
            'statistics' => [
                '450 million people affected by mental disorders',
                '13% of global disease burden from neuropsychiatric disorders',
                '3-4% of GNP spent on mental health in developed countries',
                '90% treatment gap in developing countries',
                '1 million suicides annually',
                '25 million people with schizophrenia',
                '150 million with depression'
            ]
        ];
    }
}
