<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnagraficheExport implements FromCollection, WithHeadings
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->result;
    }

    public function headings(): array
    {
        return [
            'id',
            'tipo',
            'nome',
            'cognome',
            'fullname',
            'email',
            'telefono',
            'telefono2',
            'indirizzo',
            'città',
            'cap',
            'provincia',
            'note',
            'canale primario',
            'canale secondario',
            'struttura_id',
            'data creazione',
            'data aggiornamento',
        ];
    }
}
