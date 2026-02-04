<?php

namespace App\Http\Controllers;

use App\Exports\AnagraficheExport;
use App\Exports\ClientsExport;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Phone;
use App\Models\Strutture;
use Carbon\Carbon;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FrontController extends Controller
{

    public function inizio()
    {
        return view('inizio', [
            'filiali' => Strutture::filiali()->orderBy('nome')->get(),
        ]);
    }

    public function estraiuno(Request $request)
    {
        $result = Client::select(['id', 'tipo', 'nome', 'cognome', 'fullname', 'email', 'telefono', 'telefono2', 'indirizzo', 'citta', 'cap', 'provincia', 'note', 'canalePrimario', 'canaleSecondario', 'created_at', 'strutture_id'])
            ->where('cap', $request->cap)
            ->when($request->tipo, fn ($query) => $query->where('tipo', $request->tipo))
            ->when($request->telefono, fn ($query) => $query->where('telefono', 'like', $request->telefono.'%'))
            ->get();

        if ($request->mesiPassati){
            // Recupera tutti i fullname presenti in Appointment indietro di x mesi
            $fullnamesInAppointments = Appointment::where('previsto', '>=', Carbon::now()->subMonths($request->mesiPassati))
                ->pluck('fullname')->toArray();

            // Recupera tutti i fullname presenti in telefonate indietro di x mesi
            $fullnamesInPhones = Phone::where('chiamato', '>=', Carbon::now()->subMonths($request->mesiPassati))
                ->pluck('fullname')->toArray();

            // Rimuove dal risultato i clienti il cui fullname è già in Appointments
            $result = $result->reject(function ($client) use ($fullnamesInAppointments) {
                return in_array($client->fullname, $fullnamesInAppointments);
            });

            // Rimuove dal risultato i clienti il cui fullname è già in Phones
            $result = $result->reject(function ($client) use ($fullnamesInPhones) {
                return in_array($client->fullname, $fullnamesInPhones);
            });
        }

        $file = Excel::download(new AnagraficheExport($result), 'estrai.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        return $file->deleteFileAfterSend(false);
    }

    public function estraidue(Request $request)
    {
       $clients = Client::select('tipo', 'fullname', 'telefono', 'citta')
            ->where([
                ['tipo', $request->tipo],
                ['citta', $request->citta],
            ])
            ->limit(500)->get();
        $appointments = Appointment::select('fullname', 'esito', 'previsto')
            ->where('previsto', '>', Carbon::now()->subMonth(2))
            ->get();

        $result = Gemini::geminiPro()->generateContent("Analizza i seguenti dati del database: la tabella " .
            $clients->toJson(). " ed incrocia i dati con la tabella ". $appointments->toJson() .
            ". Restituiscimi i primi ". $request->numero .
            " elementi della tabella clients che non sono presenti nella tabella appointments: tipo, fullname, telefono, e città, di ogni record, in forma di tabella");

    /*    $appointments = Appointment::select('fullname', 'esito', 'previsto', 'note')
            ->get();

        $result = Gemini::geminiProFlash()->generateContent("Analizza i seguenti dati del database: la tabella " .
            $appointments->toJson(). " e restituisci i valori in cui nelle note sono presenti delle informazioni interessanti, in cui si capisce che c'è una ipoacusia, e in cui si capisce che ha necessità di utilizzare apparecchi acustici ");
*/

        // 1. Rimuovi la prima riga e le righe di separazione
        $stringa = preg_replace('/\| Tipo \| Fullname \| Telefono \| Città \|\\n\|---\|---\|---\\n/', '', $result->text());
        //dd($stringa);
        // 2. Dividi la stringa in righe
        $righe = explode("\n", $stringa);

        // 3. Crea l'array
        $dati = [];
        foreach ($righe as $riga) {
            $elementi = explode("|", $riga);
            //dd($elementi);
            $dati[] = [
                'Tipo' => trim($elementi[1]),
                'Fullname' => trim($elementi[2]),
                'Telefono' => trim($elementi[3]),
                'Città' => trim($elementi[4])
            ];
        }

        // Chiama la view e gli passa i dati
        return view('inizio', [
            'dati' => $dati
        ]);
    }

    public function estraitre(Request $request)
    {
        if ($request->mesiPassati == 'mai' || $request->mesiPassati == null) {
            // Recupera le anagrafiche senza neanche una chiamata e senza un appuntamento
            $result = Client::select(['tipo', 'fullname', 'nome', 'cognome', 'telefono', 'indirizzo', 'citta', 'cap', 'canalePrimario', 'canaleSecondario', 'created_at', 'strutture_id'])
                ->where('strutture_id', $request->idStruttura)
                ->whereDoesntHave('appointments')
                ->whereDoesntHave('phones')
                ->when($request->tipo, fn($query) => $query->where('tipo', $request->tipo))
                ->when($request->telefono, fn($query) => $query->where('telefono', 'like', $request->telefono . '%'))
                ->get();
        } else {
            $result = Client::select(['id', 'tipo', 'fullname', 'nome', 'cognome', 'telefono', 'indirizzo', 'citta', 'cap', 'canalePrimario', 'canaleSecondario', 'created_at', 'strutture_id'])
                ->where('strutture_id', $request->idStruttura)
                ->when($request->tipo, fn($query) => $query->where('tipo', $request->tipo))
                ->when($request->telefono, fn($query) => $query->where('telefono', 'like', $request->telefono . '%'))
                ->get();

            if ($request->mesiPassati) {
                // Recupera tutti i client_id presenti in Appointment indietro di x mesi
                $fullnamesInAppointments = Appointment::where('previsto', '>=', Carbon::now()->subMonths($request->mesiPassati))
                    ->pluck('client_id')->toArray();

               // return $fullnamesInAppointments;

                // Recupera tutti i client_id presenti in telefonate indietro di x mesi
                $fullnamesInPhones = Phone::where('chiamato', '>=', Carbon::now()->subMonths($request->mesiPassati))
                    ->pluck('client_id')->toArray();

                // Rimuove dal risultato i clienti il cui fullname è già in Appointments
                $result = $result->reject(function ($client) use ($fullnamesInAppointments) {
                    return in_array($client->id, $fullnamesInAppointments);
                });

                // Rimuove dal risultato i clienti il cui fullname è già in Phones
                $result = $result->reject(function ($client) use ($fullnamesInPhones) {
                    return in_array($client->id, $fullnamesInPhones);
                });
            }
        }
        $file = Excel::download(new ClientsExport($result), 'estrai.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        return $file->deleteFileAfterSend(false);
    }

    public function estraicinque(Request $request)
    {
        $annoOggi = Carbon::now()->year;
        $result = Client::select(['tipo', 'fullname', 'telefono', 'indirizzo', 'citta', 'cap', 'canalePrimario', 'canaleSecondario', 'created_at', 'strutture_id'])
            ->where('strutture_id', $request->idStruttura)
            ->where('tipo', 'Cliente')
            ->whereDoesntHave('proformas', function ($query) use ($request, $annoOggi) {
                $query->where('anno', '>=', ($annoOggi - $request->anni));
            })
            ->get();

        $file = Excel::download(new ClientsExport($result), 'estrai.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        return $file->deleteFileAfterSend(false);
    }

    public function cellulari()
    {
        $clients = Client::where('telefono', 'like', '3%')
            ->selectRaw('cap, citta, COUNT(*) as cellulare')
            ->groupBy('cap', 'citta')
            ->orderBy('cap', 'asc')->get(); // Ordine decrescente

        /*$clients = Client::selectRaw(
                'cap, citta,
                SUM(CASE WHEN REPLACE(telefono, " ", "") LIKE "3%" THEN 1 ELSE 0 END) as cellulari,
                SUM(CASE WHEN telefono LIKE "0%" THEN 1 ELSE 0 END) as fissi'
            )
            ->groupBy('cap', 'citta')
            ->orderBy('cap')
            ->get()
            ->keyBy('cap');*/

        $totClients = Client::count();
        $totCell = Client::where('telefono', 'like', "3%")->count();
        $totFissi = Client::where('telefono', 'like', "0%")->count();

        //dd($capCount);
        return view('cellulari', [
            'clients' => $clients,
            'totClients' => $totClients,
            'totCell' => $totCell,
            'totFissi' => $totFissi,
        ]);
    }

    /*public function estraisei()
    {

    }*/

    public function clienti()
    {
        return view('clienti.clienti', [
            'clients' => Client::withCount('appointments', 'phones')->paginate(10)
        ]);
    }

    public function clientiAppuntamenti($idClient)
    {
        return view('clienti.appuntamenti', [
            'client' => Client::with('appointments')->find($idClient)
        ]);
    }

    public function clientiTelefonate($idClient)
    {
        return view('clienti.telefonate', [
            'client' => Client::with('phones')->find($idClient)
        ]);
    }

    public function verifiche()
    {
        return view('verifiche.verifiche');
    }

    public function richiamare()
    {
        $dataLimite = Carbon::now()->subMonths(5);

        return view('clienti.richiamare', [
            'filiali' => Strutture::with(['clients' => function($c) use($dataLimite) {
                $c->with(['appointments', 'phones'])
                    ->where('tipo', 'Potenziale Cliente')
                    ->where('created_at', '<', $dataLimite)
                    ->whereDoesntHave('appointments', function ($a) use($dataLimite) {
                        $a->where('previsto', '>', $dataLimite);
                    })
                    ->whereDoesntHave('phones', function ($p) use($dataLimite) {
                        $p->where('chiamato', '>', $dataLimite);
                    });
            }])->filiali()->get()
        ]);
    }

    public function capStrutture()
    {
        return view('strutture.capStrutture');
    }
}
