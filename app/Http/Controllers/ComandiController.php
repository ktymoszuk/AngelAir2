<?php

namespace App\Http\Controllers;

use App\Models\Comando;
use App\Manager\AxManager;
use App\Manager\LogManager;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use App\Models\InvioComandi;
use App\Models\CronologiaComandi;
use App\Models\ComandoDispositivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ComandoRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Automazione;
use App\Models\Sincronizzazione;
use Illuminate\Support\Facades\Artisan;

class ComandiController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('comandi');
    }

    public function download()
    {
        return response()->download(public_path('csv/download/titolo-dispositivi-template.csv'));
    }

    public function insertOne(Request $request)
    {
        try {
            $query = LogicManager::generazioneQuery($request->all());
            $codAutomazione = (int)LogicManager::getCheckboxState($request, "codAutomazione");

            $nomeComando = str_replace(' ', '', strtolower($query['Nome']));  //lowercase e trim del nome del comando
        
            $comandiDB = Comando::whereRaw("replace(lower(Nome), ' ', '') like (?)", ["%{$nomeComando}%"])->get();
            
            $numeroComandi = $comandiDB->count();

            if ($numeroComandi > 0) {
                $query['Nome'] = $query['Nome'] . $numeroComandi;
            }

            $isAutomatico = LogicManager::getCheckboxState($request, "isAutomatico");
            $isManuale = LogicManager::getCheckboxState($request, "isManuale");
            
            $query["isAutomatico"] = $isAutomatico;
            $query["isManuale"] = $isManuale;

            $AxSystemTag = Automazione::where('id', $codAutomazione)->first()->AxSystemTag;

            $res = Comando::insert($query);

            if ($res) {
                //Settiamo nella tabella sincronizzazioni AxId
                Artisan::call('ax:generate', ["tabella" => "Comandi"]);

                //Otteniamo l'ultima riga inserita
                $AxId = Sincronizzazione::orderBy('id', 'desc')->first()->AxId;

                //Sincronizziamo il campo
                AxManager::SincCampoSingolo($AxId, 'VS_GA', 'insert', "Comandi", $AxSystemTag);

                Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Comando inserito");
                Session::flash("success", "Comando inserito correttamente");
            } else {
                Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Comando non inserito");
                Session::flash("error", "Errore nell'inserimento del comando");
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Comando", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Comando "));
            return back();
        }
    }

    //Update comando
    public function update(Request $request)
    {
        try {
            $id = (int)$request->id;

            $isAutomatico = LogicManager::getCheckboxState($request, "isAutomatico");
            $isManuale = LogicManager::getCheckboxState($request, "isManuale");
            
            $query = LogicManager::generazioneQuery($request->all());
            $query["isAutomatico"] = $isAutomatico;
            $query["isManuale"] = $isManuale;

            $res = Comando::where("id", $request->id)->update($query);

            if($res){
                //Otteniamo l'AxId e AxSystemTag del dispositivo
                // $comando = Comando::where('id', $request->id)->first();
                // $AxId = $comando->AxId;
                // $AxSystemTag = $comando->AxSystemTag;
                
                //Sincronizziamo il campo
                // AxManager::SincCampoSingolo($AxId, 'VS_GA', 'update', "Comandi", $AxSystemTag);

                LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Comando", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(1, true, "Comando"));
            }else{
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Comando", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Comando"));
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Comando", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Comando "));
            return back();
        }
    }

    //Eliminazione comando
    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $comandoDispRow = ComandoDispositivo::where("codComando", $id)->get();
            if ($comandoDispRow != null) {
                $res = ComandoDispositivo::where("codComando", $id)->delete();
                if ($res) {
                    Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Associazioni con dispositivi del comando $id cancellate correttamente");
                } else {
                    Log::channel("operazioni")->error($request->ip() . " -> " . Auth::id() . ": Errore nella cancellazione delle associazioni con dispositivi del comando $id");
                }
            }

            $res = Comando::where("id", $id)->delete();
            if ($res) {
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Comando", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Comando"));
            } else {
                LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Comando", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Comando"));
            }

            // // $comandoDispRow = ComandiDispositivi::where("codComando", $id)->get();
            // if ($comandoDispRow != null) {
            //     //Otteniamo l'AxId del dispositivo
            //     $res = true;
            //     // $AxIds = ComandiDispositivi::where('codComando', $id)->pluck('AxId');
            //     foreach ($AxIds as $AxId) {
            //         //Sincronizziamo il campo
            //         $result = AxManager::SincCampoSingolo($AxId, 'VS_GA', 'delete', "ComandiDispositivi");
            //         if(!$result){
            //             $res = false;
            //         }
            //     }
            //     if ($res) {
            //         Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Associazioni con dispositivi del comando $id cancellate correttamente");
            //     } else {
            //         Log::channel("operazioni")->error($request->ip() . " -> " . Auth::id() . ": Errore nella cancellazione delle associazioni con dispositivi del comando $id");
            //     }
            // }

            // $res = true;
            // $AxIds = Comando::where('id', $id)->pluck('AxId');
            // foreach ($AxIds as $AxId) {
            //     //Sincronizziamo il campo
            //     $result = AxManager::SincCampoSingolo($AxId, 'VS_GA', 'delete', "Comandi");
            //     if(!$result){
            //         $res = false;
            //     }
            // }
            // if ($res) {
            //     Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Comando $id eliminato");
            //     Session::flash("success", "Comando eliminato correttamente");
            // } else {
            //     Log::channel("operazioni")->info($request->ip() . " -> " . Auth::id() . ": Comando non eliminato");
            //     Session::flash("error", "Errore nell'eliminazione del comando");
            // }


            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Comando", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(2, false, "Comando"));
            return back();
        }
    }
}
