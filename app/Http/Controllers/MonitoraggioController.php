<?php

namespace App\Http\Controllers;

use App\Models\CronologiaComandi;
use App\Models\DataLink;
use App\Models\NomiFirebase;
use App\Models\StatoDisp;
use App\Models\TipoDispositivo;
use App\Models\Utenti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;


class MonitoraggioController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function view()
    {
        return view("monitoraggio");
    }

    // SELECT
    // visualizzazione dati
    public function showDatiJSON(Request $request) {
        try {
            $result = array();
    
            $appTag = $request->apptag;
            $deveui = $request->deveui;
            $paginazione = $request->paginazione;
            $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
            $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
    
            $dataLinkRow = DataLink::where("AppTag", $appTag)->first();
            $tipoDispRow = TipoDispositivo::where("IdTipo", $dataLinkRow->IdTipo)->first();
    
            $chiaviList = $tipoDispRow->Chiavi;
            $colonneList = $tipoDispRow->Colonne;
    
            // schema colonne + frame
            $schema = array("colonne" => "Frame", "key" => "Frame");
            $result["schema"] = array();
            array_push($result["schema"], $schema);
    
            // dati
            if (empty($dataDA) && empty($dataA)) {
                $statoDispList = StatoDisp::where("DevEui", $deveui)->orderBy("DataOra", "desc")->skip($paginazione)->take(100)->get();
            } else {
                if (empty($dataDA) && !empty($dataA)) {
                    return response()->json(["error" => "Data iniziale non valorizzata"]);
                } else if (!empty($dataDA) && empty($dataA)) {
                    return response()->json(["error" => "Data finale non valorizzata"]);
                } else {
                    if ($dataDA > $dataA) {
                        return response()->json(["error" => "La Data finale deve essere minore o uguale alla Data iniziale"]);
                    } else {
                        $statoDispList = StatoDisp::where("DevEui", $deveui)->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("DataOra", "desc")->skip($paginazione)->take(100)->get();
                    }
                }
            }
    
            for ($i = 0; $i < count($chiaviList); $i++) {
                array_push($result["schema"], array("colonne" => $colonneList[$i], "key" => $chiaviList[$i]));
            }
    
            // dataora
            $schema = array("colonne" => "DataOra", "key" => "DataOra");
            array_push($result["schema"], $schema);
    
            $result[] = array("statodisp" => $statoDispList);
    
            return response()->json($result);        
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Monitoraggio", $request, $th);
            return back();
        }
    }

    public function showDatiSQL(Request $request) {
        try {
            $result = array();
            
            $appTag = $request->apptag;
            $deveui = $request->deveui;
            $paginazione = $request->paginazione;
            $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
            $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
            $colonne = Schema::connection("mysql2")->getColumnListing('TipoDisp');
        
            if ($deveui == null || $appTag == null) {
                return response()->json(["error" => "Dispositivo non selezionato"]);
            }
    
    
            $row = NomiFirebase::with("tipodisp")->where("AppTag", $appTag)->first();
            $tipodisp = $row->tipodisp;
    
    
            // schema colonne
            $schema = array("colonne" => "Frame", "key" => "Frame");
            $result["schema"] = array();
            array_push($result["schema"], $schema);
            
            // schema colonne per valori in utilizzo dai dispositivi
            foreach ($colonne as $value) {
                if (substr($value, 0, 4) == "Test" || substr($value, 0, 4) == "Nume" || substr($value, 0, 4) == "Floa") {
                    if ($tipodisp->$value != null) {
                        $schema = array("colonne" => $tipodisp->$value, "key" => $value);
                        array_push($result["schema"], $schema);
                    }
                }
            }
    
            // colonna data ora
            $schema = array("colonne" => "DataOra", "key" => "DataOra");
            array_push($result["schema"], $schema);
    
            // dati
            if (empty($dataDA) && empty($dataA)) {
                //$statodisp = StatoDisp::where("DevEui", $deveui)->orderBy("id_statodisp", "desc")->offset($paginazione)->limit(100)->get();
                $statodisp = StatoDisp::where("DevEui", $deveui)->orderBy("DataOra", "desc")->skip($paginazione)->take(100)->get();
            } else {
                if (empty($dataDA) && !empty($dataA)) {
                    return response()->json(["error" => "Data iniziale non valorizzata"]);
                } else if (!empty($dataDA) && empty($dataA)) {
                    return response()->json(["error" => "Data finale non valorizzata"]);
                } else {
                    if ($dataDA > $dataA) {
                        return response()->json(["error" => "La Data finale deve essere minore o uguale alla Data iniziale"]);
                    } else {
                        //$statodisp = StatoDisp::where("DevEui", $deveui)->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("id_statodisp", "desc")->offset($paginazione)->limit(100)->get();
                        $statodisp = StatoDisp::where("DevEui", $deveui)->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("DataOra", "desc")->skip($paginazione)->take(100)->get();
                    }
                }
            }
    
            $result[] = array("statodisp" => $statodisp);
    
            return response()->json($result);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Monitoraggio", $request, $th);
            return response()->json($th);
        }
        
    }

    // visualizzazione comandi inviati al dispositivo
    public function showComandi(Request $request) {
        try {
            $result = array();
    
            $deveui = $request->deveui;
            $paginazione = $request->paginazione;
            $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
            $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
    
            if (empty($dataDA) && empty($dataA)) {
                $cronologia = CronologiaComandi::with("comandi")->where("deveui", $deveui)->orderBy("id", "desc")->offset($paginazione)->limit(100)->get();
            } else {
                $cronologia = CronologiaComandi::with("comandi")->where("deveui", $deveui)->where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("id", "desc")->offset($paginazione)->limit(100)->get();
            }
    
            foreach ($cronologia as $key => $value) {
                $utente = Utenti::where("id", $value->codUtente)->first();
                if ($utente != null) {
                    $result[] = array("cronologia" => $value, "utente" => $utente);
                } else {
                    $result[] = array("cronologia" => $value, "utente" => "Sistema");
                }
            }
            return response()->json($result);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Monitoraggio comandi", $request, $th);
            return response()->json($th);
        }
        
    }
}
