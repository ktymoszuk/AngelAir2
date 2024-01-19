<?php

namespace App\Http\Controllers;

use App\Models\Struttura;
use App\Manager\LogManager;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use App\Models\TipoDispositivo;

use App\Models\SogliaDispositivo;

// MODELS
use App\Models\ComandoDispositivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DispositiviController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('dispositivi');
    }

    public function download()
    {
        return response()->download(public_path('csv/download/angeltracking-dispositivi-template.csv'));
    }

    public function update(Request $request)
    {
        try {
            $id = (int)$request->id;

            $query = LogicManager::generazioneQuery($request->all());
            $a = filter_var($query['isAbilitato'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $query['isAbilitato'] = $a ? 1 : 0;
        
            $res = Dispositivo::where("id", $id)->update($query);
            if ($res) {
                LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(1, true, "Dispositivo"));
            } else {
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Dispositivo"));
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Dispositivo", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(1, false, "Dispositivo"));
            return back();
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $soglieDispRow = SogliaDispositivo::where("codDispositivo", $id)->first();
            if ($soglieDispRow != null) {
                $res = SogliaDispositivo::where("codDispositivo", $id)->delete();
                if ($res) {
                    LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Soglia del dispositivo", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(2, true, "Soglia del dispositivo"));
                } else {
                    LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Soglia del dispositivo", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(2, false, "Soglia del dispositivo"));
                }
            }

            $comandiDispRow = ComandoDispositivo::where("codDispositivo", $id)->first();
            if ($comandiDispRow != null) {
                $res = ComandoDispositivo::where("codDispositivo", $id)->delete();
                if ($res) {
                    LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Comando del dispositivo", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(2, true, "Comando del dispositivo"));
                } else {
                    LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Comando del dispositivo", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(2, false, "Comando del dispositivo"));
                }
            }

            $res = Dispositivo::where("id", $id)->delete();
            if ($res) {
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Dispositivo"));
            } else {
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Dispositivo"));
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Dispositivo", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(2, false, "Dispositivo"));
            return back();
        }
    }

    public function comando(Request $request)
    {
        dd($request);
    }

    public function soglia(Request $request)
    {
        dd($request);
    }

    public function insertOne(Request $request)
    {
        try {
            //Settiamo le coordinate e i chilometri in caso di mancata impostazione
            $query = LogicManager::generazioneQuery($request->all());
            $query['codStruttura'] = (int)$query['codStruttura'];
            $query['codTipoDisp'] = (int)$query['codTipoDisp'];

            $dispositiviDB = Dispositivo::where('DevEui', $request->DevEui)->count();  // controlliamo se il dispositivo esiste già

            if ($dispositiviDB < 1) {
                $dispositivo = Dispositivo::create($query);

                if ($dispositivo) {
                    LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(0, true, "Dispositivo"));

                    //Settiamo nella tabella sincronizzazioni AxId
                    Artisan::call('ax:generate', ["tabella" => "Dispositivi"]);
                    
                } else {
                    LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(0, false, "Dispositivo"));
                }
            } else {
                Session::flash("error", "Ci spiace, ma questo dispositivo è già presente nel Database");
                return back();
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
            Session::flash("error", LogManager::messaggiOperazioni(0, false, "Dispositivo"));
            return back();
        }
    }

    public function insertMany(Request $request) {
        try {
            //Estrazione dati dal file csv e sottoposti a escape
    
            $file = $request->file('csvFile');  //File in arrivo dal form
    
            $extension = $file->getClientOriginalExtension();  //otteniamo l'estensione del file 
    
            $fileName = time() . '.' . $extension;  //cambiamo il nome al file csv per mitigare directory trasversal
    
            $file->storeAs('csvDispositivi', $fileName);  //Salviamo il file
    
            $filePath = storage_path('app/csvDispositivi/') . $fileName;
    
            $dispGlobali = LogicManager::getCsvData($filePath); //Otteniamo i dati che ci servono per inserirli a Db
    
            File::delete(storage_path('app/csvDispositivi/') . $fileName);  //Cancelliamo il file dal DB
    
            //Validazione dei dati dal csv
    
            $datiDaValidare = [];
    
            $idCategoria = null;
    
            if (count($dispGlobali) > 1) {
    
                foreach ($dispGlobali as $index => $riga) {
    
                    //I dati partono dalla riga 1
                    if ($index >= 1) {
    
                        //Ricerchiamo l'id della categoria tramite il nome dove aver verificato che l'utente l'abbia inserito
                        $nomeCategoria = LogicManager::lowerNoSpazi($riga['Categoria']);  //lowercase e trim del nome categoria inserito
    
                        //Ricerchiamo l'id della struttura tramite il nome dove aver verificato che l'utente l'abbia inserito
                        $nomeStruttura = LogicManager::lowerNoSpazi($riga['Struttura']);  //lowercase e trim del nome struttura inserito
    
                        $idCategoria = $riga['Categoria'] == "" ?  //deterniamo l'ide della categoria del dispositivo
                            null :
                            TipoDispositivo::whereRaw("replace(lower(Nome), ' ', '') like (?)", ["%{$nomeCategoria}%"])->pluck('id')->first();
    
                        $idStruttura = $riga['Struttura'] == "" ?  //determiniamo l'id della struttura
                            null :
                            Struttura::whereRaw("replace(lower(Nome), ' ', '') like (?)", ["%{$nomeStruttura}%"])->pluck('id')->first();
    
                        //Settiamo le coordinate in caso di mancata impostazione
                        $latitudine = $riga['Latitudine'] != "" ? (float)$riga['Latitudine'] : 0.0000000;
                        $longitudine = $riga['Longitudine'] != "" ? (float)$riga['Longitudine'] : 0.0000000;
                        $Km = $riga['Km']  != "" ? $riga['Km'] : "0+000";
    
                        //Array da validarne gli input
                        array_push($datiDaValidare, [
                            'Nome' => $riga['NomeDispositivo'],
                            'DevEui' => $riga['DevEui'],
                            'Latitudine' => $latitudine,
                            'Longitudine' => $longitudine,
                            'Km' => $Km,
                            'codCategoriaDisp' => $idCategoria,
                            'codStruttura' => $idStruttura,
                        ]);
    
                        //Validazione dei dati
                        Validator::make(
                            $datiDaValidare[$index - 1],
                            [
                                "Nome" => "required|max:50",
                                "DevEui" => "required|max:16",
                                'Latitudine' => 'nullable|numeric',
                                'Longitudine' => 'nullable|numeric',
                                "Km" => "nullable|max:10",
                                "codCategoriaDisp" => "required|integer",
                                "codStruttura" => "required|integer",
                            ],
                            [
                                "Nome.required" => "Il campo Nome è obbligatorio",
                                "Nome.max" => "Il campo Nome nopn può essere più lungo di 50 caratteri",
                                "DevEui.required" => "Il campo DevEui è obbligatorio",
                                "DevEui.max" => "Il campo DevEui deve essere di massimo 16 caratteri",
                                'Latitudine.numeric' => 'La latitudine deve essere un numero',
                                'Longitudine.numeric' => 'La logitudine deve essere un numero',
                                "Km" => "Il campo Km non può essere più lungo di 10 caratteri",
                                "codCategoriaDisp.required" => "Il campo Categoria è obbligatorio",
                                "codCategoriaDisp.integer" => "Per favore controlla o riscrivi la categoria del dispositivo, grazie per la collaborazione",
                                "codStruttura.required" => "Il campo Struttura è obbligatorio",
                                "codStruttura.integer" => "Per favore controlla o riscrivi il nome della struttura, grazie per la collaborazione",
                            ]
                        )->validate();
                    }
                }
    
                //Inserimento dati a db e check se presenti o meno
    
                $allDevEui = Dispositivo::get('DevEui'); //Deveui in tutto il Db per la verifica univoca dei dispositivi
    
                $inseritiCsv = [];  //Dispositivi inseriti con successo nel DB
    
                $nonInseritiCsv = [];  //Dispositivi non inseriti nel DB perchè già presenti
    
                $arrayDevEui = [];
    
                $doppioniCsv = [];
    
                foreach ($datiDaValidare as $riga) //Check dei deveui per verificarne l'univocità
                {
                    $check = true;
    
                    foreach ($allDevEui as $index => $DevEui) {
                        if ($DevEui->DevEui == $riga['DevEui'] && $check) {
    
                            $check = false;
    
                            array_push($nonInseritiCsv, $riga['Nome']);
    
                            Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": L'operatore ha provato a inserire un dispositivo già presente a DB");
                        }
                    }
    
                    if (in_array($riga['DevEui'], $arrayDevEui)) {
                        array_push($doppioniCsv, $riga['Nome']);
                    }
    
                    if ($check && !in_array($riga['DevEui'], $arrayDevEui)) //Se il Deveui non esiste è possibile inserire i dati a db
                    {
                        try {
                            $categoriaDispositivo = TipoDispositivo::where("id", $idCategoria)->first()->Nome;
    
                            // $appTag = DataLink::where('NomeFB', 'LIKE', '%'.$categoriaDispositivo.'%')->first();
    
                            $dispositivo = Dispositivo::create([
                                'Nome' => $riga['Nome'],
                                'DevEui' => $riga['DevEui'],
                                'Latitudine' => $riga['Latitudine'],
                                'Longitudine' => $riga['Longitudine'],
                                'Km' => $riga['Km'],
                                'codCategoriaDisp' => $riga['codCategoriaDisp'],
                                'codStruttura' => $riga['codStruttura'],
                                'AppTag' => 'APPTAG',
                            ]);
                            if ($dispositivo) {
                                array_push($arrayDevEui, $riga['DevEui']);
                                array_push($inseritiCsv, $riga['Nome']);
                                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                                Session::flash("success", LogManager::messaggiOperazioni(0, true, "Dispositivo"));
                            } else {
                                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                                Session::flash("error", LogManager::messaggiOperazioni(0, false, "Dispositivo"));
                            }
                        } catch (\Throwable $th) {
                            LogManager::scritturaLogs("operazioni", null, false, $request->ip(), Auth::id(), "Dispositivo", $request, NULL);
                            Session::flash("error", LogManager::messaggiOperazioni(1, false, "Dispositivo"));
                            return back();
                        }
                    }
                }
    
                if (!empty($doppioniCsv)) {
                    foreach (array_unique($doppioniCsv) as $doppione) {
                        array_push($nonInseritiCsv, $doppione);
                    }
                }
    
                //trasformo gli array in stringhe da inserire nella vista blade
                $stringaInseriti = implode(", ", $inseritiCsv);
                $stringaNonInseriti = implode(", ", $nonInseritiCsv);
    
                if (empty($inseritiCsv) && !empty($nonInseritiCsv)) {
                    Session::flash('error', "I seguenti dispositivi non sono stati inseriti perchè già presenti a DB: $stringaNonInseriti");
                } else if (!empty($inseritiCsv) && empty($nonInseritiCsv)) {
                    Session::flash('success', "I seguenti dispositivi sono stati inseriti: $stringaInseriti");
                } else if (!empty($inseritiCsv) && !empty($nonInseritiCsv)) {
                    Session::flash('warning', "I seguenti dispositivi sono stati inseriti: $stringaInseriti . 
                                                I seguenti dispositivi non sono stati inseriti perchè già presenti a DB: $stringaNonInseriti");
                }
                return back();
            } else {
                Session::flash('error', "Attenzione hai inserito un file excel vuoto");
                return back();
            }
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", 1, false, $request->ip(), Auth::id(), "Dispositivi", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(1, false, "Dispositivi"));
            return back();
        }
        
    }
}
