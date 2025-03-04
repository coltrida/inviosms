<?php

namespace App\Imports;

use App\Jobs\ProcessClientRowJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImportNuovo implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function __construct()
    {
        DB::table('clients')->truncate();
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // Suddivide il file in blocchi da 100 righe
        foreach ($rows->chunk(500) as $chunk) {
            // Invia ogni blocco a un Job separato
            ProcessClientRowJob::dispatch($chunk);
        }
    }

    // Indica di leggere il file a blocchi di 1000 righe
    public function chunkSize(): int
    {
        return 1000;
    }
}
