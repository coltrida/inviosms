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
            'Telefono',
            'indirizzo',
            'citta',
            'Cap',
            'store_id'
        ];
    }
}
