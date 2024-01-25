<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\NomeFirebase;
use App\Models\StatoDisp;
use App\Models\TipoDispositivo;
use Illuminate\Http\Request;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GraficiController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("grafici");
    }

    public function datiFiltrati(Request $request) {

        $tipoDisp = $request->tipodisp;
        $deveui = $request->deveui;
        $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
        $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
        $colonne = $request->colonne;
        array_push($colonne, 'DataOra');

        // Prendo i dati da statodisp
        $query = StatoDisp::where("DevEui", $deveui);

        if (!empty($dataDA) && !empty($dataA)) {
            $query->where('DataOra', '>', $dataDA)->where('DataOra', '<', $dataA)->orderBy("DataOra", "DESC");
        }

        if (!empty($dataA) && empty($dataDA)) {
            $query->where('DataOra', '>', $dataDA)->orderBy("DataOra", "DESC")->limit(10000);
        }

        if (!empty($dataDA) && empty($dataA)) {
            $query->where('DataOra', '<', $dataA)->orderBy("DataOra", "DESC")->limit(10000);
        }

        if (empty($dataDA) && empty($dataA)) {
            $query->orderBy("DataOra", "DESC")->limit(10000);
        }
        $statoDisp = $query->get($colonne);
        
        // Prendo il Tipo del dispositivo
        $tipoDisp = TipoDispositivo::where('IdTipo', $tipoDisp)->first()->toArray();
        $tipoKeys = array_keys($tipoDisp);
        $tipoValues = array_values($tipoDisp);

        $valori = (array)[];

        foreach ($tipoKeys as $k => $key) {
            if (in_array($key, $colonne)) {
                array_push($valori, $tipoValues[$k]);
            }
        }

        array_push($valori, 'DataOra');


        // Aggiusto i dati
        $dati = array();
        $dateOre = [];
        foreach ($statoDisp as $s => $stato) {
            $dato = [];
            $dato['DataOra'] = date('d/m/Y H:i:s', strtotime($stato->DataOra));
            array_push($dateOre, date('d/m/Y H:i:s', strtotime($stato->DataOra)));
            // return response()->json($dato);

            foreach ($tipoKeys as $k => $key) {
                if (in_array($key, $colonne)) {
                    $dato[$tipoValues[$k]] = $stato[$key];
                }
            }
            array_push($dati, $dato);
            
        }

        $dispositivo = Dispositivo::with("sogliadispositivo")->where("DevEui", $deveui)->first();

        $soglie = $dispositivo->sogliadispositivo;

        $result = [];
        $result['Dati'] = $dati;
        $result['Soglie'] = $soglie;
        $result['Colonne'] = $colonne;
        $result['Valori'] = $valori;
        $result['DateOre'] = $dateOre;


        return response()->json($result);

        // $dati = [];
        // foreach ($statiDisp as $value) {
        //     foreach ($colonne as $col) {
        //         $dati[$col] = $value->$col;
        //     }
        //     $result["Dati"][] = $dati;
        //     $result["DataOra"][] = date('d-m-Y H:i:s', strtotime($value->DataOra));
        // }

        // //Soglie associate al sensore
        // $soglieRow = Dispositivo::with("sogliedispositivo")->where("Deveui", $deveui)->first();

        // $result["Soglie"] = [];

        // foreach ($soglieRow->sogliedispositivo as  $key => $value) {
        //     if (!isset($soglia[$key])) {
        //         //aggiungiamo il filtro della soglia
        //         $soglia[$key]["AliasMinimo"] = $value->AliasMinimo;
        //         $soglia[$key]["ColoreMinimo"] = $value->ColoreMinimo;
        //         $soglia[$key]["AliasMassimo"] = $value->AliasMassimo;
        //         $soglia[$key]["ColoreMassimo"] = $value->ColoreMassimo;
        //         $soglia[$key]["ValoreMinimo"] = array_fill(0, count($statiDisp), $value->ValoreMinimo);
        //         $soglia[$key]["ValoreMassimo"] = array_fill(0, count($statiDisp), $value->ValoreMassimo);

        //         array_push($result["Soglie"], $soglia[$key]);
        //     }
        // }

        // $result["Valori"] = [];

        // foreach ($chiavi as $key => $chiave) {
        //     if (in_array($key, $colonne)) array_push($result["Valori"], $chiave);
        // }

        // return response()->json(['data' => $result, 'colonne' => $colonne]);

        return response()->json($statoDisp);
    }

    public function datiFiltrati2(Request $request) {
        try {
            // if (is_null($request->deveui)) {
            //    // Fare una session di mancato dispositivo selezionato
            // }


            foreach ($tipoDispositivo as $index => $campo) {
                if (!is_null($campo) && $campo != "" && !(similar_text('Testo', $index) >= 5)) {
                    $chiavi[$index] = $campo;
                }
                if (!is_null($campo) && $campo != "" && (similar_text('Numero', $index) >= 6
                    || similar_text('Float', $index) >= 5)) {
                    array_push($colonne, $index);
                }
            }

            array_push($colonne, 'DataOra');  //Inseriamo la colonna dataora per la query

            $statiDisp = [];

            //Otteniamo i dati necessari 
            if (empty($dataDA) && empty($dataA)) {
                $statiDisp = StatoDisp::where("DevEui", strval($deveui))->orderBy("DataOra", "DESC")->take(100)->get($colonne);
            } else if (empty($dataDA) && !empty($dataA)) //Campo data fino a inserito
            {
                $statiDisp = StatoDisp::where("DevEui", strval($deveui))->where("DataOra", "<=", $dataA)->orderBy("DataOra", "DESC")->get($colonne);
            } else if (!empty($dataDA) && empty($dataA))  //Campo data da inserito
            {
                $statiDisp = StatoDisp::where("DevEui", strval($deveui))->where("DataOra", ">=", $dataDA)->orderBy("DataOra", "DESC")->get($colonne);
            } else if (!empty($dataDA) && !empty($dataA))  //Range di data specificato
            {
                if ($dataDA > $dataA) {  //La data da non può essere maggiore della data a
                    return response()->json(["error" => "La Data finale deve essere minore o uguale alla Data iniziale"]);
                } else {
                    $statiDisp = StatoDisp::where("DevEui", strval($deveui))->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("DataOra", "DESC")->get($colonne);
                }
            }

            if (count($statiDisp) < 1) {
                return response()->json(["error" => "Nessun valore disponibile per questo range di date"]);
            }

            //Riorganizzazione dei dati 
            $dati = [];
            foreach ($statiDisp as $value) {
                foreach ($colonne as $col) {
                    $dati[$col] = $value->$col;
                }
                $result["Dati"][] = $dati;
                $result["DataOra"][] = date('d-m-Y H:i:s', strtotime($value->DataOra));
            }

            //Soglie associate al sensore
            $soglieRow = Dispositivo::with("sogliedispositivo")->where("Deveui", $deveui)->first();

            $result["Soglie"] = [];

            foreach ($soglieRow->sogliedispositivo as  $key => $value) {
                if (!isset($soglia[$key])) {
                    //aggiungiamo il filtro della soglia
                    $soglia[$key]["AliasMinimo"] = $value->AliasMinimo;
                    $soglia[$key]["ColoreMinimo"] = $value->ColoreMinimo;
                    $soglia[$key]["AliasMassimo"] = $value->AliasMassimo;
                    $soglia[$key]["ColoreMassimo"] = $value->ColoreMassimo;
                    $soglia[$key]["ValoreMinimo"] = array_fill(0, count($statiDisp), $value->ValoreMinimo);
                    $soglia[$key]["ValoreMassimo"] = array_fill(0, count($statiDisp), $value->ValoreMassimo);

                    array_push($result["Soglie"], $soglia[$key]);
                }
            }

            $result["Valori"] = [];

            foreach ($chiavi as $key => $chiave) {
                if (in_array($key, $colonne)) array_push($result["Valori"], $chiave);
            }

            return response()->json(['data' => $result, 'colonne' => $colonne]);
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip() . " -> " . Auth::id() . ": Errore processo invio valori disponibile $th");
            Session::flash("error", "Errore processo valori disponibili");
            return response()->json(["error" => "Attenzione valori non disponibili"]);
        }
    }

    public function valoriDisponibili(Request $request) {
        $codTipoDisp = Dispositivo::where('DevEui', $request->deveui)->first()->codTipoDisp;
        $tipo = TipoDispositivo::where('IdTipo', $codTipoDisp)->first();

        $chiavi = array_keys($tipo->toArray());
        $keys = [];
        $chiaviNonNecessarie = ['IdTipo', 'Logo', 'Nome', 'Note', 'ParserName', 'PinMappa', ];

        $result = [];
        foreach ($chiavi as $k => $chiave) {
            if (!in_array($chiave, $chiaviNonNecessarie)) {
                array_push($keys, $chiavi[$k]);
                if (!is_null($tipo[$chiave])) {
                    $obj = (object)[];
                    $obj->nome = $tipo[$chiave];
                    $obj->colonna = $chiave;
                    array_push($result, $obj);
                }
            }
        }

        return response()->json($result);
    }

    // SELECT
    // visualizzazione dati per grafico
    public function show(Request $request) {
        try {
            $deveui = $request->deveui;
            $appTag = $request->apptag;
            $colonneList = $request->colonne;
            $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
            $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
            $result = array();
    
            // dati sensore
            if (empty($dataDA) && empty($dataA)) {
                $datiList = StatoDisp::where("Deveui", $deveui)->orderBy("id_statodisp", "desc")->limit(50)->get();
            } else {
                if (empty($dataDA) && !empty($dataA)) {
                    return response()->json(["error" => "Data iniziale non valorizzata"]);
                } else if (!empty($dataDA) && empty($dataA)) {
                    return response()->json(["error" => "Data finale non valorizzata"]);
                } else {
                    if ($dataDA > $dataA) {
                        return response()->json(["error" => "La Data finale deve essere minore o uguale alla Data iniziale"]);
                    } else {
                        $datiList = StatoDisp::where("Deveui", $deveui)->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("id_statodisp", "desc")->get();
                    }
                }
            }
    
            // dati sensore
            foreach ($datiList as $key => $value) {
                foreach ($colonneList as $key2 => $col) {
                    $dati[$col] = $value->$col;
                }
                $result["Dati"][] = $dati;
                $result["DataOra"][] = $value->DataOra;
            }
            
            // soglie associate al sensore
            $soglieRow = Dispositivo::with("sogliedispositivo")->where("Deveui", $deveui)->first();
            foreach ($soglieRow["sogliedispositivo"] as $key => $value) {
                // aggiungere filtro soglia
                $soglia["AliasMinimo"] = $value->AliasMinimo;
                $soglia["ColoreMinimo"] = $value->ColoreMinimo;
                $soglia["ColoreMassimo"] = $value->ColoreMassimo;
                $i = 0;
                for ($i=0; $i < count($datiList); $i++) { 
                    $soglia["Valori"][] = array("ValoreMinimo" => $value->ValoreMinimo, "ValoreMassimo" => $value->ValoreMassimo);
                }
                $result["Soglie"][] = $soglia;
            }
    
            
            // valori colonne
            $ositivoRow = NomeFirebase::with("ositivo")->where("AppTag", $appTag)->first();
            foreach ($colonneList as $key => $value) {
                $result["Valori"][] = $ositivoRow->ositivo->$value;
            }
            
            return response()->json($result);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Grafici", $request, $th);
            return response()->json($th);
        }
        
    }
}
