<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SummaryCard extends Component
{
    public string $title;
    public int|string $value;
    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, int|string $value, string $color = 'text-gray-600')
    {
        $this->title = $title;
        $this->value = $value;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.summary-card');
    }
}
