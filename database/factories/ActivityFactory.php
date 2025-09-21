<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['cooking', 'outdoor', 'creative', 'sports', 'educational', 'arts', 'nature', 'play'];
        $category = $this->faker->randomElement($categories);

        // Generate valid start and end dates
        $startDate = $this->faker->dateTimeBetween('-7 days', '+30 days');
        $endDate = (clone $startDate)->modify('+' . rand(1, 4) . ' hours');

        return [
            'title' => $this->getActivityTitle($category),
            'description' => $this->getActivityDescription($category),
            'type' => 'bonding',
            'category' => $category,
            'max_participants' => $this->faker->numberBetween(8, 30),
            'price' => $this->faker->numberBetween(500, 3000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->getKenyanLocation(),
            'status' => $this->faker->randomElement(['draft', 'published', 'cancelled']),
            'requirements' => $this->getActivityRequirements($category),
            'tags' => $this->getActivityTags($category),
            'created_by' => User::where('provider_type', 'provider-bonding')->inRandomOrder()->first()?->id ?? 1,
            'is_active' => $this->faker->boolean(90),
        ];
    }

    // -----------------------------
    // Helper methods
    // -----------------------------
    private function getActivityTitle($category): string
    {
        $titles = [
            'cooking' => ['Family Cooking Workshop','Traditional Kenyan Cuisine','Healthy Family Meals','Kids Cook with Parents','Cultural Food Journey','Farm to Table Experience'],
            'outdoor' => ['Nature Walk & Picnic','Family Hiking Adventure','Park Exploration Day','Wildlife Safari Tour','Beach Family Fun','Forest Discovery Walk'],
            'creative' => ['Arts & Crafts Session','Family Art Workshop','Creative Expression Class','DIY Family Projects','Pottery Making Fun','Music & Movement Session'],
            'sports' => ['Family Sports Day','Team Building Games','Athletic Family Fun','Soccer Skills Workshop','Swimming Lessons','Fitness Challenge Day'],
            'educational' => ['Cultural Heritage Tour','Science Discovery Lab','History Walking Tour','Environmental Awareness','Language Learning Fun','STEM Family Workshop'],
            'arts' => ['Creative Expression Class','Family Art Gallery','Drama Workshop','Music Appreciation','Dance Together Session','Storytelling Circle'],
            'nature' => ['Nature Exploration Walk','Garden Together Time','Animal Care Workshop','Eco-Friendly Projects','Bird Watching Adventure','Tree Planting Activity'],
            'play' => ['Play Center Fun','Interactive Games Day','Playground Adventures','Family Game Tournament','Fun & Learning Hub','Active Play Session'],
        ];

        return $this->faker->randomElement($titles[$category] ?? $titles['outdoor']);
    }

    private function getActivityDescription($category): string
    {
        $descriptions = [
            'cooking' => 'Learn to prepare delicious Kenyan dishes together as a family. Bond over traditional cooking methods and enjoy the fruits of your labor.',
            'outdoor' => 'Explore the beauty of Kenya\'s nature together. Enjoy fresh air, exercise, and quality family time in stunning outdoor locations.',
            'creative' => 'Express your creativity together through various art forms. Create lasting memories and beautiful artwork as a family.',
            'sports' => 'Stay active and healthy together through fun sports activities. Build teamwork skills and enjoy friendly competition.',
            'educational' => 'Learn about Kenya\'s rich culture and history together. Expand your knowledge while strengthening family bonds.',
            'arts' => 'Discover the joy of artistic expression as a family. Explore different art forms and unleash your creativity together.',
            'nature' => 'Connect with nature and learn about environmental conservation. Develop appreciation for Kenya\'s natural beauty.',
            'play' => 'Enjoy structured play activities that promote learning and development. Have fun while building important life skills.',
        ];

        return $descriptions[$category] ?? $descriptions['outdoor'];
    }

    private function getKenyanLocation(): string
    {
        $locations = [
            'Community Center, Westlands','Karura Forest, Nairobi','Art Studio, Karen','Uhuru Park Sports Ground',
            'Bomas of Kenya, Nairobi','Nairobi National Park','City Park, Nairobi','Giraffe Centre, Karen',
            'David Sheldrick Elephant Orphanage','Nairobi Museum Gardens','Riverside Park, Westlands','KICC Grounds',
            'Arboretum Park','Ngong Hills','Paradise Lost, Kiambu','Village Market, Gigiri',
            'Two Rivers Mall, Runda','Sarit Centre, Westlands','Junction Mall, Ngong Road','Garden City Mall, Thika Road'
        ];

        return $this->faker->randomElement($locations);
    }

    private function getActivityRequirements($category): array
    {
        $requirements = [
            'cooking' => ['apron','comfortable_shoes','hair_tie'],
            'outdoor' => ['comfortable_walking_shoes','water_bottle','hat','sunscreen'],
            'creative' => ['old_clothes','creativity','patience'],
            'sports' => ['sports_clothes','water_bottle','towel','energy'],
            'educational' => ['comfortable_shoes','camera','notebook','curiosity'],
            'arts' => ['comfortable_clothes','open_mind','enthusiasm'],
            'nature' => ['outdoor_clothes','water_bottle','binoculars'],
            'play' => ['comfortable_clothes','positive_attitude','energy'],
        ];

        return $requirements[$category] ?? $requirements['outdoor'];
    }

    private function getActivityTags($category): array
    {
        $tags = [
            'cooking' => ['family','cooking','traditional','bonding','culture'],
            'outdoor' => ['nature','outdoor','exercise','family','adventure'],
            'creative' => ['art','crafts','creativity','bonding','learning'],
            'sports' => ['sports','games','competition','family','fitness'],
            'educational' => ['culture','education','heritage','learning','history'],
            'arts' => ['art','creativity','expression','culture','learning'],
            'nature' => ['nature','environment','conservation','outdoor','education'],
            'play' => ['play','fun','learning','development','interactive'],
        ];

        return $tags[$category] ?? $tags['outdoor'];
    }

    // -----------------------------
    // States
    // -----------------------------
    public function published()
    {
        return $this->state(fn(array $attributes) => ['status' => 'published']);
    }

    public function draft()
    {
        return $this->state(fn(array $attributes) => ['status' => 'draft']);
    }

    public function upcoming()
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => $start = now()->addDays(rand(1, 7))->setTime(rand(9,16), [0,30][rand(0,1)]),
            'end_date' => (clone $start)->addHours(rand(1,4)),
        ]);
    }

    public function past()
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => $start = now()->subDays(rand(1, 30))->setTime(rand(9,16), [0,30][rand(0,1)]),
            'end_date' => (clone $start)->addHours(rand(1,4)),
            'status' => 'published',
        ]);
    }
}
