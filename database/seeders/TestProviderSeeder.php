<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\ServiceProvider;

class TestProviderSeeder extends Seeder
{
    public function run()
    {
        // Create test provider user
        $user = User::create([
            'name' => 'Test Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('slug', 'provider')->first()->id ?? 1,
            'is_active' => true
        ]);

        // Create test provider profile
        $provider = ServiceProvider::create([
            'name' => 'Test Provider Services',
            'email' => 'provider@test.com',
            'phone' => '1234567890',
            'specialization' => 'Child Care',
            'country' => 'Kenya',
            'bio' => 'Test provider for testing purposes',
            'user_id' => $user->id,
            'is_approved' => true
        ]);

        $this->command->info('Test provider created successfully!');
        $this->command->info('User ID: ' . $user->id);
        $this->command->info('Provider ID: ' . $provider->id);
        $this->command->info('Email: provider@test.com');
        $this->command->info('Password: password');
    }
}
