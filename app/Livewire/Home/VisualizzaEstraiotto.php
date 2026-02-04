<?php

namespace App\Livewire\Home;

use App\Exports\AnagraficheExport;
use App\Models\Proforma;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class VisualizzaEstraiotto extends Component
{

    public $visualizzaBool = false;
    public $clientsAsl;

    #[On('visuOtto')]
    public function visualizza()
    {
        $oggi = Carbon::now();
        $quattroAnniFa = $oggi->subYears(3);

        $this->clientsAsl = Proforma::with(['interm' => function($i){
            $i->with('strutture');
        }])
            ->whereHas('interm')
            ->where('dataDocumento', '<', $quattroAnniFa)
            ->get();

        $this->visualizzaBool = true;
    }

    public function esportaClient()
    {
        $file = Excel::download(new AnagraficheExport($this->clientsAsl), 'scadenze Asl.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.home.visualizza-estraiotto');
    }
}
