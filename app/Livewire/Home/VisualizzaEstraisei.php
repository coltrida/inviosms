<?php

namespace App\Livewire\Home;

use App\Exports\DoppioniExport;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Phone;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class VisualizzaEstraisei extends Component
{
    public $visualizzaBool = false;
    public $appointments;
    public $phones;

    #[On('visuSei')]
    public function visualizza()
    {
        $this->appointments = Appointment::with('client.strutture')
            ->select('client_id', 'esito', 'note')
            ->where('esito', 'Si è presentato')
            ->where('note', 'like', '%ipoacus%') // Cattura sia ipoacus-ia che ipoacus-ico
            ->whereHas('client', function ($query) {
                $query->where('Tipo', '!=', 'Cliente');
            })
            ->get()
            ->sortBy(function($appointment) {
                return $appointment->client->strutture_id;
            }); // Ordina in memoria

        $this->phones = Phone::with('client')
            ->select('client_id', 'chiamato', 'note')
            ->where('note', 'like', '%ipoacus%') // Cattura sia ipoacus-ia che ipoacus-ico
            ->whereHas('client', function ($query) {
                $query->where('Tipo', '!=', 'Cliente');
            })
            ->get()
            ->sortBy(function($phone) {
                return $phone->client->strutture_id;
            }); // Ordina in memoria

        $this->visualizzaBool = true;
    }

    public function esportaClient()
    {
        $file = Excel::download(new DoppioniExport($this->clients), 'anagrafiche per recapito.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.home.visualizza-estraisei');
    }
}
