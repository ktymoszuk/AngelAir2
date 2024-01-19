<?php

namespace App\Http\Controllers;

use App\Http\Requests\SogliaDispRequest;
use App\Manager\LogicManager;
use App\Manager\LogManager;
use App\Models\Soglia;
use App\Models\Dispositivo;
use App\Models\SogliaDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SoglieDispositiviController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function view() {
        return view("soglia-dispositivi");
    }

    public function dispositivi(Request $request) {
        
        $idDispositivi = SogliaDispositivo::where('codSoglia', $request->soglia)->pluck('codDispositivo')->toArray();

        $query = Dispositivo::whereNotIn('id', $idDispositivi);

        if (Auth::user()->isAssistenza != 1) {

            $auth1 = (int)Auth::user()->id_1liv;
            $auth2 = (int)Auth::user()->id_2liv;
            $auth3 = (int)Auth::user()->id_3liv;
            $auth4 = (int)Auth::user()->id_4liv;

            
            if ($auth4 != null) {
                $query->where("id_1liv", $auth1)
                ->where("id_2liv", $auth2)
                ->where("id_3liv", $auth3)
                ->where("id_4liv", $auth4);
            } else if ($auth3 != null) {
                $query->where("id_1liv", $auth1)
                ->where("id_2liv", $auth2)
                ->where("id_3liv", $auth3);
            } else if ($auth2 != null) {
                $query->where("id_2liv", $auth2)
                ->where("id_1liv", $auth1);
            } else if ($auth1 != null) {
                $query->where("id_1liv", $auth1);
            }
        }

        $dispositivi = $query->with('sogliadispositivo')->orderBy("Nome")->get();

        return response()->json($dispositivi);
    }

    public function soglie() {
        $res = Soglia::orderBy('Nome', 'DESC')->get();
        return response()->json($res);
    }

    // SELECT
    // visualizzazione dispositivi
    public function show(Request $request) {
        $categoriaDisp = $request->categoriadisp;

        $query = Dispositivo::where("codCategoriaDisp", $categoriaDisp);

        if (Auth::user()->isAssistenza != 1) {

            $auth1 = (int)Auth::user()->id_1liv;
            $auth2 = (int)Auth::user()->id_2liv;
            $auth3 = (int)Auth::user()->id_3liv;
            $auth4 = (int)Auth::user()->id_4liv;

            
            if ($auth4 != null) {
                $query->where("id_1liv", $auth1)
                ->where("id_2liv", $auth2)
                ->where("id_3liv", $auth3)
                ->where("id_4liv", $auth4);
            } else if ($auth3 != null) {
                $query->where("id_1liv", $auth1)
                ->where("id_2liv", $auth2)
                ->where("id_3liv", $auth3);
            } else if ($auth2 != null) {
                $query->where("id_2liv", $auth2)
                ->where("id_1liv", $auth1);
            } else if ($auth1 != null) {
                $query->where("id_1liv", $auth1);
            }
        }

        $res = $query->orderBy("Nome")->get();

        return response()->json($res);
    }

    // INSERT
    public function insert(SogliaDispRequest $request) {
        try {
            $i = 0;
            $tentativiBuoni = [];
            $tentativiFalliti = [];

            $codSoglia = $request->id;
            $dispositiviList = $request->codDispositivo;

            foreach ($dispositiviList as $key => $value) {
                $sogliaDispRow = SogliaDispositivo::where("codDispositivo", $value)->where("codSoglia", $codSoglia)->first();
                if ($sogliaDispRow == null) {
                    $res = SogliaDispositivo::insert([
                        "codSoglia" => $codSoglia,
                        "codDispositivo" => $value,
                    ]);
                    if ($res == true) {
                        array_push($tentativiBuoni, $value);
                    } else {
                        array_push($tentativiFalliti, $value);
                    }
                    if (count($tentativiFalliti) == 0) {
                        foreach ($request->codDispositivo as $key => $cod) {
                            $obj = (object)[];
                            $obj->codDispositivo = $cod;
                            LogManager::scritturaLogs("operazioni", 4, true, $request->ip(), Auth::id(), "Soglie-dispositivi", $obj, NULL);
                            Session::flash("success", LogManager::messaggiOperazioni(4, true, "Soglie-dispositivi"));                        }
                    } else {
                        Session::flash("error", LogManager::messaggiOperazioni(4, false, "Almeno una soglia"));
                    }
                } else {
                    foreach ($request->codDispositivo as $key => $cod) {
                        $obj = (object)[];
                        $obj->codDispositivo = $cod;
                        LogManager::scritturaLogs("operazioni", 4, false, $request->ip(), Auth::id(), "Soglia esistente", $obj, NULL);
                        Session::flash("error", LogManager::messaggiOperazioni(4, false, "Almeno una soglia esistente"));
                    }
                    // Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Associazione soglia dispositivo già esistente: $codSoglia - $value");
                }
            }


            // Session::flash("success", "Associazioni soglia dispositivi completate: $i/".count($dispositiviList));

            return redirect()->route("dispositivi");
        } catch (\Throwable $th) {
            return $th;
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Soglia", $request, $th);
            Session::flash("error", $th->getMessage());
            // Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Soglia"));
            // Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore inserimento soglia dispositivo: ".$th);
            // Session::flash("error", "Errore inserimento soglia dispositivo: ".$th);
            return redirect()->route("dispositivi");
        }
    }

    public function delete(Request $request) {
        // Primo: id dispositivo
        // Secondo: id soglia

        $dati = explode('-', $request->id);

        $dispositivo = $dati[0];
        $soglia = $dati[1];

        $res = SogliaDispositivo::where('codDispositivo', $dispositivo)->where('codSoglia', $soglia)->delete();

        if($res){
            LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Associazione soglia-dispositivo", $request, NULL);
            Session::flash("success", LogManager::messaggiOperazioni(2, true, "Associazione soglia-dispositivo"));
        }else{
            LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Associazione soglia-dispositivo", $request, NULL);
            Session::flash("error", LogManager::messaggiOperazioni(2, false, "Associazione soglia-dispositivo"));
        }

        return back();
    }
}