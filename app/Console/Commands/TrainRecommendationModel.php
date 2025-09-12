<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TrainRecommendationModel extends Command
{
    protected $signature = 'recommendations:train';
    protected $description = 'Train recommendation model (mock)';

    public function handle()
    {
        $this->info('Starting mock training...');
        // simulate training work
        sleep(1);
        file_put_contents(storage_path('app/recommendation_model.json'), json_encode(['trained_at' => now()->toDateTimeString()]));
        $this->info('Training complete.');
        return 0;
    }
}
