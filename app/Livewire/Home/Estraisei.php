<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Estraisei extends Component
{
    public function visualizza()
    {
        $this->dispatch('visuSei');
    }

    public function render()
    {
        return view('livewire.home.estraisei');
    }
}
