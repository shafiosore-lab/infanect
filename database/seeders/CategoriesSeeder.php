<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name'=>'Bonding Activities','description'=>'Activities to strengthen family bonds'],
            ['name'=>'Professional Service Providers','description'=>'Skilled service providers'],
            ['name'=>'Educational Programs','description'=>'Workshops & training for kids'],
            ['name'=>'Therapeutic & Mental Health','description'=>'Counseling & support services'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name'=>$category['name']], $category);
        }
    }
}
