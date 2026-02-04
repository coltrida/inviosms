<?php

namespace App\Livewire\Home;

use App\Models\Strutture;
use Livewire\Component;

class Estraiquattro extends Component
{
    public $caps = [];
    public $nomeRecapito;

    public function recapitoSelezionato($recapito)
    {
        $this->caps = [];
        $recapitoArray = json_decode($recapito, true);
        $this->nomeRecapito = $recapitoArray['nome'];
        $caps = $recapitoArray['caps'];
        $capsArray = array_column($caps, 'cap');
        $this->caps = $capsArray;
    }

    public function visualizza()
    {
        $this->dispatch('visualizzaCaps', caps: $this->caps, nomeRecapito: $this->nomeRecapito);
    }

    public function render()
    {
        return view('livewire.home.estraiquattro', [
            'recapiti' => Strutture::recapiti()->with('caps')->orderBy('nome')->get()
        ]);
    }
}
