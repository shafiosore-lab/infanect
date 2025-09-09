<?php

namespace App\Observers;

use App\Models\Service;
use App\Events\DashboardUpdated;
use App\Models\Activity;
use App\Models\ServiceProvider;

class ServiceObserver
{
    public function saved(Service $service): void  { $this->broadcast(); }
    public function deleted(Service $service): void { $this->broadcast(); }

    protected function broadcast(): void
    {
        $counts = [
            'activities' => Activity::count(),
            'services'   => Service::count(),
            'providers'  => ServiceProvider::count(),
        ];

        $servicesHtml = view('partials.top-services', [
            'services' => Service::latest()->take(12)->get()
        ])->render();

        broadcast(new DashboardUpdated($counts, null, $servicesHtml, null));
    }
}
