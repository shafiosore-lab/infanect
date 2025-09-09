<?php

namespace App\Observers;

use App\Models\ServiceProvider;
use App\Events\DashboardUpdated;
use App\Models\Activity;
use App\Models\Service;

class ServiceProviderObserver
{
    public function saved(ServiceProvider $provider): void  { $this->broadcast(); }
    public function deleted(ServiceProvider $provider): void { $this->broadcast(); }

    protected function broadcast(): void
    {
        $counts = [
            'activities' => Activity::count(),
            'services'   => Service::count(),
            'providers'  => ServiceProvider::count(),
        ];

        $providersHtml = view('partials.top-providers', [
            'providers' => ServiceProvider::latest()->take(12)->get()
        ])->render();

        broadcast(new DashboardUpdated($counts, null, null, $providersHtml));
    }
}
