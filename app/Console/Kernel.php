<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\DiagnoseProject::class,
        \App\Console\Commands\BootstrapDemo::class,
        \App\Console\Commands\TrainRecommendationModel::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('recommendations:train')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
