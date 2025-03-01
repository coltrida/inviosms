<?php

namespace App\Http\Controllers;

use App\Imports\PhoneImport;
use App\Imports\StrutturaImport;
use App\Jobs\ImportAppointmentsJob;
use App\Jobs\ImportClientsJob;
use App\Jobs\importPhonesJob;
use App\Jobs\importStruttureJob;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\directoryExists;
use function PHPUnit\Framework\fileExists;

class UploadController extends Controller
{
    public function upload()
    {
      //  return view('upload.upload');
        return view('upload.caricaFile');
    }

    public function uploadAnagrafichePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');


        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file
        // dd(storage_path("app/private/$filePath"));

        ImportClientsJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
        //  session()->flash('message', $import->getRowCount());
        //   return redirect()->back();
    }

    public function uploadAppuntamentiPost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        ImportAppointmentsJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');

        /*$import = new ClientImport;
        Excel::import($import, $request->file('fileExcel'));
        session()->flash('message', $import->getRowCount());
        return redirect()->back();*/
    }

    public function uploadTelefonatePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        importPhonesJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');

        /*$import = new ClientImport;
        Excel::import($import, $request->file('fileExcel'));
        session()->flash('message', $import->getRowCount());
        return redirect()->back();*/
    }

    public function uploadStrutturePost(Request $request)
    {
        ini_set('max_execution_time', 600); // 5 minuti
        ini_set('memory_limit', '2048M');

        $file = $request->file('fileExcel');  // Carica il file
        //dd($file);
        $filePath = $file->store('temp'); // Salva temporaneamente il file
        //dd($filePath);
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

        Bus::chain([
            new importStruttureJob(),
            new ImportClientsJob(),
            new ImportAppointmentsJob(),
            new importPhonesJob(),
        ])->dispatch();

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }
}
