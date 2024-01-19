<?php

namespace App\Http\Controllers;

use App\Models\Log as Logs;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;



class LogsController extends Controller
{
    //Scrittura messaggio log a DB
    static public function scritturaLog($codice, $messaggio, $idUtente)
    {
        try{
            DB::insert('insert into Logs (AxAppTag, AxSystemTag, Codice, codUtente, Messaggio, DataOra) values ( ?, ?, ?, ?, ?, ?)', ["VS_AT", "VS_AT_Belluno"  ,$codice, $idUtente, $messaggio, now()]);
            Artisan::call('ax:generate', ["tabella" => "Logs"]);
            return 1;
        }catch(\Throwable $th){
            LogManager::scritturaLogs("operazini", 1, false, null, Auth::id(), "Log", null, $th);
            return 0;
        }
    }

    //Caricamento view dei logs
    public function view()
    {
        return view("logs");
    }

    //Caricamento dati logs
    public function logData(Request $request)
    {
        try{
            $codice = LogicManager::generazioneQuery($request->all())['codice'];
            $dataDA = ($request->dataDA != "Invalid date") ? $request->dataDA : "";
            $dataA = ($request->dataA != "Invalid date") ? $request->dataA : "";
            
            if (empty($dataDA) && empty($dataA)) {
                // $logs = Logs::get();
                $logs = Logs::orderBy("DataOra", "DESC")->take(100);
            } else if (empty($dataDA) && !empty($dataA)) //Campo data fino a inserito
            {
                $logs = Logs::where("DataOra", "<=", $dataA)->orderBy("DataOra", "DESC");
            } else if (!empty($dataDA) && empty($dataA))  //Campo data da inserito
            {
                $logs = Logs::where("DataOra", ">=", $dataDA)->orderBy("DataOra", "DESC");
            } else if (!empty($dataDA) && !empty($dataA))  //Range di data specificato
            {
                if ($dataDA > $dataA) {  //La data da non può essere maggiore della data a
                    return response()->json(["error" => "La Data finale deve essere minore o uguale alla Data iniziale"]);
                } else {
                    $logs = Logs::where("DataOra", ">=", $dataDA)->where("DataOra", "<=", $dataA)->orderBy("DataOra", "DESC");
                }
            }
            
            if($codice != ""){
                $logs = $logs->where('Codice', $codice)->with('utente')->paginate(10);
            } else {
                $logs = $logs->with('utente')->paginate(10);
            }

            return response()->json($logs);
        }catch(\Throwable $th){            
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Caricamento dei Log", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Caricamento dei Log"));
            return response()->json(0);
        }
    }
}
