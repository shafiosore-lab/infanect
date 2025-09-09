<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'provider_id' => 1,
                'reviewer_name' => 'John Doe',
                'country_code' => 'US',
                'rating' => 5,
                'comment' => 'Excellent service! Highly recommend.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 1,
                'reviewer_name' => 'Jane Smith',
                'country_code' => 'US',
                'rating' => 4,
                'comment' => 'Great experience, will book again.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 2,
                'reviewer_name' => 'Alice Johnson',
                'country_code' => 'KE',
                'rating' => 5,
                'comment' => 'Amazing bonding activities for families.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 2,
                'reviewer_name' => 'Bob Wilson',
                'country_code' => 'KE',
                'rating' => 4,
                'comment' => 'Good service, but could be better organized.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 3,
                'reviewer_name' => 'Charlie Brown',
                'country_code' => 'JP',
                'rating' => 5,
                'comment' => 'Perfect for child development.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 4,
                'reviewer_name' => 'Diana Prince',
                'country_code' => 'DE',
                'rating' => 4,
                'comment' => 'Informative workshops.',
                'is_approved' => true,
            ],
            [
                'provider_id' => 5,
                'reviewer_name' => 'Eve Adams',
                'country_code' => 'IN',
                'rating' => 5,
                'comment' => 'Fun outdoor events!',
                'is_approved' => true,
            ],
        ];

        foreach ($reviews as $review) {
            \App\Models\Review::create($review);
        }
    }
}
