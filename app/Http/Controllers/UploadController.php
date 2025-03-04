<?php

namespace App\Http\Controllers;

use App\Jobs\ImportAppointmentsJob;
use App\Jobs\ImportClientsJob;
use App\Jobs\importPhonesJob;
use App\Jobs\ImportProformeJob;
use App\Jobs\ImportProveJob;
use App\Jobs\importStruttureJob;
use App\Jobs\ImportUserJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class UploadController extends Controller
{
    public function upload()
    {
        return view('upload.upload');
      //  return view('upload.caricaFile');
    }

    public function uploadAnagrafichePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        ImportClientsJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }

    public function uploadAppuntamentiPost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        ImportAppointmentsJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }

    public function uploadTelefonatePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        importPhonesJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }

    public function uploadStrutturePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file
        importStruttureJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }

    public function caricaStrutture()
    {
        ini_set('max_execution_time', 600); // 10 minuti
        ini_set('memory_limit', '2048M');

        $directoryPath = storage_path('app/private/temp'); // Specifica la directory
        if (!is_dir($directoryPath)) { // Verifica se la directory esiste
            \Storage::makeDirectory('/temp'); // Crea la directory se non esiste
        }

        Bus::batch([
            new importStruttureJob(),
            new ImportAppointmentsJob(),
            new ImportUserJob(),
            new ImportProveJob(),
            new ImportProformeJob(),
            new importPhonesJob(),
            new ImportClientsJob(),
        ])->dispatch();

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }
}
