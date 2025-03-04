<?php

namespace App\Livewire\Verifiche;

use App\Exports\DoppioniExport;
use App\Models\Client;
use App\Models\Strutture;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Verifiche extends Component
{
    public $result;
    public $filialiConClientiSenzaAppuntamenti;

    public function doppioni()
    {
        $this->result = Client::with('strutture')->whereIn(DB::raw('(nome, cognome, citta)'), function ($query) {
            $query->selectRaw('nome, cognome, citta')
                ->from('clients')
                ->groupBy('nome', 'cognome', 'citta')
                ->havingRaw('COUNT(*) > 1');
        })->orderBy('strutture_id')->orderBy('citta')->orderBy('cognome')->get();
    }

    public function senzaNumero()
    {
        $this->result = Client::where('telefono', null)->orWhere('telefono', '0')->orWhere('telefono', '-')
        ->orderBy('strutture_id')->orderBy('citta')->get();
    }

    public function senzaStore()
    {
        $this->result = Client::where('strutture_id', null)
            ->orderBy('citta')->get();
    }

    public function clientiNoAppuntamento()
    {
        $unMeseFa = Carbon::now()->subMonths(1);
        $this->filialiConClientiSenzaAppuntamenti = Strutture::filiali()
            ->with(['clients' => function($c) use($unMeseFa){
                $c->where('tipo', 'Cliente')
                    ->whereDoesntHave('appointments', function($a) use($unMeseFa){
                        $a->where('previsto', '>', $unMeseFa);
                    });
            }])
            ->get();
    }

    public function esporta()
    {
        $file = Excel::download(new DoppioniExport($this->result), 'estrai.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.verifiche.verifiche');
    }
}
