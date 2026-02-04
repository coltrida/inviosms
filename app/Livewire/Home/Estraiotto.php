<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Estraiotto extends Component
{
    public function visualizza()
    {
        $this->dispatch('visuOtto');
    }

    public function render()
    {
        return view('livewire.home.estraiotto');
    }
}
