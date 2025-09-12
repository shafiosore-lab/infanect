<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ProviderProfile;

class ProviderProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role_id', '!=', null)->get();

        foreach ($users as $user) {
            // Build profile data only with columns that exist in the table
            $profileData = ['user_id' => $user->id];

            if (Schema::hasColumn('provider_profiles', 'company_name')) {
                $profileData['company_name'] = $user->name . ' Services';
            }
            if (Schema::hasColumn('provider_profiles', 'slug')) {
                $profileData['slug'] = Str::slug($user->name . '-' . $user->id);
            }
            if (Schema::hasColumn('provider_profiles', 'bio')) {
                $profileData['bio'] = 'Auto-created provider profile.';
            }
            if (Schema::hasColumn('provider_profiles', 'phone')) {
                $profileData['phone'] = $user->phone ?? null;
            }
            if (Schema::hasColumn('provider_profiles', 'website')) {
                $profileData['website'] = null;
            }
            if (Schema::hasColumn('provider_profiles', 'address')) {
                $profileData['address'] = null;
            }
            if (Schema::hasColumn('provider_profiles', 'avatar')) {
                $profileData['avatar'] = null;
            }
            if (Schema::hasColumn('provider_profiles', 'is_verified')) {
                $profileData['is_verified'] = false;
            }
            if (Schema::hasColumn('provider_profiles', 'meta')) {
                $profileData['meta'] = null;
            }

            try {
                ProviderProfile::updateOrCreate(['user_id' => $user->id], $profileData);
            } catch (\Throwable $e) {
                // log and continue if profile creation fails due to schema differences
                DB::rollBack();
                continue;
            }
        }
    }
}
