<?php

namespace App\Livewire\Home;

use App\Exports\AnagraficheExport;
use App\Exports\DoppioniExport;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Disponibilita;
use App\Models\Phone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class VisualizzaEstraisette extends Component
{

    #[On('visuSette')]
    public function visualizza()
    {
        // Rimuove il limite dei 30 secondi per questa funzione
        set_time_limit(0);

        if (Storage::disk('public')->exists('export_disponibilita_mese.zip')){
            Storage::disk('public')->delete('export_disponibilita_mese.zip');
        }

        $oggi = Carbon::now();
        $treMesiFa = Carbon::now()->subMonths(3);

        // 1. Ottimizzazione Query ID da escludere
        // Recupera tutti i client_id presenti in Appointment indietro di x mesi
        $fullnamesInAppointments = Appointment::where('previsto', '>=', $treMesiFa)
            ->pluck('client_id')->toArray();

        // Recupera tutti i client_id presenti in telefonate indietro di x mesi
        $fullnamesInPhones = Phone::where('chiamato', '>=', $treMesiFa)
            ->pluck('client_id')->toArray();


        //dd($excludeClientIds);

        $disponibilitaDelMese = Disponibilita::whereHas('struttura', function ($s) {
            $s->whereIn('tipo', ['Recapito', 'Screening']);
        })
            ->where('mese', $oggi->month)
            ->where('anno', $oggi->year)
            ->with('struttura.caps') // Eager loading per evitare altre query nel loop
            ->get()
            ->unique('strutture_id')
            ->values();

        if ($disponibilitaDelMese->isEmpty()) {
            // Opzionale: invia un messaggio d'errore se non c'è nulla da scaricare
            return;
        }

        $zipFileName = 'export_disponibilita_mese' . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive();

        $filesToCleanup = [];

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($disponibilitaDelMese as $disponibilita) {
                $nomeFileNuovi = 'temp_nuovi_' . $disponibilita->struttura->nome . '_' . $disponibilita->id . '.xlsx';
                $nomeFilePc = 'temp_pc_' . $disponibilita->struttura->nome . '_' . $disponibilita->id . '.xlsx';

                $caps = $disponibilita->struttura->caps->pluck('cap')->all();

                // 2. Filtro eseguito direttamente nel Database (molto più veloce)
                $clients = Client::whereIn('cap', $caps)
                    ->get();

                // Rimuove dal risultato i clienti il cui fullname è già in Appointments
                $clients = $clients->reject(function ($client) use ($fullnamesInAppointments) {
                    return in_array($client->id, $fullnamesInAppointments);
                });

                // Rimuove dal risultato i clienti il cui fullname è già in Phones
                $clients = $clients->reject(function ($client) use ($fullnamesInPhones) {
                    return in_array($client->id, $fullnamesInPhones);
                });

                // Filtro per Clienti Nuovi (tipo 'lead' oppure vuoto/null)
                $clientNuovi = $clients->filter(function ($client) {
                    return in_array($client->tipo, ['Lead', 'Contatto Chiamato']) || empty($client->tipo);
                });

                // Filtro per Clienti PC (tipo 'pc' oppure 'prematuro')
                $clientPc = $clients->filter(function ($client) {
                    return in_array($client->tipo, ['Potenziale Cliente', 'prematuro']);
                });


                // 3. Salvataggio e recupero percorso corretto
                Excel::store(new AnagraficheExport($clientNuovi), $nomeFileNuovi, 'local');
                $fullExcelPathNuovi = Storage::disk('local')->path($nomeFileNuovi);

                if (file_exists($fullExcelPathNuovi)) {
                    if (count($clientNuovi) > 0){
                        $zip->addFile($fullExcelPathNuovi, $disponibilita->struttura->nome. ' - ' .$disponibilita->previsto. ' - nuovi.xlsx');
                    }
                    $filesToCleanup[] = $fullExcelPathNuovi;
                }

                // 3. Salvataggio e recupero percorso corretto
                Excel::store(new AnagraficheExport($clientPc), $nomeFilePc, 'local');
                $fullExcelPathPc = Storage::disk('local')->path($nomeFilePc);

                if (file_exists($fullExcelPathPc)) {
                    if (count($clientPc) > 0) {
                        $zip->addFile($fullExcelPathPc, $disponibilita->struttura->nome . ' - ' . $disponibilita->previsto . ' - pc.xlsx');
                    }
                    $filesToCleanup[] = $fullExcelPathPc;
                }

            }
            $zip->close();

            // 3. Pulizia dei file Excel singoli
            foreach ($filesToCleanup as $file) {
                if (file_exists($file)) unlink($file);
            }
        }

        // 4. Invio download
        if (file_exists($zipPath)) {
            return response()->download($zipPath);
        }
    }

    public function render()
    {
        return view('livewire.home.visualizza-estraisette');
    }
}
