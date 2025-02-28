<?php

namespace App\Livewire\Clienti;

use App\Models\Client;
use App\Models\Strutture;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListaClienti extends Component
{
    use WithPagination;

    #[Url]
    public ?string $testoRicerca = '';

    public $store_id;
    public $tipo;

    public function eliminaRicerca()
    {
        $this->testoRicerca = '';
    }

    public function render()
    {
        return view('livewire.clienti.lista-clienti', [
            'clients' => Client::withCount('appointments', 'phones')
                ->where(function ($query) {
                    $query->where('nome', 'like', '%'. strtoupper($this->testoRicerca) .'%')
                        ->orWhere('cognome', 'like', '%'. strtoupper($this->testoRicerca) .'%')
                        ->orWhere('fullname', 'like', '%'. strtoupper($this->testoRicerca) .'%');
                })
                ->where('strutture_id', 'like', '%'.$this->store_id.'%')
                ->where('tipo', 'like', '%'.$this->tipo.'%')
                ->paginate(10),

            'listastore' => Strutture::filiali()->orderBy('nome')->get(),
        ]);
    }
}
