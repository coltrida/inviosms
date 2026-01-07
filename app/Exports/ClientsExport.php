<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
        //dd($this->result[0]);
    }

    public function collection()
    {
        return $this->result;
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Full Name',
            'Nome',
            'Cognome',
            'Telefono',
            'indirizzo',
            'citta',
            'Cap',
            'canale Primario',
            'canale Secondario',
            'creato il',
            'store_id'
        ];
    }
}
