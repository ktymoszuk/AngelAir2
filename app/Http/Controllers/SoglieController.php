<?php

namespace App\Http\Controllers;

use App\Http\Requests\SogliaRequest;
use App\Manager\LogicManager;
use App\Manager\LogManager;
use App\Manager\NotificheManager;
use App\Manager\SoglieManager;
use App\Models\Automazione;
use App\Models\CategoriaDispositivo;
use App\Models\Dispositivo;
use App\Models\Notifica;
use App\Models\Soglia;
use App\Models\SogliaDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SoglieController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function view()
    {
        return view("soglie");
    }

    // INSERT
    public function insert(SogliaRequest $request) {
        try {
            $operatori = ['==', '<', '<=', '>', '>=', '!='];

            $query = LogicManager::generazioneQuery($request->all());
            $operatoreMinimo = $request->OperatoreMinimo;
            if (in_array($operatoreMinimo, $operatori)) {
                $query['OperatoreMinimo'] = $operatoreMinimo;
            } else {
                Session::flash("error", LogManager::messaggiOperazioni(0, false, "\"Operatore Minimo\" non valido"));
                return back();
            }
            $operatoreMassimo = $request->OperatoreMassimo;
            if (!is_null($request->OperatoreMassimo)) {
                if (in_array($operatoreMassimo, $operatori)) {
                    $query['OperatoreMassimo'] = $operatoreMassimo;
                } else {
                    Session::flash("error", LogManager::messaggiOperazioni(0, false, "\"Operatore Minimo\" non valido"));
                    return back();
                }
            }
            
            if ($query['ValoreMinimo']) {
                if ($query['TipoDatoSoglia'] == 'Testuale') {
                    $query['ValoreMinimo'] = strval($query['ValoreMinimo']);
                } else if ($query['TipoDatoSoglia'] == 'Numerica') {
                    $query['ValoreMinimo'] = floatval($query['ValoreMinimo']);
                }
            } else {
                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Soglia senza valore", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Soglia senza valore"));
                return back();
            }
            
            if ($query["TipoSoglia"] == "Singola") {
                $query["ValoreMassimo"] = null;
                $query["OperatoreMassimo"] = null;
                $query["AliasMassimo"] = 'Valore Massimo';
                $query["ColoreMassimo"] = '#ff1803';
            } else {
                if ($query['ValoreMassimo']) {
                    if ($query['TipoDatoSoglia'] == 'Testuale') {
                        $query['ValoreMassimo'] = strval($query['ValoreMassimo']);
                    } else if ($query['TipoDatoSoglia'] == 'Numerica') {
                            $query['ValoreMassimo'] = floatval($query['ValoreMassimo']);
                    }
                } else {
                    LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Soglia senza valore", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(0, false, "Soglia senza valore"));
                    return back();
                }
            }



            $res = Soglia::insert($query);

            if ($res) {
                // NotificheManager::insertNotifica(); // inizializzo le notifiche per tutti gli utenti
                LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(0, true, "Soglia"));
                return back();
            } else {
                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(0, false, "Soglia"));
                return back();
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", null, false, $request->ip(), Auth::id(), "Soglia", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(null, false, "Soglia"));
            return back();
        }
    }

    public function tipodisp(Request $request) {
        try {
            $result = array();
            $categoriaDisp = $request->categoriadisp;
            $automazione = $request->automazione;
    
            if (!empty($categoriaDisp) && !empty($automazione)) {
                $tipoDispRow = CategoriaDispositivo::where("IdTipo", $categoriaDisp)->first();
            }
            
            // Lista chiavi
            $chiaviList = ['Testo1', 'Testo2', 'Testo3', 'Testo4', 'Testo5', 'Testo6', 'Testo7', 'Testo8', 'Testo9', 'Testo10', 'Numero1', 'Numero2', 'Numero3', 'Numero4', 'Numero5', 'Numero6', 'Numero7', 'Numero8', 'Numero9', 'Numero10', 'FloatP1', 'FloatP2', 'FloatP3', 'FloatP4', 'FloatP5', 'FloatP6', 'FloatP7', 'FloatP8', 'FloatP9', 'FloatP10', 'DataOra'];
            foreach ($chiaviList as $col) {
                if (substr($col, 0, 4) == "Nume" || substr($col, 0, 4) == "Floa") {
                    if ($tipoDispRow->$col != null) {
                        $obj["Colonne"] = $col;
                        $obj["Valori"] = $tipoDispRow->$col;
                        array_push($result, $obj);
                    }
                }
            }       
    
            return response()->json($result);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "TipoDisp", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "tipi dispositivi"));
            return back();
        }
    }

    // UPDATE
    public function update(SogliaRequest $request) {
        try {
            $operatori = ['==', '<', '<=', '>', '>=', '!='];
            
            $query = LogicManager::generazioneQuery($request->all());
            $operatoreMinimo = $request->OperatoreMinimo;
            if (in_array($operatoreMinimo, $operatori)) {
                $query['OperatoreMinimo'] = $operatoreMinimo;
            } else {
                Session::flash("error", LogManager::messaggiOperazioni(0, false, "\"Operatore Minimo\" non valido"));
                return back();
            }
            $operatoreMassimo = $request->OperatoreMassimo;
            if (!is_null($request->OperatoreMassimo)) {
                if (in_array($operatoreMassimo, $operatori)) {
                    $query['OperatoreMassimo'] = $operatoreMassimo;
                } else {
                    Session::flash("error", LogManager::messaggiOperazioni(0, false, "\"Operatore Minimo\" non valido"));
                    return back();
                }
            }

            if ($query['ValoreMinimo']) {
                // if ($query['TipoDatoSoglia'] == 'Testuale') {
                //     $query['ValoreMinimo'] = strval($query['ValoreMinimo']);
                // } else if ($query['TipoDatoSoglia'] == 'Numerica') {
                //     $query['ValoreMinimo'] = floatval($query['ValoreMinimo']);
                // }
            } else {
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Soglia senza valore", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Soglia senza valore"));
                return back();
            }

            if ($query["TipoSoglia"] == "Singola") {
                $query["ValoreMassimo"] = null;
                $query["OperatoreMassimo"] = null;
                $query["AliasMassimo"] = 'Valore Massimo';
                $query["ColoreMassimo"] = '#ff1803';
            } else {
                if (isset($query['ValoreMassimo'])) {
                    // if ($query['TipoDatoSoglia'] == 'Testuale') {
                    //     $query['ValoreMinimo'] = strval($query['ValoreMassimo']);
                    // } else if ($query['TipoDatoSoglia'] == 'Numerica') {
                    //     $query['ValoreMassimo'] = floatval($query['ValoreMassimo']);
                    // }
                } else {
                    LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Soglia senza valore", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(1, false, "Soglia senza valore"));
                    return back();
                }
            }
            
            $query['codAutomazione'] = (int)$query['codAutomazione'];
            $query['codTipoDisp'] = (int)$query['codTipoDisp'];
            $query['codCategoriaSoglia'] = (int)$query['codCategoriaSoglia'];
            
            // dd($query);
            // dd('ciao');
            // $soglia = Soglia::where("id", $request->id)->first();
            
            
            $res = Soglia::where("id", $request->id)->update($query);
            
            if ($res) {
                LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(1, true, "Soglia"));
                return back();
            } else {
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Soglia"));
                return back();
            }

        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Soglia", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(1, false, "Soglia"));
            return back();
        }
    }

    // DELETE
    public function delete(Request $request) {
        try {
            $id = $request->id;
            $soglieDispRow = SogliaDispositivo::where("codSoglia", $id)->first();
            if ($soglieDispRow != null) {
                $res = SogliaDispositivo::where("codSoglia", $id)->delete();
                if ($res) {
                    LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                    Session::flash("success", LogManager::messaggiOperazioni(2, true, "Soglia"));    
                    return back();

                } else {
                    LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                    Session::flash("error", LogManager::messaggiOperazioni(2, false, "Soglia"));
                    return back();
                }
            }

            $res = Soglia::where("id", $request->id)->delete();
            if ($res) {
                // $res2 = Notifica::where("codSoglia", $id)->delete();
                // if ($res2) {
                //     LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Notifica soglia", $request, NULL);
                //     Session::flash("success", LogManager::messaggiOperazioni(2, true, "Notifica soglia"));    
                // }
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Soglia"));
                return back();

            } else {
                LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Soglia", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Soglia"));
                return back();
            }

        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Soglia", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Soglia"));
            return back();
        }
    }
}