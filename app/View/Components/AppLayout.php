<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public ?string $pageTitle;

    public function __construct(string $pageTitle = null)
    {
        $this->pageTitle = $pageTitle;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
