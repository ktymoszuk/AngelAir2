<?php

namespace App\Http\Controllers;

use App\Manager\TelecamereManager;
use App\Models\CronologiaComandi;
use App\Models\Dispositivo;
use App\Models\InvioComandi;
use App\Models\Log as Logs;
use App\Models\StatoDisp;
use App\Models\Telecamera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelecamereController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("telecamere");
    }

    // SELECT
    // tutte le telecamere
    public function show(Request $request)
    {
        $result = array();

        $struttura = $request->struttura;
        $testo = $request->testo;

        if (empty($struttura) && empty($testo)) {
            $dispositiviList = Dispositivo::with("telecamere", "comandidispositivo")->where("codCategoriaDisp", 6)->orderBy("Nome")->get();
        } else {
            $dispositiviList = Dispositivo::with("telecamere", "comandidispositivo")->where("codCategoriaDisp", 6)->where("codStruttura", $struttura)->orWhere("Nome", "LIKE", "%$testo%")->orderBy("Nome")->get();
        }

        foreach ($dispositiviList as $row) {
            $datoRow = StatoDisp::where("DevEui", $row->Deveui)->orderBy("id_statodisp", "desc")->first();
            if ($datoRow->Testo2 != null) {
                if ($datoRow->Testo2 == "Spento") {
                    $telecameraRow = Telecamera::where("codDispositivo", $row->id)->first();
                    if($telecameraRow->Stato == 1) {
                        $row["Stato"] == 1;
                    } else if($telecameraRow->Stato == 2) {
                        Telecamera::where("codDispositivo", $row->id)->update(["Stato" => 0]);
                        $row["Stato"] = 0;
                    }
                } else {
                    Telecamera::where("codDispositivo", $row->id)->update(["Stato" => 2]);
                    $row["Stato"] = 2;
                }
            } else {
                $row["Stato"] = 2;
            }
            
            array_push($result, $row);
        }
        
        return response()->json($result);
    }

    // prendo il comando da inviare alla telecamera
    public function invioComando(Request $request) {
        try {
            $telecamera = $request->telecamera;
            $comando = $request->comando;
            $codice = $request->codice;
            $deveui = $request->deveui;
            $struttura = $request->struttura;
            $stato = $request->stato;
            
            Telecamera::where("codDispositivo", $telecamera)->update(["Stato" => $stato]);

            $res = InvioComandi::insert([
                "Codice" => $codice,
                "Deveui" => $deveui
            ]);
            if ($res) {
                $res2 = CronologiaComandi::insert([
                    "codComando" => $comando,
                    "codUtente" => Auth::id(),
                    "Deveui" => $deveui,
                    "isAutomatico" => 0
                ]);
                if ($res2) {
                    $messaggio = TelecamereManager::messaggioTelecamera($stato);
                    $res3 = Logs::insert([
                        "Codice" => "SCENARIO",
                        "codUtente" => Auth::id(),
                        "codStruttura" => $struttura,
                        "Messaggio" => $messaggio
                    ]);
                    if ($res3) {
                        Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Azionamento telecamera ompletato $telecamera");
                        return response()->json(["Stato" => true, "Messaggio" => "Processo azionamento telecamera completato"]);
                    } else {
                        Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore registrazione log telecamera $telecamera");
                        return response()->json(["Stato" => false, "Messaggio" => "Errore nella registrazione dei log dell'azionamento telecamera. Comando inviato lo stesso"]);
                    }
                } else {
                    Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore inserimento cronologia telecamera $telecamera");
                    return response()->json(["Stato" => false, "Messaggio" => "Errore nella registrazione in cronologia dell'azionamento telecamera. Comando inviato lo stesso"]);
                }
            } else {
                Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore invio comando azionamento telecamera $telecamera");
                return response()->json(["Stato" => false, "Messaggio" => "Errore nell'invio del comando di azionamento telecamera"]);
            }
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore processo azionamento telecamera -> $th");
            return response()->json(["Stato" => false, "Messaggio" => "Errore processo azionamento telecamera"]);
        }
    }
}
