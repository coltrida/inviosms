<?php

namespace App\Imports;

use App\Models\Phone;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class PhoneImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    public function __construct()
    {
        DB::table('phones')->truncate();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        //preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:/u', $row['contatti'], $matches);
        preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:\s*(\d+)/u', $row['contatti'], $matches);

        $idClient = isset($matches[3]) ? trim($matches[3]) : null;

        preg_match('/\(ID:(\d+)\)/', $row['contatti'], $matches2);
        // $matches2[1] contiene il valore catturato dalle parentesi tonde (\d+)
        $secondoTentativoEstrazioneIdClient = isset($matches2[1]) ? trim($matches2[1]) : null;


        if (!$idClient && $secondoTentativoEstrazioneIdClient){
            $idClient = $secondoTentativoEstrazioneIdClient;
        }

        //dd($row);
        return new Phone([
            'contatto'  => $row['contatti'],
            'fullname'   => (isset($matches[1]) ? trim($matches[1]) : null) . ' ' . (isset($matches[2]) ? trim($matches[2]) : null), // Cognome + Nome
            'client_id'  => $idClient, // ID numerico
            'note'  => $row['note'],
            'chiamato'  => $row['chiamatoa_il'],
            'esito'  => $row['esito_chiamata'],
        ]);
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
