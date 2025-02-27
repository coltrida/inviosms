<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Strutture;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Illuminate\Support\Facades\DB;

class ClientImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
//class ClientImport implements ToCollection, WithHeadingRow
{

    private $rows = 0;

    protected $stores; // Qui salviamo i dati della tabella Store

    public function __construct()
    {
        // Leggiamo tutti i dati di Store una sola volta
        $this->stores = Strutture::all()->pluck('id', 'nome');
        // pluck('id', 'name') -> restituisce un array associativo [ 'Nome Store' => ID ]
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Verifica se il record esiste già nel database
        /*$existingRecord = Client::where([
                ['nome', $row['nome']],
                ['cognome', $row['cognome']],
                ['telefono', $row['numero_di_telefono']],
            ])->first(); // Ad esempio, controlla per email*/

        $existingRecord = Client::find($row['id']);

        // Se il record esiste, ignora la riga
        if ($existingRecord) {
            return null; // Salta questa riga
        }

        ++$this->rows;

        $storeName = $row['store'];


        // Cerchiamo l'ID dello store basandoci sul nome
        $storeId = $this->stores[$storeName] ?? null;
        //dd($this->stores);
        //dd($row);
        return new Client([
            'id'            => $row['id'],
            'tipo'          => $row['tipo'],
            'nome'          => $row['nome'],
            'cognome'       => $row['cognome'],
            'fullname'      => trim($row['cognome'].' '.$row['nome']),
            'email'         => $row['email'],
            'telefono'      => $row['numero_di_telefono'],
            'indirizzo'     => $row['indirizzo'],
            'citta'         => $row['citta'],
            'cap'           => $row['cap'],
            'provincia'     => $row['provincia'],
            'note'          => $row['note'],
            'created_at'    => $row['creato_il'],
            'updated_at'    => $row['aggiornato_il'],
            'strutture_id'  => $storeId,
        ]);
    }


    /*public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                'nome'     => $row['nome'] ?? null,
                'cognome'  => $row['cognome'] ?? null,
                'email'    => $row['email'] ?? null,
                'telefono' => $row['telefono'] ?? null,
                'indirizzo'=> $row['indirizzo'] ?? null,
                'citta'    => $row['citta'] ?? null,
                'cap'      => $row['cap'] ?? null,
                'provincia'=> $row['provincia'] ?? null,
            ];
            ++$this->rows;
        }

        // Inserisce tutti i dati in un'unica query
        DB::table('clients')->insert($data);
    }*/

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
