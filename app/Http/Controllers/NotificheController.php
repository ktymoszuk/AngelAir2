<?php

namespace App\Http\Controllers;

use App\Models\Notifica;
use App\Models\Soglia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class NotificheController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index() {
        return view("notifiche");
    }

    // SELECT
    // visualizzazione notifica
    public function show(Request $request) {
        $automazione = $request->automazione;
        $categoriaDisp = $request->categoriadisp;
        $testo = $request->testo;
        $paginazione = $request->paginazione;

        if (empty($automazione) && empty($categoriaDisp) && empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->offset($paginazione)->limit(100)->get();
        } elseif (empty($automazione) && empty($categoriaDisp) && !empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("Nome", "like", "%$testo%")->offset($paginazione)->limit(100)->get();
        } elseif (empty($automazione) && !empty($categoriaDisp) && empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codTipoDisp", $categoriaDisp)->offset($paginazione)->limit(100)->get();
        } elseif (empty($automazione) && !empty($categoriaDisp) && !empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codTipoDisp", $categoriaDisp)->where("Nome", "like", "%$testo%")->offset($paginazione)->limit(100)->get();
        } elseif (!empty($automazione) && empty($categoriaDisp) && empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codAutomazione", $automazione)->offset($paginazione)->limit(100)->get();
        } elseif (!empty($automazione) && empty($categoriaDisp) && !empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codAutomazione", $automazione)->where("Nome", "like", "%$testo%")->offset($paginazione)->limit(100)->get();
        } elseif (!empty($automazione) && !empty($categoriaDisp) && empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codAutomazione", $automazione)->where("codTipoDisp", $categoriaDisp)->offset($paginazione)->limit(100)->get();
        } elseif (!empty($automazione) && !empty($categoriaDisp) && !empty($testo)) {
            $res = Soglia::with("automazione", "categoriadispositivo", "notifica")->where("codCategoriaSoglia", 5)->where("codAutomazione", $automazione)->where("codTipoDisp", $categoriaDisp)->where("Nome", "like", "%$testo%")->offset($paginazione)->limit(100)->get();
        }

        return $res;
    }

    // UPDATE
    public function update(Request $request) {
        try {
            $id = $request->id;
            $isNotifica = ($request->isNotifica == 1) ? 0 : 1;

            $res = Notifica::where("id", $id)->update([
                "isNotifica" => $isNotifica
            ]);

            if ($res) {
                Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Stato ricevimento notifica $id aggiornato");
                return response()->json(["success" => "Stato ricevimento aggiornato"]);
            } else {
                Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Errore aggiornamento stato ricevimento notifica $id");
                return response()->json(["error" => "Errore aggiornamento stato ricevimento notifica"]);
            }
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore processo aggiornamento della soglia $request->id -> $th");
            Session::flash("error", "Errore processo aggiornamento stato ricevimento notifica");
        }
    }
}
