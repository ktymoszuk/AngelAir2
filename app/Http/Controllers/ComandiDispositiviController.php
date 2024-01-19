<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ComandoDispositivo;
use Illuminate\Http\Request;
use App\Models\Comando;
use App\Models\Dispositivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ComandoDispRequest;
use App\Manager\LogManager;

class ComandiDispositiviController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function view()
    {
        return view("comando-dispositivi");
    }

    public function comandi() {
        try {
            $res = Comando::orderBy('Nome', 'DESC')->get();
            return response()->json($res);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Comandi dispositivi", null, $th);
            return response()->json(null);
        }
    }



    // public function dispositivi(Request $request) {
        
    //     $soglieDispositivi = SogliaDispositivo::where('codSoglia', $request->soglia)->get();

    //     $idDispositivi = [];

    //     foreach ($soglieDispositivi as $s => $soglia) {
    //         array_push($idDispositivi, $soglia->codDispositivo);
    //     }

    //     $dispositivi = Dispositivo::whereNotIn('id', $idDispositivi)->with('sogliadispositivo')->orderBy("Nome")->get();
        
    //     return response()->json($dispositivi);
    // }


    public function dispositivi(Request $request) {
        try {
            $idDispositivi = ComandoDispositivo::where('codComando', $request->soglia)->pluck('codDispositivo')->toArray();
            
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
    
            $dispositivi = $query->with('comandodispositivo')->orderBy("Nome")->get();
    
            return response()->json($dispositivi);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Comando dispositivo", $request, $th);
            return response()->json(null);
        }
    }


    public function show(Request $request) {
        try {
            $codComando = (int)$request->comando;
            if (isset($request->dispositivo)) {
                $res = ComandoDispositivo::where("codDispositivo", $request->dispositivo)->where("codComando", $request->comando)->first();
            } else {
                $res = ComandoDispositivo::with("dispositivi")->where("codComando", $codComando)->get();
            }
            return response()->json($res);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Comando", $request, $th);
            return response()->json(null);
        }
    }

    public function insert(ComandoDispRequest $request) {
        try {
            $i = 0;
            
            $codComando = $request->id;
            $dispositiviList = $request->codDispositivo;
            
            foreach ($dispositiviList as $key => $value) {
                $sogliaDispRow = ComandoDispositivo::where("codDispositivo", $value)->where("codComando", $codComando)->first();
                if ($sogliaDispRow == null) {
                    
                    $res = ComandoDispositivo::insert([
                        "codComando" => $codComando,
                        "codDispositivo" => $value,
                    ]);

                    if ($res) {
                        Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Inserimento comando dispositivo: $codComando - $value");
                        $i++;
                    } else {
                        Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore inserimento comando dispositivo: $codComando - $value");
                    }
                } else {
                    Session::flash("success", "Associazione comando dispositivo già esistente");
                    Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Associazione comando dispositivo già esistente: $codComando - $value");

                    return back();
        
                }
            }

            Session::flash("success", "Associazioni comando dispositivi completate: $i/".count($dispositiviList));

            return back();
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore inserimento comando dispositivo: ".$th);
            Session::flash("error", "Errore inserimento comando dispositivo: ".$th);
            return back();
        }
    }

    public function delete(Request $request) {
        try {
            // Primo: id dispositivo
            // Secondo: id comando
    
            $dati = explode('-', $request->id);
    
            $dispositivo = $dati[0];
            $comando = $dati[1];
    
            $res = ComandoDispositivo::where('codDispositivo', $dispositivo)->where('codComando', $comando)->delete();
    
            if($res){
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Associazione comando-dispositivo", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Associazione comando-dispositivo"));
            }else{
                LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Associazione comando-dispositivo", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Associazione comando-dispositivo"));
            }
    
            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", 2, false, $request->ip(), Auth::id(), "Comando dispositivo", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(2, false, "Comando dispositivo"));
            return back();
        }
    }
}
