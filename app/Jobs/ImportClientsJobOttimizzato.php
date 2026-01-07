<?php

namespace App\Jobs;

use App\Imports\ClientImportNuovoOttimizzato;
use App\Models\Strutture; // Importa il modello Strutture per pre-caricare i dati
use Illuminate\Bus\Batchable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportClientsJobOttimizzato implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels, Batchable;

    // Timeout infinito per i Job lunghi
    public $timeout = 0;

    /**
     * Esegue il job.
     * Questa funzione gestisce il setup (TRUNCATE, pre-caricamento) e avvia l'importazione.
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $sourcePath = '/home/vagrant/CRM/ii.xlsx';
        $tempPath = 'private/temp/ii.xlsx';
        $fullTempPath = storage_path("app/$tempPath");

        try {
            // 1. TRUNCATE DATABASE (DDL)
            // Eseguito qui, al di fuori del ciclo di importazione Maatwebsite,
            // per evitare il problema di "no active transaction".
            DB::table('clients')->truncate();

            // 2. Precaricamento dati (Strutture)
            // Disabilitiamo il log delle query per alleggerire l'operazione di fetching massivo.
            DB::connection()->disableQueryLog();
            // Precarichiamo i dati necessari (ID dello store mappato al nome dello store)
            $stores = Strutture::all()->pluck('id', 'nome')->toArray();

            // 3. Copia del file (Logica mantenuta per coerenza col tuo codice)
            if (!File::exists(dirname($fullTempPath))) {
                File::makeDirectory(dirname($fullTempPath), 0755, true, true);
            }
            $originalPath = file_get_contents($sourcePath); // Legge il contenuto del file
            file_put_contents($fullTempPath, $originalPath); // Scrivi nella destinazione

            // 4. Avvio l'importazione
            // Passiamo i dati delle strutture pre-caricati al costruttore della classe di importazione.
            Excel::import(new ClientImportNuovoOttimizzato($stores), $fullTempPath);

        } catch (\Exception $e) {
            \Log::error("Errore fatale Job Clients: " . $e->getMessage());
            $this->fail($e);
            return;
        } finally {
            // Pulizia
            if (File::exists($fullTempPath)) {
                File::delete($fullTempPath);
            }
        }
    }
}
