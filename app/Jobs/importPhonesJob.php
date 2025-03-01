<?php

namespace App\Jobs;

use App\Events\ImportCompleted;
use App\Imports\PhoneImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class importPhonesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels;

    public $timeout = 3600; // 1 ora

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
        $path = '/home/vagrant/CRM/tt.xlsx';
        $originalPath = file_get_contents($path); // Leggi il contenuto del file
        file_put_contents(storage_path('app/private/temp/tt.xlsx'), $originalPath); // Scrivi nella destinazione

        Excel::import(new PhoneImport, storage_path('app/private/temp/tt.xlsx'));

        $path = storage_path('app/private/temp');
        \File::deleteDirectory($path);

        // Emetti l'evento per il frontend
        broadcast(new ImportCompleted());
    }
}
