<?php

namespace App\Livewire\Clienti;

use App\Exports\ClientsExport;
use App\Models\Client;
use App\Models\Strutture;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ListaClienti extends Component
{
    use WithPagination;

    #[Url]
    public ?string $testoRicerca = '';

    public $store_id = null;
    public $tipo = null;

    public function eliminaRicerca()
    {
        $this->testoRicerca = '';
    }

    public function esporta()
    {
        // Rifaccio la query per esportare tutti i dati senza paginazione
        $dati = Client::withCount('appointments', 'phones')
            ->when($this->testoRicerca, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . strtoupper($this->testoRicerca) . '%')
                        ->orWhere('cognome', 'like', '%' . strtoupper($this->testoRicerca) . '%')
                        ->orWhere('fullname', 'like', '%' . strtoupper($this->testoRicerca) . '%');
                });
            })
            ->when($this->store_id, fn ($query) => $query->where('strutture_id', $this->store_id))
            ->when($this->tipo, fn ($query) => $query->where('tipo', $this->tipo))
            ->get(); // Rimuovo paginazione per esportazione completa

        return Excel::download(new ClientsExport($dati), 'clients.xlsx')->deleteFileAfterSend(false);
    }

    public function resetRicerca()
    {
        $this->testoRicerca = '';
        $this->tipo = '';
        $this->store_id = '';
    }

    public function render()
    {
        $clients = Client::withCount('appointments', 'phones')
            ->when($this->testoRicerca, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . strtoupper($this->testoRicerca) . '%')
                        ->orWhere('cognome', 'like', '%' . strtoupper($this->testoRicerca) . '%')
                        ->orWhere('fullname', 'like', '%' . strtoupper($this->testoRicerca) . '%');
                });
            })
            ->when($this->store_id, fn ($query) => $query->where('strutture_id', $this->store_id))
            ->when($this->tipo, fn ($query) => $query->where('tipo', $this->tipo))
            ->paginate(7);

        return view('livewire.clienti.lista-clienti', [
            'clients' => $clients,
            'listastore' => Strutture::filiali()->orderBy('nome')->get(),
        ]);
    }
}
