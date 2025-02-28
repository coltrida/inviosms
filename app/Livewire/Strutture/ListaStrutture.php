<?php

namespace App\Livewire\Strutture;

use App\Models\Strutture;
use App\Models\Strutturecap;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListaStrutture extends Component
{
    use WithPagination;

    #[Url]
    public ?string $testoRicerca = '';

    public $tipo = '';
    public $citta = '';
    public $provincia = '';

    public $nuovocap;
    public $idStruttura;

    public function eliminaRicerca()
    {
        $this->testoRicerca = '';
    }

    public function associa()
    {
        Strutturecap::create([
            'strutture_id' => $this->idStruttura,
            'cap' => $this->nuovocap
        ]);
    }

    public function render()
    {
        return view('livewire.strutture.lista-strutture', [
            'strutture' => Strutture::with('caps')
                ->where('nome', 'like', '%'.$this->testoRicerca.'%')
                ->where('tipo', 'like', '%'.$this->tipo.'%')
                ->where('citta', 'like', '%'.$this->citta.'%')
                ->where('provincia', 'like', '%'.$this->provincia.'%')
                ->paginate(10),
            'listacitta' => Strutture::where('citta', '!=', '')->orderBy('citta')->distinct()->pluck('citta')->toArray(),
            'listaprovince' => Strutture::where('provincia', '!=', '')->orderBy('provincia')->distinct()->pluck('provincia')->toArray(),
        ]);
    }
}
