<?php

namespace App\Livewire\Home;

use Livewire\Attributes\On;
use Livewire\Component;

class VisualizzaEstraisette extends Component
{

    #[On('visuSette')]
    public function visualizza()
    {

    }

    public function render()
    {
        return view('livewire.home.visualizza-estraisette');
    }
}
