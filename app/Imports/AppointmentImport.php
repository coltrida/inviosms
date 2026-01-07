<?php

namespace App\Imports;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class AppointmentImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $rows = 0;

    public function __construct()
    {
        DB::table('appointments')->truncate();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Verifica se il record esiste già nel database
        /*$existingRecord = Appointment::where([
            ['nome', $row['nome']],
            ['cognome', $row['cognome']],
            ['previsto', $row['previsto']],
        ])->first(); // Ad esempio, controlla per email

        // Se il record esiste, ignora la riga
        if ($existingRecord) {
            return null; // Salta questa riga
        }*/

        preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:\s*(\d+)\)/u', $row['contatto'], $matches);

        $idClient = isset($matches[3]) ? trim($matches[3]) : null;

        preg_match('/\(ID:(\d+)\)/', $row['contatto'], $matches2);
        // $matches2[1] contiene il valore catturato dalle parentesi tonde (\d+)
        $secondoTentativoEstrazioneIdClient = isset($matches2[1]) ? trim($matches2[1]) : null;


        if (!$idClient && $secondoTentativoEstrazioneIdClient){
            $idClient = $secondoTentativoEstrazioneIdClient;
        }

        return new Appointment([
            'contatto'  => $row['contatto'],
            'nome'      => isset($matches[2]) ? trim($matches[2]) : null, // Nome con lettere accentate
            'cognome'   => isset($matches[1]) ? trim($matches[1]) : null, // Cognome con lettere accentate
            'fullname'  => (isset($matches[1]) ? trim($matches[1]) : '') . ' ' . (isset($matches[2]) ? trim($matches[2]) : ''), // Cognome + Nome
            'client_id'   => $idClient, // ID numerico estratto
            'tipo'      => $row['tipo'],
            'previsto'  => $row['previstoa_dalle_ore'],
            'esito'     => $row['modelsappointmentsfieldsappointment_result'],
            'note'      => $row['note'],
        ]);




    }

    public function getRowCount(): int
    {
        return $this->rows;
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
