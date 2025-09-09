<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name'          => 'Mindful Space Therapy',
                'service_type'  => 'Therapy & Counseling',
                'email'         => 'contact@mindfulspace.com',
                'phone'         => '+1-202-555-0101',
                'country_code'  => 'US',
                'city'          => 'New York',
                'state'         => 'NY',
                'address'       => '123 Madison Ave',
                'postal_code'   => '10016',
                'latitude'      => 40.7128,
                'longitude'     => -74.0060,
                'is_available'  => true,
                'avg_rating'    => 4.7,
                'total_reviews' => 120,
                'total_revenue' => 25000.50,
            ],
            [
                'name'          => 'Nairobi Family Bonding',
                'service_type'  => 'Bonding Activities',
                'email'         => 'info@nairobi-bonding.ke',
                'phone'         => '+254-700-123456',
                'country_code'  => 'KE',
                'city'          => 'Nairobi',
                'state'         => 'Nairobi',
                'address'       => 'Kenyatta Avenue',
                'postal_code'   => '00100',
                'latitude'      => -1.2921,
                'longitude'     => 36.8219,
                'is_available'  => true,
                'avg_rating'    => 4.5,
                'total_reviews' => 95,
                'total_revenue' => 18000.00,
            ],
            [
                'name'          => 'Tokyo Kids Connect',
                'service_type'  => 'Child Development',
                'email'         => 'support@kidsconnect.jp',
                'phone'         => '+81-3-1234-5678',
                'country_code'  => 'JP',
                'city'          => 'Tokyo',
                'state'         => 'Tokyo',
                'address'       => 'Shibuya Crossing',
                'postal_code'   => '150-0002',
                'latitude'      => 35.6895,
                'longitude'     => 139.6917,
                'is_available'  => false,
                'avg_rating'    => 4.8,
                'total_reviews' => 200,
                'total_revenue' => 40000.75,
            ],
            [
                'name'          => 'Berlin Parent-Child Hub',
                'service_type'  => 'Workshops & Training',
                'email'         => 'hello@pchildhub.de',
                'phone'         => '+49-30-123456',
                'country_code'  => 'DE',
                'city'          => 'Berlin',
                'state'         => 'Berlin',
                'address'       => 'Alexanderplatz 5',
                'postal_code'   => '10178',
                'latitude'      => 52.5200,
                'longitude'     => 13.4050,
                'is_available'  => true,
                'avg_rating'    => 4.3,
                'total_reviews' => 70,
                'total_revenue' => 15000.00,
            ],
            [
                'name'          => 'Mumbai Bonding Activities',
                'service_type'  => 'Outdoor Events',
                'email'         => 'events@bonding.in',
                'phone'         => '+91-9876543210',
                'country_code'  => 'IN',
                'city'          => 'Mumbai',
                'state'         => 'Maharashtra',
                'address'       => 'Marine Drive',
                'postal_code'   => '400001',
                'latitude'      => 19.0760,
                'longitude'     => 72.8777,
                'is_available'  => true,
                'avg_rating'    => 4.6,
                'total_reviews' => 150,
                'total_revenue' => 30000.00,
            ],
        ];

        foreach ($providers as $data) {
            Provider::create($data);
        }
    }
}
