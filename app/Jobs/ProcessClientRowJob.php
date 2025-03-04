<?php

namespace App\Jobs;

use App\Models\Strutture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;

class ProcessClientRowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    protected $stores; // Qui salviamo i dati della tabella Store

    public function __construct($rows)
    {
        $this->rows = $rows;

        // Leggiamo tutti i dati di Store una sola volta
        $this->stores = Strutture::all()->pluck('id', 'nome');
        // pluck('id', 'name') -> restituisce un array associativo [ 'Nome Store' => ID ]
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->rows as $row) {

            $storeName = $row['store'];

            // Cerchiamo l'ID dello store basandoci sul nome
            $storeId = $this->stores[$storeName] ?? null;
            try {
            Client::create([
                'id'                    => $row['id'],
                'tipo'                  => $row['tipo'],
                'nome'                  => $row['nome'],
                'cognome'               => $row['cognome'],
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
            ]);
            } catch (\Throwable $e) {
                // Log dell'errore
                \Log::error("Errore nel Job ProcessClientRowJob: " . $e->getMessage());

                // Indica a Laravel che il Job è fallito e non deve essere saltato
                $this->fail($e);
            }
        }
    }
}
