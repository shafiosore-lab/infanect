<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class BootstrapDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infanect:bootstrap {--start-worker : Start a background queue worker after bootstrapping}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations, seeders, storage:link and optionally start queue worker for demo setup.';

    public function handle(): int
    {
        $this->info('Starting Infanect bootstrap...');

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Running database seeders...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Creating storage symlink (if missing)...');
        try {
            Artisan::call('storage:link');
            $this->info(Artisan::output());
        } catch (\Throwable $e) {
            $this->warn('Could not create storage link: '.$e->getMessage());
        }

        if ($this->option('start-worker')) {
            $this->info('Starting background queue worker...');
            try {
                $cmd = ['php', 'artisan', 'queue:work', '--tries=3'];
                $process = new Process($cmd);
                $process->setWorkingDirectory(base_path());
                $process->setTimeout(0);
                $process->start();

                $this->info('Queue worker started.');
            } catch (\Throwable $e) {
                $this->error('Failed to start background worker: '.$e->getMessage());
            }
        }

        $this->info('Bootstrap complete.');
        return 0;
    }
}
