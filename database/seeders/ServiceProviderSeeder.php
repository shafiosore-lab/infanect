<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceProvider;
use App\Models\User;

class ServiceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Little Stars Childcare',
                'email' => 'contact@littlestars.com',
                'phone' => '+254712345678',
                'bio' => 'Professional childcare services with over 10 years of experience in early childhood development.',
                'specialization' => 'Early Childhood Education',
                'rating' => 4.8,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Sunshine Kids Academy',
                'email' => 'info@sunshinekids.co.ke',
                'phone' => '+254723456789',
                'bio' => 'Comprehensive childcare and educational programs for children aged 1-6 years.',
                'specialization' => 'Infant Care',
                'rating' => 4.6,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Tiny Tots Nursery',
                'email' => 'hello@tinytots.co.ke',
                'phone' => '+254734567890',
                'bio' => 'Nurturing environment for toddlers with focus on social and emotional development.',
                'specialization' => 'Toddler Care',
                'rating' => 4.7,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Rainbow Children\'s Center',
                'email' => 'admin@rainbowcenter.com',
                'phone' => '+254745678901',
                'bio' => 'Creative and educational activities for preschool children with Montessori approach.',
                'specialization' => 'Preschool Education',
                'rating' => 4.9,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Happy Hearts Daycare',
                'email' => 'care@happyhearts.co.ke',
                'phone' => '+254756789012',
                'bio' => 'Loving daycare services with emphasis on physical activities and outdoor play.',
                'specialization' => 'Physical Development',
                'rating' => 4.5,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Bright Futures Academy',
                'email' => 'info@brightfutures.ac.ke',
                'phone' => '+254767890123',
                'bio' => 'Academic preparation for school-age children with focus on STEM education.',
                'specialization' => 'School Readiness',
                'rating' => 4.8,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Little Angels Montessori',
                'email' => 'contact@littleangels.org',
                'phone' => '+254778901234',
                'bio' => 'Montessori-based learning environment fostering independence and creativity.',
                'specialization' => 'Montessori Education',
                'rating' => 4.7,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Playful Minds Nursery',
                'email' => 'hello@playfulminds.co.ke',
                'phone' => '+254789012345',
                'bio' => 'Play-based learning approach for children with special focus on cognitive development.',
                'specialization' => 'Cognitive Development',
                'rating' => 4.6,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Green Valley Childcare',
                'email' => 'info@greenvalley.co.ke',
                'phone' => '+254790123456',
                'bio' => 'Nature-based childcare with outdoor activities and environmental education.',
                'specialization' => 'Nature Education',
                'rating' => 4.4,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
            [
                'name' => 'Creative Kids Hub',
                'email' => 'admin@creativekids.co.ke',
                'phone' => '+254701234567',
                'bio' => 'Arts and crafts focused childcare center encouraging creative expression.',
                'specialization' => 'Arts & Creativity',
                'rating' => 4.5,
                'country' => 'Kenya',
                'language' => 'en',
                'currency' => 'KES',
            ],
        ];

        foreach ($providers as $providerData) {
            ServiceProvider::create($providerData);
        }
    }
}
