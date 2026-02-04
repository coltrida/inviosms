<?php

namespace App\Imports;

use App\Models\Disponibilita;
use App\Models\Strutture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class DisponibilitaImport implements ToModel, WithSkipDuplicates, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    public function __construct()
    {
        DB::table('disponibilitas')->truncate();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //dd($row['audioprotesista']);

        if ((User::where('name',$row['audioprotesista'])->first()) && Strutture::where('nome', $row['store'])->first()){
            $giorno = Carbon::make($row['previstoa_dalle_ore']);
            return new Disponibilita([
                'user_id' => User::where('name',$row['audioprotesista'])->first()->id,
                'strutture_id' => Strutture::where('nome',$row['store'])->first()->id,
                'previsto' => $giorno->format('Y-m-d'),
                'mese' => $giorno->month,
                'anno' => $giorno->year,
            ]);
        }

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
