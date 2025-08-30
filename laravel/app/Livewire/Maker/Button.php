<?php

namespace App\Livewire\Maker;

use Livewire\Component;

class Button extends Component
{
    public $modal;
    public $label;
    public $justify;

    public function mount($modal, $label, $justify = 'end')
    {
        $this->modal = $modal;
        $this->label = $label;
        $this->justify = $justify;
    }

    public function render()
    {
        return view('livewire.maker.button');
    }
}
