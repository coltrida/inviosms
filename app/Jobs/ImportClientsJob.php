<?php

namespace App\Jobs;

use App\Events\ImportCompleted;
use App\Imports\ClientImportNuovo;
use Illuminate\Bus\Batchable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels, Batchable;

    public $timeout = 0; // 1 ora

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = '/home/vagrant/CRM/ii.xlsx';
        $originalPath = file_get_contents($path); // Leggi il contenuto del file
        file_put_contents(storage_path('app/private/temp/ii.xlsx'), $originalPath); // Scrivi nella destinazione

        Excel::import(new ClientImportNuovo, storage_path("app/private/temp/ii.xlsx"));

        // Puliamo la cartella temporanea
        $path = storage_path('app/private/temp');
        \File::deleteDirectory($path);

        // Emetti l'evento per il frontend
        broadcast(new ImportCompleted());
    }
}
