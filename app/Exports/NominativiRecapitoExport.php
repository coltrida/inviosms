<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NominativiRecapitoExport implements WithMultipleSheets
{
    protected $clients;

    public function __construct($clients)
    {
        // Riceviamo la collezione completa dei client dal componente Livewire
        $this->clients = $clients;
    }

    /**
     * Definiamo i fogli da creare
     */
    public function sheets(): array
    {
        $sheets = [];

        // Foglio 1: Lead (tipo è 'Lead' OPPURE tipo è null)
        $leads = $this->clients->filter(function ($client) {
            return $client->tipo === 'Lead' || is_null($client->tipo);
        });
        $sheets[] = new ClientSheetExport($leads, 'lead');

        // Foglio 2: PC (Possibile Cliente)
        $pc = $this->clients->filter(function ($client) {
            // Dobbiamo confrontare esplicitamente il tipo con entrambi i valori
            return $client->tipo === 'Potenziale Cliente' || $client->tipo === 'prematuro';
        });
        $sheets[] = new ClientSheetExport($pc, 'pc');

        return $sheets;
    }
}

