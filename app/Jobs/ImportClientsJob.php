<?php

namespace App\Jobs;

use App\Events\ImportCompleted;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    public $timeout = 3600; // 1 ora

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new ClientImport, $this->filePath);

        $path = storage_path('app/private/temp');
        \File::deleteDirectory($path);

        // Emetti l'evento per il frontend
        broadcast(new ImportCompleted());
    }
}
