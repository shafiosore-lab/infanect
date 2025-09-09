<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Infant Care Package',
                'description' => 'Complete care package for infants 0-12 months including feeding, diaper changes, and developmental activities.',
                'price' => 2500.00,
                'duration' => 'Full Day (8 hours)',
                'location' => 'Nairobi Central',
                'is_active' => true,
                'user_id' => 1, // Assuming admin user exists
            ],
            [
                'name' => 'Toddler Development Program',
                'description' => 'Structured program for toddlers focusing on motor skills, language development, and social interaction.',
                'price' => 3000.00,
                'duration' => 'Half Day (4 hours)',
                'location' => 'Westlands, Nairobi',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Preschool Readiness',
                'description' => 'Comprehensive preparation for preschool including basic literacy, numeracy, and social skills.',
                'price' => 3500.00,
                'duration' => 'Full Day (8 hours)',
                'location' => 'Karen, Nairobi',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'After School Care',
                'description' => 'Safe and engaging after-school care with homework assistance and recreational activities.',
                'price' => 1500.00,
                'duration' => '3 hours',
                'location' => 'Kilimani, Nairobi',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Weekend Family Activities',
                'description' => 'Fun-filled weekend activities for the whole family including arts, crafts, and outdoor games.',
                'price' => 2000.00,
                'duration' => 'Full Day (6 hours)',
                'location' => 'Various Locations',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Summer Camp Program',
                'description' => 'Exciting summer camp with educational activities, swimming, and team-building exercises.',
                'price' => 5000.00,
                'duration' => 'Week (5 days)',
                'location' => 'Outdoor Camp Site',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Special Needs Support',
                'description' => 'Specialized care and educational support for children with special needs and developmental challenges.',
                'price' => 4000.00,
                'duration' => 'Full Day (8 hours)',
                'location' => 'Specialized Center',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Music and Arts Therapy',
                'description' => 'Therapeutic sessions using music, art, and creative expression for emotional development.',
                'price' => 1800.00,
                'duration' => '1.5 hours',
                'location' => 'Creative Arts Center',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Sports and Physical Education',
                'description' => 'Comprehensive physical education program including various sports and fitness activities.',
                'price' => 2200.00,
                'duration' => '2 hours',
                'location' => 'Sports Complex',
                'is_active' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'STEM Learning Program',
                'description' => 'Science, Technology, Engineering, and Mathematics focused learning for young innovators.',
                'price' => 2800.00,
                'duration' => '3 hours',
                'location' => 'Innovation Hub',
                'is_active' => true,
                'user_id' => 1,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }
    }
}
