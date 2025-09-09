<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public array $counts;
    public ?string $activitiesHtml;
    public ?string $servicesHtml;
    public ?string $providersHtml;

    public function __construct(array $counts, ?string $activitiesHtml = null, ?string $servicesHtml = null, ?string $providersHtml = null)
    {
        $this->counts        = $counts;
        $this->activitiesHtml = $activitiesHtml;
        $this->servicesHtml   = $servicesHtml;
        $this->providersHtml  = $providersHtml;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('dashboard.global');
    }

    public function broadcastAs(): string
    {
        return 'DashboardUpdated';
    }
}
