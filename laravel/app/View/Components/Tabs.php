<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return function (array $data) 
        {
            $slots = $data['__laravel_slots'];
            unset($slots['__default']);
            return view('components.tabs', compact('slots'));
        };
    }
}
