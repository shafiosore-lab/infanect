<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $users = User::all();

        if ($admin) {
            Task::create([
                'title' => 'Review and approve new service providers',
                'description' => 'Go through the pending service provider applications and approve qualified candidates.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Task::create([
                'title' => 'Update system documentation',
                'description' => 'Update the user manual and API documentation with the latest features.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => now()->addDays(14),
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Task::create([
                'title' => 'Implement user feedback system',
                'description' => 'Create a system for collecting and managing user feedback and suggestions.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(21),
                'assigned_to' => $users->random()->id ?? $admin->id,
                'created_by' => $admin->id,
            ]);

            Task::create([
                'title' => 'Optimize database queries',
                'description' => 'Review and optimize slow database queries to improve application performance.',
                'status' => 'completed',
                'priority' => 'high',
                'due_date' => now()->subDays(5),
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Task::create([
                'title' => 'Set up automated backups',
                'description' => 'Configure automated daily backups for the database and file storage.',
                'status' => 'pending',
                'priority' => 'urgent',
                'due_date' => now()->addDays(3),
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Task::create([
                'title' => 'Conduct user training session',
                'description' => 'Organize and conduct training sessions for new users on the platform.',
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => now()->addDays(30),
                'assigned_to' => $users->random()->id ?? $admin->id,
                'created_by' => $admin->id,
            ]);
        }
    }
}
