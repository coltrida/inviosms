<?php

namespace App\Livewire\Home;

use App\Models\Strutture;
use Livewire\Component;

class Estraiquattro extends Component
{
    public $caps = [];

    public function recapitoSelezionato($recapito)
    {
        $this->caps = [];
        $recapitoArray = json_decode($recapito, true);
        $caps = $recapitoArray['caps'];
        $capsArray = array_column($caps, 'cap');
        $this->caps = $capsArray;
    }

    public function visualizza()
    {
        $this->dispatch('visualizzaCaps', caps: $this->caps);
    }

    public function render()
    {
        return view('livewire.home.estraiquattro', [
            'recapiti' => Strutture::recapiti()->with('caps')->orderBy('nome')->get()
        ]);
    }
}
