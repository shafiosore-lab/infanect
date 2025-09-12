<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearViewCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:clearcache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all compiled view files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Path to compiled views
        $path = storage_path('framework/views');

        if (File::exists($path)) {
            // Delete all files in the views folder
            foreach (File::glob("{$path}/*") as $view) {
                File::delete($view);
            }

            $this->info('Compiled views cleared!');
        } else {
            $this->info('Views folder not found.');
        }

        // Run the standard Laravel view:clear command
        $this->call('view:clear');
        $this->call('cache:clear');

        $this->info('All view caches cleared successfully!');
    }
}
