<?php

namespace App\Jobs;

use App\Events\ImportCompleted;
use App\Imports\AppointmentImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportAppointmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels;

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
        Excel::import(new AppointmentImport, $this->filePath);

        $path = storage_path('app/private/temp');
        \File::deleteDirectory($path);

        // Emetti l'evento per il frontend
        broadcast(new ImportCompleted());

    }
}
