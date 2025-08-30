<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchableSelect extends Component
{
    public $options;
    public $model;
    public $title;

    public function __construct($collection, $value, $label, $title, $model)
    {
        $this->model = $model;
        $this->title = $title;
        $this->set_options($collection, $value, $label);
    }

    private function set_options($collection, $value, $label)
    {
        foreach ($collection as $item) {
            $this->options[] = ['value' => $item->$value, 'label' => $item->$label];
        }
    }
    
    public function render(): View|Closure|string
    {
        return view('components.searchable-select');
    }
}
