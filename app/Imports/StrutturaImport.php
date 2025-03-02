<?php

namespace App\Imports;

use App\Models\Strutture;
use App\Models\Strutturecap;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class StrutturaImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    public function __construct()
    {
        DB::table('struttures')->truncate();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        $struttura = new Strutture([
            'id'  => $row['id'],
            'nome'  => $row['nome'],
            'indirizzo'  => $row['indirizzo'],
            'citta'  => $row['citta'],
            'provincia'  => $row['provincia'],
            'cap'  => $row['cap'],
            'tipo'  => $row['tipo'],
        ]);

        if ($row['cap']){
            Strutturecap::create([
                'strutture_id' => $row['id'],
                'cap' => $row['cap']
            ]);
        }


        return $struttura;
    }

    // Importa i dati in batch da 100 righe per volta
    public function batchSize(): int
    {
        return 1000;
    }

    // Legge il file in chunk da 1000 righe per volta
    public function chunkSize(): int
    {
        return 1000;
    }
}
