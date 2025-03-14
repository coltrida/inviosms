<?php

namespace App\Imports;

use App\Models\Prova;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class ProveImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    protected $users; // Qui salviamo i dati della tabella Users

    public function __construct()
    {
        DB::table('provas')->truncate();

        // Leggiamo tutti i dati di Users una sola volta
        $this->users = User::all()->pluck('id', 'name');
        // pluck('id', 'name') -> restituisce un array associativo [ 'Nome User' => ID ]
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:\s*(\d+)\)/u', $row['cliente_finale'], $matches);

        $UserName = $row['audioprotesista'];

        // Cerchiamo l'ID dello store basandoci sul nome
        $userId = $this->users[$UserName] ?? null;

        return new Prova([
            'id'  => $row['id'],
            'client_id'   => isset($matches[3]) ? trim($matches[3]) : null, // ID numerico estratto
            'user_id' => $userId,
            'contatto'  => $row['cliente_finale'],
            'stato'  => $row['stato_documento'],
            'dataDocumento'  => $row['data_documento'],
            'totale'  => $row['valore_totale'],
            'canalePrimario'  => $row['canale_primario'],
            'canaleSecondario'  => $row['canale_secondario'],
            'anno'  => Carbon::make($row['data_documento'])->year,
            'mese'  => Carbon::make($row['data_documento'])->month
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
