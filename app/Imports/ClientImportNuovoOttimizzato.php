<?php

namespace App\Imports;

use App\Models\Strutture;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts; // NUOVO!

class ClientImportNuovoOttimizzato implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $stores;

    // Il costruttore riceve i dati precaricati dal Job, non li carica da sé.
    public function __construct(array $stores)
    {
        $this->stores = $stores;
        // La disabilitazione del log delle query DEVE avvenire all'inizio della handle() del Job
        // per evitare side effect. Maatwebsite lo fa automaticamente per i Job.


        /*if (self::$isFirstRun) {
            // È essenziale usare DB::connection()->disableQueryLog() per file grandi
            DB::connection()->disableQueryLog();

            // Leggiamo tutti i dati di Store una sola volta
            $this->stores = Strutture::all()->pluck('id', 'nome');
            // pluck('id', 'name') -> restituisce un array associativo [ 'Nome Store' => ID ]

            // Eseguiamo il TRUNCATE qui, ma solo la prima volta
            DB::table('clients')->truncate();
            self::$isFirstRun = false;
        }*/
    }

    /**
     * @param Collection $rows
     * Ora salviamo il blocco di dati intero in una singola query di massa.
     */
    public function collection(Collection $rows)
    {
        $dataToInsert = [];

        // Mappa la collezione in un array che corrisponda ai campi del database
        foreach ($rows as $row) {
            $storeName = $row['store'];

            // Cerchiamo l'ID dello store basandoci sul nome
            $storeId = $this->stores[$storeName] ?? null;

            $dataToInsert[] = [
                'id'                    => $row['id'],
                'tipo'                  => $row['tipo'],
                'nome'                  => $row['nome'] != '' ? $row['nome'] : '...',
                'cognome'               => $row['cognome'] != '' ? $row['cognome'] : '...',
                'fullname'              => trim($row['cognome'].' '.$row['nome']),
                'email'                 => $row['email'],
                'telefono'              => $row['numero_di_telefono'],
                'telefono2'             => $row['numero_di_telefono_alternativo'],
                'indirizzo'             => $row['indirizzo'],
                'citta'                 => $row['citta'],
                'cap'                   => $row['cap'],
                'provincia'             => $row['provincia'],
                'note'                  => $row['note'],
                'created_at'            => $row['creato_il'],
                'updated_at'            => $row['aggiornato_il'],
                'canalePrimario'        => $row['canale_primario'],
                'canaleSecondario'      => $row['canale_secondario'],
                'strutture_id'          => $storeId,
            ];
        }

        if (!empty($dataToInsert)) {
            // L'inserimento massivo è super veloce e avviene all'interno della transazione del chunk.
            DB::table('clients')->insert($dataToInsert);
        }
    }

    // Aumentiamo la dimensione del chunk per ridurre l'overhead. Prova 5000 o 10000.
    public function chunkSize(): int
    {
        return 3000;
    }

    // Con ToCollection/DB::insert, WithBatchInserts non è strettamente necessaria,
    // ma la includiamo per coerenza. Se usassi ToModel, sarebbe vitale.
    public function batchSize(): int
    {
        // Deve essere uguale o maggiore di chunkSize per ToCollection
        return 3000;
    }
}

