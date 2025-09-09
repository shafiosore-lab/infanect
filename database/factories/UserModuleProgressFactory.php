<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserModuleProgressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module_id' => Module::factory(),
            'progress' => $this->faker->numberBetween(0, 100),
        ];
    }
}
