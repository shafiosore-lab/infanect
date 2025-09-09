<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixUserPasswords extends Command
{
    protected $signature = 'users:fix-passwords';
    protected $description = 'Fix user passwords that are not properly hashed';

    public function handle()
    {
        $this->info('Starting password fix process...');

        $users = User::all();
        $fixed = 0;

        foreach ($users as $user) {
            // Check if password is not hashed with bcrypt
            if (!Hash::needsRehash($user->password)) {
                continue; // Password is already properly hashed
            }

            // For existing users, you might want to:
            // Option 1: Set a default password
            $user->password = Hash::make('password123'); // Change this default

            // Option 2: Generate random password and email it to user
            // $newPassword = Str::random(12);
            // $user->password = Hash::make($newPassword);
            // Mail user the new password

            $user->save();
            $fixed++;

            $this->line("Fixed password for user: {$user->email}");
        }

        $this->info("Fixed {$fixed} user passwords.");

        if ($fixed > 0) {
            $this->warn('Remember to inform users about their new passwords!');
        }

        return 0;
    }
}
