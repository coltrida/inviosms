<?php

namespace App\Livewire\Home;

use App\Exports\DoppioniExport;
use App\Models\Client;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class VisualizzaEstraiquattro extends Component
{
    public $visualizzaBool = false;
    public $clients;

    #[On('visualizzaCaps')]
    public function visualizza($caps)
    {
        $this->clients = Client::whereIn('cap', $caps)->get();

        $this->visualizzaBool = true;
    }

    public function esportaClient()
    {
        $file = Excel::download(new DoppioniExport($this->clients), 'anagrafiche per recapito.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.home.visualizza-estraiquattro');
    }
}
