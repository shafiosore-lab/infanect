<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class DiagnoseProject extends Command
{
    protected $signature = 'app:diagnose {--autofix : Create minimal controller stubs for missing controllers} {--route-stubs : Generate route placeholders for missing named routes}';

    protected $description = 'Scan project for missing controller classes and undefined route names referenced in Blade views.';

    public function handle()
    {
        $report = [];
        $createdFiles = [];

        $this->info('Scanning registered routes for controller classes...');
        $routes = Route::getRoutes();
        $missingControllers = [];

        foreach ($routes as $route) {
            $action = $route->getActionName();
            if (is_string($action) && strpos($action, '@') !== false) {
                [$class, $method] = explode('@', $action);
                if (!class_exists($class)) {
                    $missingControllers[$class][] = $route->uri();
                }
            }
        }

        if (empty($missingControllers)) {
            $this->info('No missing controller classes found.');
            $report[] = "No missing controller classes found.";
        } else {
            $this->warn('Missing controller classes:');
            $report[] = "Missing controller classes:";
            foreach ($missingControllers as $class => $uris) {
                $this->line(" - {$class} (used in: \n   " . implode('\n   ', $uris) . ")");
                $report[] = " - {$class} (used in: " . implode(', ', $uris) . ")";
            }

            if ($this->option('autofix')) {
                $this->info('Autofix enabled: creating controller stubs...');
                foreach ($missingControllers as $class => $uris) {
                    $created = $this->createControllerStub($class);
                    if ($created) {
                        $createdFiles[] = $created;
                        $this->line("Created stub: {$created}");
                        $report[] = "Created controller stub: {$created}";
                    }
                }
            }
        }

        $this->info('Scanning Blade views for route(...) usages...');
        $viewPath = resource_path('views');
        $bladeFiles = File::allFiles($viewPath);
        $routeNames = [];

        foreach ($bladeFiles as $file) {
            $contents = File::get($file->getRealPath());
            // match route('name' or route("name")
            preg_match_all('/route\(\s*[\\\'\"]([^\\\'\"]+)[\\\'\"]/', $contents, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $name) {
                    $routeNames[$name][] = str_replace(base_path().'\\', '', $file->getRealPath());
                }
            }
        }

        $missingRoutes = [];
        foreach ($routeNames as $name => $files) {
            if (!\Illuminate\Support\Facades\Route::has($name)) {
                $missingRoutes[$name] = $files;
            }
        }

        if (empty($missingRoutes)) {
            $this->info('No missing named routes found in Blade views.');
            $report[] = "No missing named routes found in Blade views.";
        } else {
            $this->warn('Missing named routes referenced in Blade views:');
            $report[] = "Missing named routes referenced in Blade views:";
            foreach ($missingRoutes as $name => $files) {
                $this->line(" - {$name} (used in: \n   " . implode('\n   ', $files) . ")");
                $report[] = " - {$name} (used in: " . implode(', ', $files) . ")";
            }

            if ($this->option('route-stubs')) {
                $this->info('Generating route stub file...');
                $stubPath = base_path('routes/diagnose_missing_routes.php');
                $contents = $this->buildRouteStubs($missingRoutes);
                File::put($stubPath, $contents);
                $createdFiles[] = $stubPath;
                $this->line("Created route stubs file: {$stubPath}");
                $report[] = "Created route stubs file: {$stubPath}";

                // Ensure routes/web.php includes the stub file
                $webRoutes = base_path('routes/web.php');
                $includeLine = "require __DIR__.'/diagnose_missing_routes.php';";
                $webContents = File::get($webRoutes);
                if (strpos($webContents, $includeLine) === false) {
                    File::append($webRoutes, "\n\n// Included by diagnose tool for missing routes\n{$includeLine}\n");
                    $this->line("Appended include to routes/web.php");
                    $report[] = "Appended include to routes/web.php";
                }
            }
        }

        // Save report
        $logPath = storage_path('logs/diagnose.txt');
        $summary = array_merge($report, ['Created files:' => implode(', ', $createdFiles)]);
        File::put($logPath, implode("\n", $summary));

        $this->info('Diagnosis complete. Report written to: '.$logPath);

        if (!empty($createdFiles)) {
            $this->info('Files created:');
            foreach ($createdFiles as $f) $this->line(' - '.$f);
        }

        return 0;
    }

    protected function createControllerStub(string $fqcn): ?string
    {
        // Convert FQCN to file path under app/
        $nsParts = explode('\\', trim($fqcn, '\\'));
        if (empty($nsParts)) return null;

        // Ensure it is within App\Http\Controllers
        $appIndex = array_search('App', $nsParts);
        if ($appIndex === false) return null;

        $relative = array_slice($nsParts, $appIndex + 1); // e.g., Http, Controllers, FooController
        $filePath = base_path(implode(DIRECTORY_SEPARATOR, $relative) . '.php');
        $dir = dirname($filePath);
        if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);

        $className = array_pop($relative);
        $namespace = 'App\\' . implode('\\', array_slice($nsParts, 1, count($nsParts) - 2));
        // Build a safe namespace if something odd
        if (!str_contains($fqcn, 'App\\Http\\Controllers')) {
            $namespace = implode('\\', array_slice($nsParts, 0, -1));
        }

        $stub = "<?php\n\nnamespace {$namespace};\n\nuse App\\Http\\Controllers\\Controller;\nuse Illuminate\\Http\\Request;\n\nclass {$className} extends Controller\n{\n    public function index()\n    {\n        return view('welcome');\n    }\n}\n";

        if (!File::exists($filePath)) {
            File::put($filePath, $stub);
            return $filePath;
        }

        return null;
    }

    protected function buildRouteStubs(array $missingRoutes): string
    {
        $lines = ["<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Auto-generated route stubs for missing named routes (diagnose tool)\n\n"];

        $i = 1;
        foreach ($missingRoutes as $name => $files) {
            $uri = '/_diagnose/' . preg_replace('/[^a-z0-9_\-]/i', '_', $name);
            $lines[] = "Route::any('{$uri}', function() { return response('Placeholder route for {$name}', 200); })->name('{$name}');\n";
            $i++;
        }

        return implode("\n", $lines);
    }
}
