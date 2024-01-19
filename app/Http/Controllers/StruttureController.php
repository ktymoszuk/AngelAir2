<?php

namespace App\Http\Controllers;

use App\Models\Allarmi;
use App\Models\Struttura;
use App\Manager\LogManager;
use App\Models\Automazione;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use App\Http\Requests\CsvRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\StrutturaRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateStruttureRequest;

class StruttureController extends Controller
{
    public function __construct()
    {
        $this->middleware(["utente"]);
    }

    public function index()
    {
        return view("strutture");
    }

    //Lista sinottici disponibili
    public function getSinottici()
    {

        $colonne = Schema::getColumnListing('Strutture');
        $cartografie = [];

        foreach ($colonne as $colonna) {
            if (similar_text('Cartografia', $colonna) >= 11) {
                array_push($cartografie, $colonna);
            }
        }
        return response()->json($cartografie);
    }

    // SELECT
    // tutte le strutture
    public function show(Request $request)
    {
        $automazione = $request->automazione;
        $testo = $request->testo;
        return response()->json($request);



        if (empty($automazione) && empty($testo)) {
            $res = Struttura::with("automazione")->orderBy("Nome")->paginate(5);
        } else if (!empty($automazione) && empty($testo)) {
            $res = Struttura::with("automazione")->where("codAutomazione", $automazione)->orderBy("Nome")->paginate(5);
        } else if (empty($automazione) && !empty($testo)) {
            $res = Struttura::with("automazione")->where("Nome", "like", "%$testo%")->orderBy("Nome")->paginate(5);
        } else {
            $res = Struttura::with("automazione")->where("Nome", "like", "%$testo%")->where("codAutomazione", $automazione)->orderBy("Nome")->paginate(5);
        }

        return response()->json($res);
    }

    // singola struttura
    public function showStruttura(Request $request)
    {
        $id = $request->id;

        $res = Struttura::where("id", $id)->first();
        return response()->json($res);
    }

    // UPDATE
    // aggiornamento struttura
    public function update(StrutturaRequest $request)
    {
        try {
            $id = (int)$request->id;
            $query = LogicManager::generazioneQuery($request->all());
            $res = null;
            if (is_null($request->Immagine)) {
                $res = Struttura::where("id", $id)
                ->update([
                    "Nome" => $query['Nome'],
                    "Indirizzo" => $query['Indirizzo'],
                    "Referente" => $query['Referente'],
                    "Latitudine" => $query['Latitudine'],
                    "Longitudine" => $query['Longitudine'],
                ]);
            } else {
                if (is_null($request->Cartografia)) {
                    Session::flash("error", LogManager::messaggiOperazioni(1, false, "Cartografia: è necessario specificare la Cartografia"));
                    return redirect()->route("strutture");
                } else {
                    $extension = $request->Immagine->getClientOriginalExtension();  
                    $immagine = time() . '.' . $extension; 
                    $request->Immagine->move(public_path('immagini/mappa'), $immagine);
                    $query['Cartografia'] = $immagine;
                    $res = Struttura::where("id", $id)->update([
                        "Nome" => $query["Nome"],
                        "Indirizzo" => $query["Indirizzo"],
                        "Referente" => $query["Referente"],
                        "Latitudine" => $query["Latitudine"],
                        "Longitudine" => $query["Longitudine"],
                        $request->Cartografia => $query["Cartografia"],
                    ]);
                }
            }
            
            if ($res) {
                
                $strutturaRow = Struttura::where("id", $id)->first();
                
                $verificaAutomazione = Automazione::where("id", $strutturaRow->codAutomazione)->first();
                
                if (
                    $verificaAutomazione->Nome != $query['Nome'] ||
                    $verificaAutomazione->Latitudine != (double)$query['Latitudine'] ||
                    $verificaAutomazione->Longitudine != (double)$query['Longitudine']
                    ) {
                    $idAut = $strutturaRow->codAutomazione;

                    $res2 = Automazione::where("id", $idAut)->update([
                        "Nome" => $query['Nome'],
                        "Latitudine" => $query['Latitudine'],
                        "Longitudine" => $query['Longitudine'],
                    ]);

                    if ($res2) {
                        LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                        Session::flash("success", LogManager::messaggiOperazioni(1, true, "Struttura"));
                    } else {
                        LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Automazione della Struttura", $request, NULL);
                        Session::flash("error", LogManager::messaggiOperazioni(1, false, "Automazione della Struttura"));
                    }
                } else {
                    LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(1, true, "Struttura"));
                }
            } else {
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Struttura"));
            }

            return redirect()->route("strutture");
        } catch (\Throwable $th) {
            return $th;
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Struttura", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Struttura ggg"));
            return redirect()->route("strutture");
        }
    }

    // DELETE
    // cancellazione struttura
    public function delete(Request $request)
    {
        try {
            $strutturaRow = Struttura::where("id", $request->id)->first();
            $res = Struttura::where("id", $request->id)->delete();
            if ($res) {
                $res2 = Automazione::where("id", $strutturaRow->codAutomazione)->delete();
                if ($res2) {
                    LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(2, true, "Struttura"));
                } else {
                    LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Automazione della Struttura", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(2, false, "Automazione della Struttura"));
                }
            } else {
                LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Struttura"));
            }

            return redirect()->route("strutture");
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Struttura", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Struttura"));
            return redirect()->route("strutture");
        }
    }

    //Post per l'inserimento di una singola struttura nel db
    public function insertOne(CreateStruttureRequest $request)
    {
        $query = LogicManager::generazioneQuery($request->all());

        //Settiamo le coordinate e i chilometri in caso di mancata impostazione
        $latitudine = $query["Latitudine"] != "" ? (float) $query["Latitudine"] : 0.0000000;
        $longitudine = $query["Longitudine"]  != "" ? (float) $query["Longitudine"] : 0.0000000;
        $Km = $query["Km"]  != "" ? $query["Km"] : "0+000";
        $indirizzo = $query["Indirizzo"] != "" ? $query["Indirizzo"] : "Località sconosciuta";
        $referente = $query["Referente"] != "" ? $query["Referente"] : "Nessun referente";

        $struttureList = Struttura::where('Nome', $query["Nome"])->get('Nome');  // controlliamo se la struttura esiste già

        if ($struttureList->count() < 1) {
            try {
                $struttura = Struttura::insert([
                    'Nome' => $query["Nome"],
                    'Indirizzo' => $indirizzo,
                    'Latitudine' => $latitudine,
                    'Longitudine' => $longitudine,
                    'Km' => $Km,
                    'Referente' =>  $referente,
                ]);

                if ($struttura) {
                    $automazione = Automazione::insert([
                        "Nome" => $query["Nome"],
                        "Latitudine" => $latitudine,
                        "Longitudine" => $longitudine,
                        "Km" => $Km,
                    ]);

                    if ($automazione) {
                        $automazioneRow = Automazione::where("Nome", $query["Nome"])->first();

                        $res = Struttura::where("Nome", $query["Nome"])->update(["codAutomazione" => $automazioneRow->id]);

                        if ($res) {
                            LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                            Session::flash("success", LogManager::messaggiOperazioni(0, true, "Struttura"));
                        } else {
                            LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Associazione della struttura", $request, NULL);
                            Session::flash("error", LogManager::messaggiOperazioni(0, false, "Associazione della struttura"));
                        }
                    } else {
                        LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Automazione della struttura", $request, NULL);
                        Session::flash("error", LogManager::messaggiOperazioni(0, false, "Automazione della struttura"));
                    }
                } else {
                    LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(0, true, "Struttura"));
                }
            } catch (\Throwable $th) {
                LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Struttura", $request, $th);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Struttura"));
                return redirect()->route("strutture");
            }
        } else {
            LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
            Session::flash("success", LogManager::messaggiOperazioni(0, true, "Struttura"));
            return redirect()->route("strutture");
        }

        return redirect()->back();
    }

    //Scarichiamo il file csv per l'inserimento multiplo dei dati
    public function download()
    {
        return response()->download(public_path('csv/download/angelbridge-strutture-template.csv'));
    }

    //Gestiamo il caricamento del file csv all' interno del db
    public function uploadCsv(CsvRequest $request)
    {
        //Estrazione dati dal file csv e sottoposti a escape

        $file = $request->file('csvFile');  //File in arrivo dal form

        $extension = $file->getClientOriginalExtension();  //otteniamo l'estensione del file 

        $fileName = time() . '.' . $extension;  //cambiamo il nome al file csv per mitigare directory trasversal

        $file->storeAs('csvDispositivi', $fileName);  //Salviamo il file

        $filePath = storage_path('app/csvDispositivi/') . $fileName;

        $strutture = LogicManager::getCsvData($filePath); //Otteniamo i dati che ci servono per inserirli a Db

        File::delete(storage_path('app/csvDispositivi/') . $fileName);  //Cancelliamo il file dal DB

        //Validazione dei dati dal csv

        $datiDaValidare = [];

        foreach ($strutture as $index => $riga) {
            //I dati partono dalla riga 1
            if ($index >= 1) {

                //Settiamo le coordinate e i chilometri in caso di mancata impostazione
                $latitudine = $riga['Latitudine'] != "" ? (float)$riga['Latitudine'] : 0.0000000;
                $longitudine = $riga['Longitudine']  != "" ? (float)$riga['Longitudine'] : 0.0000000;
                $Km = $riga['Km']  != "" ? $riga['Km'] : "0+000";
                $indirizzo = $riga['Indirizzo'] != "" ? $riga['Indirizzo'] : "Località sconosciuta";
                $referente = $riga['Referente'] != "" ? $riga['Referente'] : "Nessun referente";

                //Array da validarne gli input
                $datiDaValidare[$index - 1] = [
                    'Nome' => $riga['Nome'],
                    'Indirizzo' => $indirizzo,
                    'Latitudine' => $latitudine,
                    'Longitudine' => $longitudine,
                    'Km' => $Km,
                    'Referente' => $referente,
                ];

                //validiamo l'array appena creato
                Validator::make(
                    $datiDaValidare[$index - 1],
                    [
                        "Nome" => "required|max:50",
                        "Indirizzo" => "nullable|max:50",
                        "Latitudine" => "nullable|numeric",
                        "Longitudine" => "nullable|numeric",
                        "Km" => "nullable|max:10",
                        "Referente" => "nullable|max:150"
                    ],
                    [
                        'Nome.required' => 'Attenzione il Nome è richiesto',
                        'Nome.max' => 'Attenzione il Nome non può essere più lunga di 50 caratteri',
                        'Indirizzo.max' => 'Attenzione il Indirizzo non può essere più lungo di 50 caratteri',
                        'Latitudine.numeric' => 'La latitudine deve essere un numero con la virgola',
                        'Longitudine.numeric' => 'La longitudine deve essere un numero con la virgola',
                        'Referente.max' => 'Attenzione il Indirizzo non può essere più lungo di 150 caratteri',
                    ]
                )->validate();
            }
        }

        //Deveui in tutto il Db per la verifica univoca dei dispositivi
        $strutturePresenti = Struttura::get(['Nome']);

        $inseritiCsv = [];  //Dispositivi inseriti con successo nel DB

        $nonInseritiCsv = [];  //Dispositivi non inseriti nel DB perchè già presenti

        $arrayCheck = [];  //Evitiamo di inserire nuovamente la medesima struttura

        foreach ($datiDaValidare as $riga) //Check dei deveui per verificarne l'univocità
        {
            $check = true;

            foreach ($strutturePresenti as $index => $value) {
                if (LogicManager::lowerNoSpazi($value->Nome) ==  LogicManager::lowerNoSpazi($riga['Nome'])
                    || in_array(LogicManager::lowerNoSpazi($riga['Nome']), $arrayCheck)
                ) {

                    $check = false;

                    LogManager::scritturaLogs("operazioni", 3, true, $request->ip(), Auth::id(), "Struttura già presente a DB", $request, NULL);
                }
            }

            if(!$check){
                array_push($arrayCheck, LogicManager::lowerNoSpazi($riga['Nome']));
                array_push($nonInseritiCsv, $riga['Nome']);
            }
            
   
            if ($check) //Se il Deveui non esiste è possibile inserire i dati a db
            {
                try {
                    $struttura = Struttura::insert($riga);

                    array_push($arrayCheck, LogicManager::lowerNoSpazi($riga["Nome"]));

                    if ($struttura) {
                        $automazione = Automazione::insert([
                            "Nome" => $riga["Nome"],
                            'Latitudine' => $riga['Latitudine'],
                            'Longitudine' => $riga['Longitudine'],
                            'Km' => $riga['Km']
                        ]);

                        if ($automazione) {
                            $automazioneRow = Automazione::where("Nome", $riga["Nome"])->first();

                            $res = Struttura::where("Nome", $riga["Nome"])->update(["codAutomazione" => $automazioneRow->id]);

                            if ($res) {
                                array_push($inseritiCsv, $riga['Nome']);
                                LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Automazione della struttura", $request, NULL);
                                Session::flash("success", LogManager::messaggiOperazioni(0, true, "Automazione della struttura"));
                            } else {
                                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Automazione della struttura", $request, NULL);
                                Session::flash("error", LogManager::messaggiOperazioni(0, false, "Automazione della struttura"));
                            }
                        }
                    } else {
                        LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                        Session::flash("error", LogManager::messaggiOperazioni(0, false, "Struttura"));
                    }
                } catch (\Throwable $th) {
                    LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Struttura", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(0, true, "Struttura"));
                    return redirect()->route("strutture");
                }
            }
        }

        //trasformo i csv in stringhe da inserire nella vista blade
        $stringaInseriti = implode(", ", $inseritiCsv);
        $stringaNonInseriti = implode(", ", $nonInseritiCsv);

        if (empty($inseritiCsv) && !empty($nonInseritiCsv)) {
            Session::flash('error', "Le seguenti strutture non sono state inserite perchè già presente a DB: $stringaNonInseriti");
        } else if (!empty($inseritiCsv) && empty($nonInseritiCsv)) {
            Session::flash('success', "Le seguenti strutture sono state inserite: $stringaInseriti");
        } else if (!empty($inseritiCsv) && !empty($nonInseritiCsv)) {
            Session::flash('warning', "Le seguenti strutture sono state inserite: $stringaInseriti . 
            Le seguenti strutture non sono state inserite perchè già presente a DB: $stringaNonInseriti");
        }

        return redirect()->route("strutture");
    }
}
