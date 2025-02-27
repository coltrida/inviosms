<?php

namespace App\Http\Controllers;

use App\Jobs\ImportAppointmentsJob;
use App\Jobs\ImportClientsJob;
use App\Jobs\importPhonesJob;
use App\Jobs\importStruttureJob;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload()
    {
        return view('upload.upload');
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
        $filePath = $file->store('temp'); // Salva temporaneamente il file

        importStruttureJob::dispatch(storage_path("app/private/$filePath")); // Avvia il job in coda

        return back()->with('message', 'Importazione avviata! Sarai notificato al termine.');
    }
}
