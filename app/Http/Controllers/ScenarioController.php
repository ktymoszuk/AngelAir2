<?php

namespace App\Http\Controllers;

use App\Manager\ScenarioManager;
use App\Models\Comando;
use App\Models\CronologiaComandi;
use App\Models\Dispositivo;
use App\Models\InvioComandi;
use App\Models\Log as Logs;
use App\Models\Scenario;
use App\Models\Struttura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScenarioController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view('scenario');
    }

    // SELECT
    // visualizzazione scenario
    public function show(Request $request) {
        $result = array();
        
        $scenarioSlug = $request->scenarioSlug;
        $stato = $request->stato;

        
        if ($stato == 0) {
            $scenarioRow = Scenario::with("regoleautomazioni_apertura")->where('ScenarioSlug', $scenarioSlug)->first();
            $obj["Nome"] = $scenarioRow->Nome;
            $regoleList = $scenarioRow->regoleautomazioni_apertura;
        } else {
            $scenarioRow = Scenario::with("regoleautomazioni_chiusura")->where('ScenarioSlug', $scenarioSlug)->first();
            $obj["Nome"] = $scenarioRow->Nome;
            $regoleList = $scenarioRow->regoleautomazioni_chiusura;
        }

        $i=0;
        foreach ($regoleList as $row) {
            $dispositiviList = explode(",", $row->codDispositivo);
            $obj[$i]["Dispositivi"] = array();
            foreach ($dispositiviList as $codDispositivo) {
                $dispositivoRow = Dispositivo::where("id", $codDispositivo)->first();
                array_push($obj[$i]["Dispositivi"], $dispositivoRow);
            }

            $comandiList = explode(",", $row->codComando);
            $obj[$i]["Comandi"] = array();
            foreach ($comandiList as $codComando) {
                $comandoRow = Comando::where("id", $codComando)->first();
                array_push($obj[$i]["Comandi"], $comandoRow);
            }

            $struttureList = explode(",", $row->codStruttura);
            $obj[$i]["Strutture"] = array();
            foreach ($struttureList as $codStruttura) {
                $strutturaRow = Struttura::where("id", $codStruttura)->first();
                array_push($obj[$i]["Strutture"], $strutturaRow);
            }

            array_push($result, $obj);
            $i++;
        }

        return response()->json($result);
    }

    // INSERT
    public function insert(Request $request) {
        try {
            $comando = $request->comando;
            $codice = $request->codice;
            $deveui = $request->deveui;
            $struttura = $request->struttura;
            $scenario = $request->scenario;
            $stato = $request->stato;

            // configuro stato
            $stato = ScenarioManager::statoScenario($stato);

            // INVIO COMANDO
            $res = InvioComandi::insert([
                "Codice" => $codice,
                "Deveui" => $deveui
            ]);

            if ($res) {
                // UPDATE SCENARIO
                Scenario::where("id", $scenario)->update(["Stato" => $stato]);

                // DISATTIVAZIONE ALLARME DISPOSITIVI
                if ($stato == 0) {
                    Dispositivo::where("isAllarme", 1)->where("codStruttura", $struttura)->update(["isAllarme" => 0]);
                }
                
                // CRONOLOGIA COMANDO
                $res2 = CronologiaComandi::insert([
                    "codComando" => $comando,
                    "codUtente" => Auth::id(),
                    "Deveui" => $deveui,
                    "isAutomatico" => 0
                ]);
                if ($res2) {
                    // LOG
                    $messaggio = ScenarioManager::messaggioScenario($stato);
                    $res3 = Logs::insert([
                        "Codice" => "SCENARIO",
                        "codUtente" => Auth::id(),
                        "codStruttura" => $struttura,
                        "Messaggio" => $messaggio
                    ]);
                    if ($res3) {
                        Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Gestione scenario completato $scenario");
                        return response()->json(["Stato" => true, "Messaggio" => "Processo gestione scenario completato"]);
                    }
                } else {
                    Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore inserimento cronologia del comando $comando");
                    return response()->json(["Stato" => false, "Messaggio" => "Errore nella gestione scenario nell'inserimento in cronologia dei comandi inviato"]);
                }
            
            } else {
                Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore nell'invio del comando $comando");
                return response()->json(["Stato" => false, "Messaggio" => "Errore nell'invio del comando"]);
            }

        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore processo gestione scenario -> $th");
            return response()->json(["Stato" => false, "Messaggio" => "Errore processo gestione scenario"]);
        }
    }
}