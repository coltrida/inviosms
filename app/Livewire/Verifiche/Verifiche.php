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
    public $filialiConContattoChiamatoConAppuntamento;
    public $filialiConClientSenzaProforma;
    public $filialiConLeadConAppuntamenti;

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

    public function contattoChiamatoConAppuntamento()
    {
        $this->filialiConContattoChiamatoConAppuntamento = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Contatto Chiamato')
                    ->whereHas('appointments', function($a) {
                        $a->where('esito', 'Si è presentato');
                    });
            }])
            ->get();
    }

    public function clientiNoProforma()
    {
        $this->filialiConClientSenzaProforma = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Cliente')
                    ->whereDoesntHave('proformas')
                    ->whereDoesntHave('intermediari');
            }])
            ->get();
    }

    public function leadConAppuntamenti()
    {
        $this->filialiConLeadConAppuntamenti = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Lead')
                    ->whereHas('appointments', function($a) {
                        $a->where('esito', 'Si è presentato');
                    });
            }])
            ->get();
    }

    public function esporta()
    {
        $file = Excel::download(new DoppioniExport($this->result), 'estrai.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function esportaClientiNoAppuntamenti($idFiliale)
    {
        // Ricarica i dati prima di filtrare
        $unMeseFa = Carbon::now()->subMonths(1);
        $filialiConClienti = Strutture::filiali()
            ->with(['clients' => function($c) use($unMeseFa){
                $c->where('tipo', 'Cliente')
                    ->whereDoesntHave('appointments', function($a) use($unMeseFa){
                        $a->where('previsto', '>', $unMeseFa);
                    });
            }])
            ->get();

        $filialeDaStampare = $filialiConClienti->firstWhere('id', $idFiliale);

        $file = Excel::download(new DoppioniExport($filialeDaStampare->clients), 'clientiNoAppuntamenti.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function esportaContattoChiamatoConAppuntamento($idFiliale)
    {
        // Ricarica i dati prima di filtrare
        $filialiConClienti = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Contatto Chiamato')
                    ->whereHas('appointments', function($a) {
                        $a->where('esito', 'Si è presentato');
                    });
            }])
            ->get();

        $filialeDaStampare = $filialiConClienti->firstWhere('id', $idFiliale);

        $file = Excel::download(new DoppioniExport($filialeDaStampare->clients), 'contattiChiamatiConAppuntamenti.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function esportaLeadConAppuntamento($idFiliale)
    {
        // Ricarica i dati prima di filtrare
        $filialiConClienti = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Lead')
                    ->whereHas('appointments', function($a) {
                        $a->where('esito', 'Si è presentato');
                    });
            }])
            ->get();

        $filialeDaStampare = $filialiConClienti->firstWhere('id', $idFiliale);

        $file = Excel::download(new DoppioniExport($filialeDaStampare->clients), 'leadConAppuntamenti.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function esportaClientSenzaProforma($idFiliale)
    {
        // Ricarica i dati prima di filtrare
        $filialiConClienti = Strutture::filiali()
            ->with(['clients' => function($c) {
                $c->where('tipo', 'Cliente')
                    ->whereDoesntHave('proformas')
                    ->whereDoesntHave('intermediari');
            }])
            ->get();

        $filialeDaStampare = $filialiConClienti->firstWhere('id', $idFiliale);

        $file = Excel::download(new DoppioniExport($filialeDaStampare->clients), 'clientiSenzaProforma.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        return $file->deleteFileAfterSend(false);
    }

    public function render()
    {
        return view('livewire.verifiche.verifiche');
    }
}
