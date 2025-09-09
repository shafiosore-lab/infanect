<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;
use App\Models\Category;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        // Get a provider user, or create one
        $provider = User::firstOrCreate(
            ['email'=>'provider@infanect.com'],
            ['name'=>'Sample Provider', 'password'=>bcrypt('password123'), 'role_id'=>4, 'is_active'=>true]
        );

        $categories = Category::all();

        foreach ($categories as $category) {
            Service::firstOrCreate([
                'name' => $category->name.' Service 1',
                'user_id' => $provider->id,
            ], [
                'description' => $category->description,
                'price' => rand(50, 200),
            ]);
        }
    }
}
