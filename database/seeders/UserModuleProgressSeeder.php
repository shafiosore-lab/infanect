<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Module;
use App\Models\UserModuleProgress;

class UserModuleProgressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $modules = Module::all();

        foreach ($users as $user) {
            foreach ($modules as $module) {
                UserModuleProgress::factory()->create([
                    'user_id' => $user->id,
                    'module_id' => $module->id,
                ]);
            }
        }
    }
}
