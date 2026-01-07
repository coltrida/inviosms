<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Estraisette extends Component
{
    public function visualizza()
    {
        $this->dispatch('visuSette');
    }

    public function render()
    {
        return view('livewire.home.estraisette');
    }
}
