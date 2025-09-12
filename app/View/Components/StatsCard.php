<?php

namespace App\View\Components;

use App\View\Components\BaseComponent;

class StatsCard extends BaseComponent
{
    public function __construct(
        public string $title,
        public string|int|float $value,
        public ?string $icon = null,
        public ?string $color = 'blue'
    ) {}

    public function render()
    {
        return view('components.stats-card');
    }
}
