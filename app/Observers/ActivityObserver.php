<?php

namespace App\Observers;

use App\Models\Activity;
use App\Events\DashboardUpdated;
use App\Models\Service;
use App\Models\ServiceProvider;

class ActivityObserver
{
    public function saved(Activity $activity): void  { $this->broadcast(); }
    public function deleted(Activity $activity): void { $this->broadcast(); }

    protected function broadcast(): void
    {
        $counts = [
            'activities' => Activity::count(),
            'services'   => Service::count(),
            'providers'  => ServiceProvider::count(),
        ];

        // Option 1: send fresh fragments to clients
        $activitiesHtml = view('partials.top-bonding-activities', [
            'activities' => Activity::latest('datetime')->take(12)->get()
        ])->render();

        broadcast(new DashboardUpdated($counts, $activitiesHtml, null, null));
    }
}
