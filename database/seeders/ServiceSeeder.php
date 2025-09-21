<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Individual Therapy Session',
                'description' => 'One-on-one counseling session with licensed mental health professional. Location: Online / Office. Requirements: quiet space, stable internet.',
                'category' => 'professional',
                'base_price' => 5000.00,
                'duration' => 60,
            ],
            [
                'name' => 'Family Therapy Session',
                'description' => 'Family counseling session to improve communication and relationships. Location: Online / Office. Requirements: all family members present, quiet space.',
                'category' => 'professional',
                'base_price' => 7500.00,
                'duration' => 90,
            ],
            [
                'name' => 'Group Bonding Activity',
                'description' => 'Community-based family bonding activities and workshops. Requirements: comfortable clothes, positive attitude.',
                'category' => 'bonding',
                'base_price' => 1500.00,
                'duration' => 180,
            ],
            [
                'name' => 'Crisis Intervention',
                'description' => 'Emergency mental health support and crisis counseling. Requirements: immediate availability, safe environment.',
                'category' => 'professional',
                'base_price' => 8000.00,
                'duration' => 45,
            ],
            [
                'name' => 'Outdoor Family Adventure',
                'description' => 'Nature-based family bonding activities and outdoor adventures. Location: Outdoor Camp Site. Requirements: weather-appropriate clothing, physical fitness.',
                'category' => 'bonding',
                'base_price' => 2000.00,
                'duration' => 240,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert(array_merge($service, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
