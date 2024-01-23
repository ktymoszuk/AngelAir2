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

    public function valoriDisponibili(Request $request) {
        $codTipoDisp = Dispositivo::where('DevEui', $request->deveui)->first()->codTipoDisp;
        $tipo = TipoDispositivo::where('IdTipo', $codTipoDisp)->first();

        $chiavi = array_keys($tipo->toArray());
        $keys = [];
        $chiaviNonNecessarie = ['IdTipo', 'Logo', 'Nome', 'Note', 'ParserName', 'PinMappa', ];

        $valori = [];
        foreach ($chiavi as $k => $chiave) {
            if (!in_array($chiave, $chiaviNonNecessarie)) {
                array_push($keys, $chiavi[$k]);
                if (!is_null($tipo[$chiave])) {
                    $valori[$tipo[$chiave]] = $chiave;
                }
            }
        }

        return response()->json($valori);
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
