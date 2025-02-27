<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class ClientsExport implements FromCollection
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
}
