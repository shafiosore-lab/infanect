<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeduplicateServiceProvidersSeeder extends Seeder
{
    public function run()
    {
        if (!\Schema::hasTable('service_providers')) return;

        $providers = DB::table('service_providers')->get();
        $seen = [];

        foreach ($providers as $p) {
            $email = strtolower(trim($p->email ?? ''));
            if (!$email) continue;

            if (isset($seen[$email])) {
                // append suffix
                $i = ++$seen[$email];
                $newEmail = preg_replace('/(.*)@(.*)/', '$1+' . $i . '@$2', $email);
                DB::table('service_providers')->where('id', $p->id)->update(['email' => $newEmail]);
                $this->command->info("Updated provider {$p->id} email to {$newEmail}");
            } else {
                $seen[$email] = 1;
            }
        }
    }
}
