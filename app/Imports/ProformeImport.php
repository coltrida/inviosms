<?php

namespace App\Imports;

use App\Models\Proforma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class ProformeImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $users; // Qui salviamo i dati della tabella Users

    public function __construct()
    {
        DB::table('proformas')->truncate();

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
        if ($row['cliente_finale']){
          //  preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:\s*(\d+)\)/u', $row['cliente_finale'], $matches1);
            preg_match('/ID:(\d+)/', $row['cliente_finale'], $matches1);
        }
        if ($row['intermediario']){
         //   preg_match('/^[A-Za-z]{2}\s+([\p{L}\'-]+(?:\s+[\p{L}\'-]+)*)\s+((?:[\p{L}\'-]+\s*)+)\s+\(ID:\s*(\d+)\)/u', $row['intermediario'], $matches2);
            preg_match('/ID:(\d+)/', $row['intermediario'], $matches2);
        }

        $UserName = $row['audioprotesista'];

        // Cerchiamo l'ID dello store basandoci sul nome
        $userId = $this->users[$UserName] ?? null;

        return new Proforma([
            'id'  => $row['id'],
            'client_id'   => isset($matches1[1]) ? trim($matches1[1]) : null, // ID numerico estratto
            'intermediario_id'   => isset($matches2[1]) ? trim($matches2[1]) : null, // ID numerico estratto
            'user_id' => $userId,
            'cliente_finale'  => $row['cliente_finale'],
            'intermediario'  => $row['intermediario'],
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
