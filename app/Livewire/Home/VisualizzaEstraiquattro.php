<?php

namespace App\Livewire\Home;

use App\Exports\AnagraficheExport;
use App\Exports\DoppioniExport;
use App\Exports\NominativiRecapitoExport;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Phone;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class VisualizzaEstraiquattro extends Component
{
    public $visualizzaBool = false;
    public $clients;
    public $nomeRecapito;

    #[On('visualizzaCaps')]
    public function visualizza($caps, $nomeRecapito)
    {
        $this->nomeRecapito = $nomeRecapito;

        $this->clients = Client::select(['id','tipo', 'nome', 'cognome', 'fullname', 'telefono', 'telefono2', 'indirizzo', 'citta', 'cap', 'provincia'])
        ->whereIn('cap', $caps)->get();

        // Recupera tutti i client_id presenti in Appointment indietro di x mesi
        $fullnamesInAppointments = Appointment::where('previsto', '>=', Carbon::now()->subMonths(3))
            ->pluck('client_id')->toArray();

        // Recupera tutti i client_id presenti in telefonate indietro di x mesi
        $fullnamesInPhones = Phone::where('chiamato', '>=', Carbon::now()->subMonths(3))
            ->pluck('client_id')->toArray();

        // Rimuove dal risultato i clienti il cui fullname è già in Appointments
        $this->clients = $this->clients->reject(function ($client) use ($fullnamesInAppointments) {
            return in_array($client->id, $fullnamesInAppointments);
        });

        // Rimuove dal risultato i clienti il cui fullname è già in Phones
        $this->clients = $this->clients->reject(function ($client) use ($fullnamesInPhones) {
            return in_array($client->id, $fullnamesInPhones);
        });

        $this->visualizzaBool = true;
    }

    public function esportaClient()
    {
        $export = new NominativiRecapitoExport($this->clients);

        $fileName = $this->nomeRecapito . '.xlsx';

        return Excel::download($export, $fileName)
            ->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.home.visualizza-estraiquattro');
    }
}

